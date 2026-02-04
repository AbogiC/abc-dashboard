import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/backend/api'

// Create axios instance with default config
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response.data,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      window.location.href = '/login'
    }
    return Promise.reject(error.response?.data || error.message)
  }
)

// Auth API
export const authAPI = {
  login: (credentials) => api.post('/auth/login', credentials),
  register: (userData) => api.post('/auth/register', userData),
  logout: () => api.post('/auth/logout'),
  getProfile: () => api.get('/auth/profile'),
  verifyToken: () => api.get('/auth/verify')
}

// Projects API
export const projectsAPI = {
  getAll: (params) => api.get('/projects', { params }),
  getById: (id) => api.get(`/projects/${id}`),
  create: (projectData) => api.post('/projects', projectData),
  update: (id, projectData) => api.put(`/projects/${id}`, projectData),
  delete: (id) => api.delete(`/projects/${id}`),
  getStats: () => api.get('/projects/stats')
}

// Calendar API
export const calendarAPI = {
  getAll: (params) => api.get('/calendar', { params }),
  getById: (id) => api.get(`/calendar/${id}`),
  create: (eventData) => api.post('/calendar', eventData),
  update: (id, eventData) => api.put(`/calendar/${id}`, eventData),
  delete: (id) => api.delete(`/calendar/${id}`),
  getUpcoming: (limit) => api.get('/calendar/upcoming', { params: { limit } }),
  getByDateRange: (startDate, endDate) =>
    api.get('/calendar/range', { params: { start_date: startDate, end_date: endDate } })
}

// Dashboard Stats API
export const statsAPI = {
  getDashboardStats: () => api.get('/stats')
}

// Users API
export const usersAPI = {
  getAll: (params) => api.get('/users', { params }),
  getById: (id) => api.get(`/users/${id}`),
  updateProfile: (userData) => api.put(`/users/${userData.id}`, userData)
}

export default api
