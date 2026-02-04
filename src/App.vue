<template>
  <div id="app">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
      <div class="container-fluid">
        <router-link class="navbar-brand d-flex align-items-center" to="/">
          <i class="bi bi-speedometer2 me-2"></i>
          <span class="fw-bold">My Dashboard</span>
        </router-link>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link class="nav-link" to="/" active-class="active">
                <i class="bi bi-house me-1"></i>Home
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/projects" active-class="active">
                <i class="bi bi-folder me-1"></i>My Projects
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/calendar" active-class="active">
                <i class="bi bi-calendar me-1"></i>Calendar
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/about" active-class="active">
                <i class="bi bi-person me-1"></i>About Me
              </router-link>
            </li>
          </ul>

          <!-- User Menu -->
          <div class="navbar-nav">
            <div class="dropdown">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="avatar avatar-sm me-2">
                  <span>JD</span>
                </div>
                <span>John Developer</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <router-link class="dropdown-item" to="/profile">
                    <i class="bi bi-person-circle me-2"></i>Profile
                  </router-link>
                </li>
                <li>
                  <a class="dropdown-item" href="#">
                    <i class="bi bi-gear me-2"></i>Settings
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item text-danger" href="#">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-4">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light border-top">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-md-6">
            <span class="text-muted">© 2024 My Dashboard. All rights reserved.</span>
          </div>
          <div class="col-md-6 text-md-end">
            <a href="#" class="text-muted me-3">Privacy Policy</a>
            <a href="#" class="text-muted">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

.navbar-brand {
  font-size: 1.25rem;
}

.nav-link {
  font-weight: 500;
}

.nav-link.active {
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 0.375rem;
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
  font-weight: bold;
}

.avatar-sm {
  width: 28px;
  height: 28px;
  font-size: 0.875rem;
}

.footer {
  margin-top: auto;
}
</style>
