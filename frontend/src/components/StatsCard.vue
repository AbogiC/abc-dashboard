<template>
  <div class="card bg-gradient shadow-sm border-0 h-100">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">{{ title }}</h6>
          <h2 class="mb-0">{{ value }}</h2>
        </div>
        <div class="icon-shape rounded-circle bg-primary bg-opacity-10 p-3">
          <span class="fs-3 text-primary">{{ icon }}</span>
        </div>
      </div>
      <p class="mt-3 mb-0 text-muted">
        <span :class="`text-${trend === 'up' ? 'success' : 'danger'}`">
          {{ trend === 'up' ? '↑' : '↓' }} {{ change }}
        </span>
        <span class="ms-2">from last month</span>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    value: [String, Number],
    icon: String,
    change: String,
    trend: {
      type: String,
      validator: (value) => ['up', 'down'].includes(value)
    }
  }
}
</script>

<style scoped>
.bg-gradient {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.icon-shape {
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
