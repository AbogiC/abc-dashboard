import { defineStore } from 'pinia'
import { authAPI } from '@/services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
    isAuthenticated: !!localStorage.getItem('token')
  }),

  actions: {
    async login(credentials) {
      try {
        const response = await authAPI.login(credentials)
        this.setAuth(response)
        return { success: true }
      } catch (error) {
        return { success: false, message: error.message }
      }
    },

    async register(userData) {
      try {
        const response = await authAPI.register(userData)
        return { success: true, data: response }
      } catch (error) {
        return { success: false, message: error.message }
      }
    },

    async logout() {
      try {
        await authAPI.logout()
      } finally {
        this.clearAuth()
        window.location.href = '/login'
      }
    },

    async verifyToken() {
      try {
        const response = await authAPI.verifyToken()
        return response.valid
      } catch (error) {
        console.log('Token verification failed:', error)
        this.clearAuth()
        return false
      }
    },

    setAuth(authData) {
      this.user = authData.user
      this.token = authData.token
      this.isAuthenticated = true

      localStorage.setItem('user', JSON.stringify(authData.user))
      localStorage.setItem('token', authData.token)
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.isAuthenticated = false

      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },

    updateUser(userData) {
      this.user = { ...this.user, ...userData }
      localStorage.setItem('user', JSON.stringify(this.user))
    }
  }
})
