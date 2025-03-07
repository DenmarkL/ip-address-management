import axios from "axios";
import router from '@/router';

const API_BASE_URL = `${import.meta.env.VITE_API_BASE_URL}/api`;

const api = axios.create({
  baseURL: API_BASE_URL,
  withCredentials: true, // Ensures the refresh token is sent in cookies
});

// Token Management Helpers
const getAccessToken = () => localStorage.getItem('accessToken');
const setAccessToken = (token: string) => localStorage.setItem('accessToken', token);
const clearAuthData = () => localStorage.removeItem('accessToken');

// Logout function
const logout = async () => {
  try {
    await api.post('/logout'); // Notify backend to invalidate the refresh token
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    clearAuthData();
    router.push('/login'); // Redirect to login page
  }
};

let isRefreshing = false;
let refreshSubscribers: ((token: string) => void)[] = [];

function onRefreshed(token: string) {
  refreshSubscribers.forEach((callback) => callback(token));
  refreshSubscribers = [];
}

function addRefreshSubscriber(callback: (token: string) => void) {
  refreshSubscribers.push(callback);
}

// Attach Access Token to Requests
api.interceptors.request.use((config) => {
  const token = getAccessToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        return new Promise((resolve) => {
          addRefreshSubscriber((token) => {
            originalRequest.headers.Authorization = `Bearer ${token}`;
            resolve(api(originalRequest));
          });
        });
      }

      originalRequest._retry = true;
      isRefreshing = true;

      try {

        const response = await axios.post(`${API_BASE_URL}/refresh`, {}, { 
          withCredentials: true,
          headers: { Authorization: `Bearer ${getAccessToken()}` } 
        });
        const newAccessToken = response.data.access_token;
        setAccessToken(newAccessToken);
        onRefreshed(newAccessToken);
        originalRequest.headers.Authorization = `Bearer ${newAccessToken}`;
        return api(originalRequest);
      } catch (refreshError) {
        // If refresh fails, log out user
        await logout();
        return Promise.reject(refreshError);
      } finally {
        isRefreshing = false;
      }
    }

    return Promise.reject(error);
  }
);

export default api;
