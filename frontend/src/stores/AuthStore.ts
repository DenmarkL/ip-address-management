import { defineStore } from 'pinia';
import axios from 'axios';

interface UserRoleResponse {
    is_admin: boolean;
    user_id: number;
}

interface AuthState {
    isAdmin: boolean;
    userId: number | null;
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        isAdmin: false,
        userId: null
    }),
    actions: {
        async fetchUserRole(): Promise<void> {
            try {
                const { data } = await axios.get<UserRoleResponse>(`${import.meta.env.VITE_API_BASE_URL}/api/user`, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('accessToken')}` }
                });
                this.isAdmin = data.is_admin;
                this.userId = data.user_id;
            } catch (error) {
                console.error('Error fetching user role:', error);
                this.isAdmin = false;
                this.userId = null;
            }
        }
    }
});
