<template>
  <div class="card h-100 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">{{ title }}</h5>
      <span v-if="icon" class="text-primary fs-4">{{ icon }}</span>
    </div>
    <div class="card-body">
      <slot></slot>
    </div>
    <div v-if="footer" class="card-footer text-muted">
      {{ footer }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    icon: String,
    footer: String
  }
}
</script>
