import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  headers: { "Content-Type": "application/json" },
});

let isRefreshing = false;
let failedRequestsQueue: ((token: string) => void)[] = [];

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    if (error.response?.status === 401 && originalRequest.url === "/login") {
      return Promise.reject(error);
    }

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
          const refreshResponse = await axios.post(
            "/refresh",
            {},
            { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
          );

          const newToken = refreshResponse.data.access_token;
          localStorage.setItem("token", newToken);

          failedRequestsQueue.forEach((callback) => callback(newToken));
          failedRequestsQueue = [];

          originalRequest.headers.Authorization = `Bearer ${newToken}`;
          return api(originalRequest);
        } catch (refreshError) {
          localStorage.removeItem("token");
          window.location.href = "/";
          return Promise.reject(refreshError);
        } finally {
          isRefreshing = false;
        }
      } else {
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
