import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  headers: { "Content-Type": "application/json" },
});

// Flag to track token refresh status
let isRefreshing = false;
let failedRequestsQueue: ((token: string) => void)[] = [];

// Attach access token to each request
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle expired tokens and auto-refresh
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    // If it's a 401 but the request is for login, return the error immediately
    if (error.response?.status === 401 && originalRequest.url === "/login") {
      return Promise.reject(error); // Don't refresh token, let the login component handle it
    }

    // If the error is 401 (Unauthorized) and NOT from login
    if (error.response?.status === 401) {
      if (originalRequest._retry) {
        localStorage.removeItem("token");
        window.location.href = "/";
        return Promise.reject(error);
      }

      originalRequest._retry = true;

      if (!isRefreshing) {
        isRefreshing = true;

        try {
          // Attempt to refresh token
          const refreshResponse = await axios.post(
            "/refresh",
            {},
            { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
          );

          const newToken = refreshResponse.data.access_token;
          localStorage.setItem("token", newToken);

          // Retry failed requests with the new token
          failedRequestsQueue.forEach((callback) => callback(newToken));
          failedRequestsQueue = [];

          originalRequest.headers.Authorization = `Bearer ${newToken}`;
          return api(originalRequest);
        } catch (refreshError) {
          // Refresh failed: Logout user
          localStorage.removeItem("token");
          window.location.href = "/";
          return Promise.reject(refreshError);
        } finally {
          isRefreshing = false;
        }
      } else {
        // Queue failed requests while refreshing
        return new Promise((resolve) => {
          failedRequestsQueue.push((newToken: string) => {
            originalRequest.headers.Authorization = `Bearer ${newToken}`;
            resolve(api(originalRequest));
          });
        });
      }
    }

    return Promise.reject(error);
  }
);

export default api;
