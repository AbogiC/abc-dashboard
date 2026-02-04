<template>
  <div class="projects-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="mb-0">My Projects</h1>
      <button class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Project
      </button>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
      <div class="col-md-8">
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" class="form-control" placeholder="Search projects...">
          <select class="form-select" style="max-width: 200px;">
            <option>All Categories</option>
            <option>Web Development</option>
            <option>Mobile Development</option>
            <option>Design</option>
            <option>Marketing</option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="btn-group w-100">
          <button v-for="filter in filters" :key="filter" class="btn"
            :class="activeFilter === filter ? 'btn-primary' : 'btn-outline-primary'" @click="activeFilter = filter">
            {{ filter }}
          </button>
        </div>
      </div>
    </div>

    <!-- Project Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div v-for="project in filteredProjects" :key="project.id" class="col">
        <div class="card h-100 shadow-sm project-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="project-icon rounded-circle p-3" :style="{ backgroundColor: project.color + '20' }">
                <span class="fs-3" :style="{ color: project.color }">{{ project.icon }}</span>
              </div>
              <span :class="`badge bg-${getStatusColor(project.status)}`">
                {{ project.status }}
              </span>
            </div>

            <h5 class="card-title">{{ project.name }}</h5>
            <p class="card-text text-muted">{{ project.description }}</p>

            <div class="mb-3">
              <div class="d-flex justify-content-between mb-1">
                <small>Progress</small>
                <small>{{ project.progress }}%</small>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar" :style="{ width: project.progress + '%', backgroundColor: project.color }">
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <div>
                <small class="text-muted">Due Date</small>
                <div class="fw-bold">{{ project.dueDate }}</div>
              </div>
              <div class="avatar-group">
                <div v-for="member in project.team" :key="member" class="avatar avatar-sm" :title="member">
                  {{ getInitials(member) }}
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <div class="d-flex justify-content-between">
              <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye me-1"></i>View
              </button>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-pencil me-1"></i>Edit
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Project Statistics -->
    <div class="row mt-5">
      <div class="col-12">
        <DashboardCard title="Project Statistics">
          <div class="row">
            <div class="col-md-3 text-center mb-3">
              <div class="display-4 fw-bold text-primary">12</div>
              <div class="text-muted">Total Projects</div>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="display-4 fw-bold text-success">8</div>
              <div class="text-muted">Active</div>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="display-4 fw-bold text-warning">3</div>
              <div class="text-muted">Pending</div>
            </div>
            <div class="col-md-3 text-center mb-3">
              <div class="display-4 fw-bold text-danger">1</div>
              <div class="text-muted">Overdue</div>
            </div>
          </div>
        </DashboardCard>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardCard from '@/components/DashboardCard.vue'

export default {
  components: {
    DashboardCard
  },
  data() {
    return {
      activeFilter: 'All',
      filters: ['All', 'Active', 'Pending', 'Completed'],
      projects: [
        {
          id: 1,
          name: 'E-commerce Platform',
          description: 'Build a modern e-commerce platform with React and Node.js',
          status: 'Active',
          progress: 75,
          dueDate: '2024-02-15',
          icon: '🛒',
          color: '#0d6efd',
          team: ['John Doe', 'Jane Smith', 'Bob Johnson']
        },
        {
          id: 2,
          name: 'Mobile Banking App',
          description: 'iOS and Android banking application with biometric login',
          status: 'Active',
          progress: 45,
          dueDate: '2024-03-20',
          icon: '📱',
          color: '#198754',
          team: ['Alice Brown', 'Charlie Wilson']
        },
        {
          id: 3,
          name: 'Portfolio Website',
          description: 'Personal portfolio website with blog and project showcase',
          status: 'Completed',
          progress: 100,
          dueDate: '2024-01-10',
          icon: '🎨',
          color: '#6f42c1',
          team: ['David Lee']
        },
        {
          id: 4,
          name: 'Inventory System',
          description: 'Enterprise inventory management system',
          status: 'Pending',
          progress: 20,
          dueDate: '2024-04-01',
          icon: '📦',
          color: '#fd7e14',
          team: ['Eva Green', 'Frank White', 'Grace Black']
        },
        {
          id: 5,
          name: 'Marketing Dashboard',
          description: 'Analytics dashboard for marketing campaigns',
          status: 'Active',
          progress: 60,
          dueDate: '2024-02-28',
          icon: '📊',
          color: '#0dcaf0',
          team: ['Henry Ford', 'Ivy Stone']
        },
        {
          id: 6,
          name: 'Learning Platform',
          description: 'Online course platform with video streaming',
          status: 'On Hold',
          progress: 30,
          dueDate: '2024-05-15',
          icon: '🎓',
          color: '#dc3545',
          team: ['Jack Moon', 'Karen Sun']
        }
      ]
    }
  },
  computed: {
    filteredProjects() {
      if (this.activeFilter === 'All') return this.projects
      return this.projects.filter(p => p.status === this.activeFilter)
    }
  },
  methods: {
    getStatusColor(status) {
      const colors = {
        'Active': 'success',
        'Pending': 'warning',
        'Completed': 'primary',
        'On Hold': 'secondary'
      }
      return colors[status] || 'secondary'
    },
    getInitials(name) {
      return name.split(' ').map(n => n[0]).join('')
    }
  }
}
</script>

<style scoped>
.projects-view {
  padding: 1rem;
}

.project-icon {
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.project-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.project-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1) !important;
}

.avatar-group {
  display: flex;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background-color: #6c757d;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: bold;
  margin-left: -8px;
  border: 2px solid white;
}

.avatar:first-child {
  margin-left: 0;
}

.avatar-sm {
  width: 28px;
  height: 28px;
  font-size: 0.7rem;
}
</style>
