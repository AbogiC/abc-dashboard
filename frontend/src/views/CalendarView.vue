<template>
  <div class="calendar-view">
    <h1 class="mb-4">Calendar</h1>

    <div class="row">
      <!-- Main Calendar -->
      <div class="col-lg-8 mb-4">
        <DashboardCard title="Monthly Calendar">
          <CalendarWidget />
        </DashboardCard>

        <!-- Event Details -->
        <DashboardCard title="Event Details" class="mt-4">
          <div v-if="selectedEvent" class="event-details">
            <h5>{{ selectedEvent.title }}</h5>
            <div class="row mt-3">
              <div class="col-md-6">
                <p><strong>Date:</strong> {{ selectedEvent.date }}</p>
                <p><strong>Time:</strong> {{ selectedEvent.time }}</p>
                <p><strong>Type:</strong> <span
                    :class="`badge bg-${selectedEvent.type === 'meeting' ? 'primary' : 'success'}`">{{
                      selectedEvent.type }}</span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Location:</strong> {{ selectedEvent.location }}</p>
                <p><strong>Participants:</strong> {{ selectedEvent.participants.join(', ') }}</p>
              </div>
            </div>
            <p class="mt-3">{{ selectedEvent.description }}</p>
            <div class="mt-4">
              <button class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i>Edit Event
              </button>
              <button class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete Event
              </button>
            </div>
          </div>
          <div v-else class="text-center text-muted py-5">
            <i class="bi bi-calendar-event fs-1"></i>
            <p class="mt-3">Select an event to view details</p>
          </div>
        </DashboardCard>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Upcoming Events -->
        <DashboardCard title="Upcoming Events" icon="📌">
          <div class="list-group list-group-flush">
            <div v-for="event in upcomingEvents" :key="event.id" class="list-group-item list-group-item-action"
              :class="{ 'active': selectedEvent?.id === event.id }" @click="selectEvent(event)">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h6 class="mb-1">{{ event.title }}</h6>
                  <small>{{ event.date }} at {{ event.time }}</small>
                </div>
                <span :class="`badge bg-${event.type === 'meeting' ? 'primary' : 'success'}`">
                  {{ event.type }}
                </span>
              </div>
              <p class="mb-1 small text-muted">{{ event.description.substring(0, 60) }}...</p>
            </div>
          </div>
        </DashboardCard>

        <!-- Add Event Form -->
        <DashboardCard title="Add New Event" icon="➕" class="mt-4">
          <form @submit.prevent="addEvent" class="event-form">
            <div class="mb-3">
              <label class="form-label">Event Title</label>
              <input v-model="newEvent.title" type="text" class="form-control" required>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Date</label>
                <input v-model="newEvent.date" type="date" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Time</label>
                <input v-model="newEvent.time" type="time" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Type</label>
              <select v-model="newEvent.type" class="form-select" required>
                <option value="meeting">Meeting</option>
                <option value="deadline">Deadline</option>
                <option value="personal">Personal</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea v-model="newEvent.description" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="bi bi-plus-circle me-1"></i>Add Event
            </button>
          </form>
        </DashboardCard>

        <!-- Event Statistics -->
        <DashboardCard title="Event Statistics" icon="📈" class="mt-4">
          <div class="row text-center">
            <div class="col-6 mb-3">
              <div class="display-6 fw-bold text-primary">12</div>
              <small class="text-muted">This Month</small>
            </div>
            <div class="col-6 mb-3">
              <div class="display-6 fw-bold text-success">8</div>
              <small class="text-muted">Meetings</small>
            </div>
            <div class="col-6">
              <div class="display-6 fw-bold text-warning">3</div>
              <small class="text-muted">Pending</small>
            </div>
            <div class="col-6">
              <div class="display-6 fw-bold text-info">7</div>
              <small class="text-muted">Personal</small>
            </div>
          </div>
        </DashboardCard>
      </div>
    </div>
  </div>
</template>

<script>
import DashboardCard from '@/components/DashboardCard.vue'
import CalendarWidget from '@/components/CalendarWidget.vue'

export default {
  components: {
    DashboardCard,
    CalendarWidget
  },
  data() {
    return {
      selectedEvent: null,
      newEvent: {
        title: '',
        date: '',
        time: '',
        type: 'meeting',
        description: ''
      },
      upcomingEvents: [
        {
          id: 1,
          title: 'Team Weekly Sync',
          date: '2024-01-15',
          time: '10:00 AM',
          type: 'meeting',
          description: 'Weekly team meeting to discuss progress and blockers',
          location: 'Conference Room A',
          participants: ['John Doe', 'Jane Smith', 'Bob Johnson']
        },
        {
          id: 2,
          title: 'Client Presentation',
          date: '2024-01-18',
          time: '2:00 PM',
          type: 'meeting',
          description: 'Demo presentation for the new e-commerce platform',
          location: 'Client Office',
          participants: ['Alice Brown', 'Charlie Wilson']
        },
        {
          id: 3,
          title: 'Project Deadline',
          date: '2024-01-20',
          time: '5:00 PM',
          type: 'deadline',
          description: 'Final submission for the mobile banking app',
          location: 'Remote',
          participants: ['David Lee', 'Eva Green']
        },
        {
          id: 4,
          title: 'Lunch with Team',
          date: '2024-01-22',
          time: '12:30 PM',
          type: 'personal',
          description: 'Team building lunch at the new restaurant',
          location: 'Downtown Restaurant',
          participants: ['All Team Members']
        }
      ]
    }
  },
  mounted() {
    // Set default date to today
    const today = new Date().toISOString().split('T')[0]
    this.newEvent.date = today
    // Select first event by default
    if (this.upcomingEvents.length > 0) {
      this.selectedEvent = this.upcomingEvents[0]
    }
  },
  methods: {
    selectEvent(event) {
      this.selectedEvent = event
    },
    addEvent() {
      const newId = Math.max(...this.upcomingEvents.map(e => e.id)) + 1
      const event = {
        id: newId,
        ...this.newEvent,
        location: 'To be determined',
        participants: []
      }

      this.upcomingEvents.push(event)
      this.selectedEvent = event

      // Reset form
      this.newEvent = {
        title: '',
        date: new Date().toISOString().split('T')[0],
        time: '',
        type: 'meeting',
        description: ''
      }

      // Show success message
      alert('Event added successfully!')
    }
  }
}
</script>

<style scoped>
.calendar-view {
  padding: 1rem;
}

.event-details {
  padding: 1rem;
}

.list-group-item.active {
  background-color: #e7f1ff;
  border-color: #e7f1ff;
  color: #0d6efd;
}

.event-form {
  padding: 0.5rem;
}
</style>
