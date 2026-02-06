<template>
  <div class="home-view">
    <h1 class="mb-4">Dashboard Overview</h1>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <StatsCard title="Active Projects" value="12" icon="📋" change="+2" trend="up" />
      </div>
      <div class="col-md-3 mb-3">
        <StatsCard title="Completed Tasks" value="48" icon="✅" change="+12" trend="up" />
      </div>
      <div class="col-md-3 mb-3">
        <StatsCard title="Pending Reviews" value="7" icon="⏳" change="-3" trend="down" />
      </div>
      <div class="col-md-3 mb-3">
        <StatsCard title="Team Members" value="8" icon="👥" change="+1" trend="up" />
      </div>
    </div>

    <!-- Main Content -->
    <div class="row">
      <!-- Projects -->
      <div class="col-lg-8 mb-4">
        <DashboardCard title="Recent Projects" icon="📁">
          <ProjectList :projects="projects" />
        </DashboardCard>
      </div>

      <!-- Calendar Widget -->
      <div class="col-lg-4 mb-4">
        <DashboardCard title="Calendar" icon="📅">
          <CalendarWidget />
        </DashboardCard>

        <!-- Upcoming Deadlines -->
        <DashboardCard title="Upcoming Deadlines" icon="⏰" class="mt-4">
          <div class="list-group list-group-flush">
            <div v-for="deadline in deadlines" :key="deadline.id"
              class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">{{ deadline.project }}</h6>
                <small class="text-muted">{{ deadline.task }}</small>
              </div>
              <span :class="`badge bg-${deadline.priority === 'high' ? 'danger' : 'warning'}`">
                {{ deadline.dueDate }}
              </span>
            </div>
          </div>
        </DashboardCard>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
      <div class="col-12">
        <DashboardCard title="Quick Actions" icon="⚡">
          <div class="row g-3">
            <div class="col-md-3 col-6">
              <button class="btn btn-outline-primary w-100 py-3">
                <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                New Project
              </button>
            </div>
            <div class="col-md-3 col-6">
              <button class="btn btn-outline-success w-100 py-3">
                <i class="bi bi-calendar-plus fs-4 d-block mb-2"></i>
                Add Event
              </button>
            </div>
            <div class="col-md-3 col-6">
              <button class="btn btn-outline-info w-100 py-3">
                <i class="bi bi-upload fs-4 d-block mb-2"></i>
                Upload Files
              </button>
            </div>
            <div class="col-md-3 col-6">
              <button class="btn btn-outline-warning w-100 py-3">
                <i class="bi bi-graph-up fs-4 d-block mb-2"></i>
                Generate Report
              </button>
            </div>
          </div>
        </DashboardCard>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardCard from '@/components/DashboardCard.vue'
import StatsCard from '@/components/StatsCard.vue'
import ProjectList from '@/components/ProjectList.vue'
import CalendarWidget from '@/components/CalendarWidget.vue'

export default {
  components: {
    DashboardCard,
    StatsCard,
    ProjectList,
    CalendarWidget
  },
  data() {
    return {
      projects: [
        { id: 1, name: 'Website Redesign', category: 'Web Development', status: 'Active', progress: 75, dueDate: '2024-02-15', icon: '🎨' },
        { id: 2, name: 'Mobile App', category: 'Mobile Development', status: 'Pending', progress: 30, dueDate: '2024-03-01', icon: '📱' },
        { id: 3, name: 'E-commerce Platform', category: 'E-commerce', status: 'Active', progress: 90, dueDate: '2024-01-31', icon: '🛒' },
        { id: 4, name: 'CRM System', category: 'Enterprise Software', status: 'On Hold', progress: 45, dueDate: '2024-03-15', icon: '💼' },
        { id: 5, name: 'Marketing Campaign', category: 'Marketing', status: 'Completed', progress: 100, dueDate: '2024-01-15', icon: '📢' }
      ],
      deadlines: [
        { id: 1, project: 'Website Redesign', task: 'Final Review', dueDate: 'Tomorrow', priority: 'high' },
        { id: 2, project: 'Mobile App', task: 'UI Design', dueDate: 'In 3 days', priority: 'medium' },
        { id: 3, project: 'CRM System', task: 'Database Setup', dueDate: 'Next Week', priority: 'medium' }
      ]
    }
  }
}
</script>

<style scoped>
.home-view {
  padding: 1rem;
}
</style>
