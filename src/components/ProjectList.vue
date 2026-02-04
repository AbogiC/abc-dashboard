<template>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Project</th>
          <th>Status</th>
          <th>Progress</th>
          <th>Due Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="project in projects" :key="project.id">
          <td>
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                <span class="text-primary">{{ project.icon }}</span>
              </div>
              <div>
                <h6 class="mb-0">{{ project.name }}</h6>
                <small class="text-muted">{{ project.category }}</small>
              </div>
            </div>
          </td>
          <td>
            <span :class="`badge bg-${getStatusColor(project.status)}`">
              {{ project.status }}
            </span>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <div class="progress flex-grow-1 me-2" style="height: 6px;">
                <div class="progress-bar" :class="`bg-${getProgressColor(project.progress)}`"
                  :style="{ width: project.progress + '%' }"></div>
              </div>
              <small>{{ project.progress }}%</small>
            </div>
          </td>
          <td>{{ project.dueDate }}</td>
          <td>
            <button class="btn btn-sm btn-outline-primary me-1">
              <i class="bi bi-eye"></i>
            </button>
            <button class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-pencil"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    projects: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    getStatusColor(status) {
      const colors = {
        'Active': 'success',
        'Pending': 'warning',
        'On Hold': 'secondary',
        'Completed': 'primary'
      }
      return colors[status] || 'secondary'
    },
    getProgressColor(progress) {
      if (progress >= 80) return 'success'
      if (progress >= 50) return 'warning'
      return 'danger'
    }
  }
}
</script>
