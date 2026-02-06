<template>
  <div class="about-view">
    <div class="row">
      <!-- Profile Card -->
      <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <div class="profile-avatar mx-auto mb-3">
              <span class="fs-1">👤</span>
            </div>
            <h4 class="card-title">John Developer</h4>
            <p class="text-muted mb-3">Senior Full Stack Developer</p>

            <div class="d-flex justify-content-center mb-4">
              <a href="#" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-github"></i>
              </a>
              <a href="#" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-twitter"></i>
              </a>
            </div>

            <div class="contact-info text-start">
              <p class="mb-2">
                <i class="bi bi-envelope me-2"></i>
                john.developer@example.com
              </p>
              <p class="mb-2">
                <i class="bi bi-phone me-2"></i>
                (123) 456-7890
              </p>
              <p class="mb-0">
                <i class="bi bi-geo-alt me-2"></i>
                San Francisco, CA
              </p>
            </div>
          </div>
        </div>

        <!-- Skills -->
        <DashboardCard title="Skills" class="mt-4">
          <div class="skill-item mb-3" v-for="skill in skills" :key="skill.name">
            <div class="d-flex justify-content-between mb-1">
              <span>{{ skill.name }}</span>
              <span>{{ skill.level }}%</span>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar" :class="`bg-${skill.color}`" :style="{ width: skill.level + '%' }"></div>
            </div>
          </div>
        </DashboardCard>
      </div>

      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- About Me -->
        <DashboardCard title="About Me">
          <p class="lead">
            Passionate full-stack developer with 8+ years of experience building scalable web applications.
            I specialize in Vue.js, React, Node.js, and modern cloud technologies.
          </p>

          <p>
            Currently leading a team of 5 developers at TechCorp, where we build enterprise solutions for Fortune 500
            companies.
            I enjoy mentoring junior developers and contributing to open-source projects in my spare time.
          </p>

          <h5 class="mt-4 mb-3">Experience</h5>
          <div class="timeline">
            <div v-for="exp in experience" :key="exp.id" class="timeline-item mb-4">
              <div class="timeline-date">{{ exp.period }}</div>
              <div class="timeline-content">
                <h6>{{ exp.position }}</h6>
                <p class="text-muted mb-1">{{ exp.company }}</p>
                <p class="small mb-0">{{ exp.description }}</p>
              </div>
            </div>
          </div>

          <h5 class="mt-4 mb-3">Education</h5>
          <div class="row">
            <div v-for="edu in education" :key="edu.id" class="col-md-6 mb-3">
              <div class="card border">
                <div class="card-body">
                  <h6>{{ edu.degree }}</h6>
                  <p class="text-muted mb-1">{{ edu.school }}</p>
                  <p class="small mb-0">{{ edu.year }}</p>
                </div>
              </div>
            </div>
          </div>
        </DashboardCard>

        <!-- Recent Activity -->
        <DashboardCard title="Recent Activity" class="mt-4">
          <div class="activity-list">
            <div v-for="activity in activities" :key="activity.id" class="activity-item d-flex mb-3">
              <div class="activity-icon me-3">
                <span class="badge" :class="`bg-${activity.type === 'project' ? 'primary' : 'success'}`">
                  <i :class="`bi bi-${activity.icon}`"></i>
                </span>
              </div>
              <div class="activity-content flex-grow-1">
                <p class="mb-1">{{ activity.description }}</p>
                <small class="text-muted">{{ activity.time }}</small>
              </div>
            </div>
          </div>
        </DashboardCard>
      </div>
    </div>

    <!-- Projects Grid -->
    <div class="row mt-4">
      <div class="col-12">
        <DashboardCard title="Featured Projects">
          <div class="row row-cols-1 row-cols-md-3 g-4">
            <div v-for="project in featuredProjects" :key="project.id" class="col">
              <div class="card h-100">
                <div class="card-body">
                  <h5 class="card-title">{{ project.name }}</h5>
                  <p class="card-text">{{ project.description }}</p>
                  <div class="tech-stack mb-3">
                    <span v-for="tech in project.technologies" :key="tech" class="badge bg-light text-dark me-1">
                      {{ tech }}
                    </span>
                  </div>
                  <a :href="project.link" class="btn btn-outline-primary btn-sm">View Project</a>
                </div>
              </div>
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
      skills: [
        { name: 'Vue.js', level: 95, color: 'success' },
        { name: 'React', level: 90, color: 'primary' },
        { name: 'Node.js', level: 88, color: 'warning' },
        { name: 'TypeScript', level: 85, color: 'info' },
        { name: 'AWS', level: 80, color: 'secondary' },
        { name: 'Python', level: 75, color: 'danger' }
      ],
      experience: [
        {
          id: 1,
          period: '2020 - Present',
          position: 'Senior Full Stack Developer',
          company: 'TechCorp Inc.',
          description: 'Leading a team of 5 developers. Built multiple enterprise applications.'
        },
        {
          id: 2,
          period: '2017 - 2020',
          position: 'Frontend Developer',
          company: 'WebSolutions Ltd.',
          description: 'Developed responsive web applications using Vue.js and React.'
        },
        {
          id: 3,
          period: '2015 - 2017',
          position: 'Junior Developer',
          company: 'StartupXYZ',
          description: 'Full-stack development with focus on JavaScript technologies.'
        }
      ],
      education: [
        {
          id: 1,
          degree: 'MSc Computer Science',
          school: 'Stanford University',
          year: '2013 - 2015'
        },
        {
          id: 2,
          degree: 'BSc Software Engineering',
          school: 'MIT',
          year: '2009 - 2013'
        }
      ],
      activities: [
        {
          id: 1,
          type: 'project',
          icon: 'folder-plus',
          description: 'Created new project "E-commerce Platform"',
          time: '2 hours ago'
        },
        {
          id: 2,
          type: 'meeting',
          icon: 'calendar-check',
          description: 'Attended weekly team sync meeting',
          time: '5 hours ago'
        },
        {
          id: 3,
          type: 'project',
          icon: 'check-circle',
          description: 'Completed mobile app development',
          time: 'Yesterday'
        },
        {
          id: 4,
          type: 'code',
          icon: 'code-slash',
          description: 'Contributed to open-source project',
          time: '2 days ago'
        }
      ],
      featuredProjects: [
        {
          id: 1,
          name: 'E-commerce Platform',
          description: 'Full-featured online shopping platform with payment integration',
          technologies: ['Vue.js', 'Node.js', 'MongoDB', 'Stripe'],
          link: '#'
        },
        {
          id: 2,
          name: 'Task Management App',
          description: 'Collaborative task management with real-time updates',
          technologies: ['React', 'Firebase', 'Material-UI'],
          link: '#'
        },
        {
          id: 3,
          name: 'Weather Dashboard',
          description: 'Real-time weather forecasting with interactive maps',
          technologies: ['Vue.js', 'OpenWeather API', 'Leaflet'],
          link: '#'
        }
      ]
    }
  }
}
</script>

<style scoped>
.about-view {
  padding: 1rem;
}

.profile-avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 3rem;
}

.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 10px;
  top: 0;
  bottom: 0;
  width: 2px;
  background-color: #dee2e6;
}

.timeline-item {
  position: relative;
}

.timeline-item::before {
  content: '';
  position: absolute;
  left: -30px;
  top: 5px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: #0d6efd;
  border: 2px solid white;
}

.timeline-date {
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.activity-icon .badge {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}
</style>
