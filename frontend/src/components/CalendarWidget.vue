<template>
  <div class="calendar-widget">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="mb-0">{{ currentMonth }} {{ currentYear }}</h6>
      <div>
        <button @click="prevMonth" class="btn btn-sm btn-outline-secondary me-1">
          <i class="bi bi-chevron-left"></i>
        </button>
        <button @click="nextMonth" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>
    </div>

    <div class="calendar-grid mb-3">
      <div class="calendar-header d-flex">
        <div v-for="day in days" :key="day" class="calendar-day-header text-center">
          {{ day }}
        </div>
      </div>
      <div class="calendar-body">
        <div v-for="week in calendarDays" :key="week" class="calendar-week d-flex">
          <div v-for="day in week" :key="day.date" class="calendar-day" :class="{
            'today': day.isToday,
            'current-month': day.isCurrentMonth,
            'has-event': day.hasEvent
          }" @click="selectDay(day)">
            <div class="day-number">{{ day.day }}</div>
            <div v-if="day.hasEvent" class="event-dot"></div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="selectedDay" class="events-list">
      <h6 class="mb-3">Events for {{ selectedDay.date }}</h6>
      <div v-for="event in selectedDay.events" :key="event.id" class="event-item mb-2 p-2 border rounded">
        <div class="d-flex justify-content-between">
          <strong>{{ event.title }}</strong>
          <span :class="`badge bg-${event.type === 'meeting' ? 'primary' : 'success'}`">
            {{ event.time }}
          </span>
        </div>
        <small class="text-muted">{{ event.description }}</small>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentDate: new Date(),
      selectedDay: null,
      days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    }
  },
  computed: {
    currentMonth() {
      return this.currentDate.toLocaleString('default', { month: 'long' })
    },
    currentYear() {
      return this.currentDate.getFullYear()
    },
    calendarDays() {
      const year = this.currentDate.getFullYear()
      const month = this.currentDate.getMonth()

      const firstDay = new Date(year, month, 1)
      const lastDay = new Date(year, month + 1, 0)
      const daysInMonth = lastDay.getDate()

      const startDay = firstDay.getDay()

      const today = new Date()
      const todayStr = today.toISOString().split('T')[0]

      const days = []
      let week = []

      // Previous month days
      const prevMonthLastDay = new Date(year, month, 0).getDate()
      for (let i = startDay - 1; i >= 0; i--) {
        const date = new Date(year, month - 1, prevMonthLastDay - i)
        week.push({
          day: date.getDate(),
          date: date.toISOString().split('T')[0],
          isCurrentMonth: false,
          isToday: false,
          hasEvent: false
        })
      }

      // Current month days
      for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day)
        const dateStr = date.toISOString().split('T')[0]
        const hasEvent = this.hasEventForDate(dateStr)

        week.push({
          day: day,
          date: dateStr,
          isCurrentMonth: true,
          isToday: dateStr === todayStr,
          hasEvent: hasEvent,
          events: hasEvent ? this.getEventsForDate(dateStr) : []
        })

        if (week.length === 7) {
          days.push([...week])
          week = []
        }
      }

      // Next month days
      if (week.length > 0) {
        const nextMonthDay = 1
        while (week.length < 7) {
          const date = new Date(year, month + 1, nextMonthDay + (week.length - (daysInMonth % 7)))
          week.push({
            day: date.getDate(),
            date: date.toISOString().split('T')[0],
            isCurrentMonth: false,
            isToday: false,
            hasEvent: false
          })
        }
        days.push([...week])
      }

      return days
    }
  },
  mounted() {
    this.selectToday()
  },
  methods: {
    prevMonth() {
      this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1)
    },
    nextMonth() {
      this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1)
    },
    selectToday() {
      const today = new Date()
      const todayStr = today.toISOString().split('T')[0]
      const todayDay = this.calendarDays.flat().find(day => day.date === todayStr)
      if (todayDay) {
        this.selectedDay = todayDay
      }
    },
    selectDay(day) {
      this.selectedDay = day
    },
    hasEventForDate(date) {
      // Mock data - replace with real data later
      const events = {
        '2024-01-15': [{ id: 1, title: 'Team Meeting', time: '10:00 AM', description: 'Weekly sync', type: 'meeting' }],
        '2024-01-20': [{ id: 2, title: 'Project Deadline', time: '5:00 PM', description: 'Submit final report', type: 'deadline' }],
        '2024-01-25': [
          { id: 3, title: 'Client Call', time: '2:00 PM', description: 'Demo presentation', type: 'meeting' },
          { id: 4, title: 'Lunch with Team', time: '12:30 PM', description: 'Team building', type: 'personal' }
        ]
      }
      return date in events
    },
    getEventsForDate(date) {
      const events = {
        '2024-01-15': [{ id: 1, title: 'Team Meeting', time: '10:00 AM', description: 'Weekly sync', type: 'meeting' }],
        '2024-01-20': [{ id: 2, title: 'Project Deadline', time: '5:00 PM', description: 'Submit final report', type: 'deadline' }],
        '2024-01-25': [
          { id: 3, title: 'Client Call', time: '2:00 PM', description: 'Demo presentation', type: 'meeting' },
          { id: 4, title: 'Lunch with Team', time: '12:30 PM', description: 'Team building', type: 'personal' }
        ]
      }
      return events[date] || []
    }
  }
}
</script>

<style scoped>
.calendar-grid {
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
}

.calendar-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.calendar-day-header {
  flex: 1;
  padding: 0.5rem;
  font-weight: 600;
  font-size: 0.875rem;
}

.calendar-week {
  border-bottom: 1px solid #dee2e6;
}

.calendar-week:last-child {
  border-bottom: none;
}

.calendar-day {
  flex: 1;
  min-height: 80px;
  padding: 0.25rem;
  border-right: 1px solid #dee2e6;
  cursor: pointer;
  transition: background-color 0.2s;
}

.calendar-day:hover {
  background-color: #f8f9fa;
}

.calendar-day:last-child {
  border-right: none;
}

.calendar-day.current-month {
  background-color: white;
}

.calendar-day:not(.current-month) {
  background-color: #f8f9fa;
  color: #6c757d;
}

.calendar-day.today {
  background-color: #e7f1ff;
}

.day-number {
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.event-dot {
  width: 8px;
  height: 8px;
  background-color: #0d6efd;
  border-radius: 50%;
  margin: 0 auto;
}
</style>
