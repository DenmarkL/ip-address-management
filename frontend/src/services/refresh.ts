import axios from 'axios';
import { useAuthStore } from '@/stores/auth';
import router from '@/router';

export class AuthService {
    async refreshAccessToken(): Promise<boolean> {
        try {
            const response = await axios.post(
                `${import.meta.env.VITE_API_BASE_URL}/api/refresh`,
                {},
                { withCredentials: true }
            );
            localStorage.setItem('accessToken', response.data.access_token);
            return true; 
        } catch (error) {
            console.error('Refresh token expired or invalid. Logging out...');
            this.handleLogout();
            return false; 
        }
    }

    async apiRequest<T>(
        url: string,
        method: 'get' | 'post' | 'put' | 'delete' = 'get',
        data?: any,
        retry = true 
    ): Promise<T> {
        try {
            const token = localStorage.getItem('accessToken');
            const response = await axios.request<T>({
                url,
                method,
                data,
                headers: token ? { Authorization: `Bearer ${token}` } : {},
            });
            return response.data;
        } catch (error) {
            if (axios.isAxiosError(error) && error.response?.status === 401 && retry) {
                console.warn('Access token expired. Trying to refresh...');
                const refreshed = await this.refreshAccessToken();
                if (refreshed) {
                    return this.apiRequest<T>(url, method, data, false); // 🔄 Retry only once
                }
            }
            throw error;
        }
    }

    async logout(): Promise<void> {
        try {
            await axios.post(`${import.meta.env.VITE_API_BASE_URL}/api/logout`, {}, { withCredentials: true });
        } catch {
            console.warn('Logout request failed. Proceeding to log out.');
        } finally {
            this.handleLogout();
        }
    }

    handleLogout(): void {
        localStorage.removeItem('accessToken');
        window.location.href = '/login'; // Redirect to login page
    }
}

export const authService = new AuthService();
