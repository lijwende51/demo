<script setup>
import { onMounted } from 'vue';
import Navbar from './components/Navbar.vue';

onMounted(() => {
  // Initialize app
});
</script>

<template>
  <div id="app">
    <Navbar />
    <main>
      <router-view />
    </main>
    <footer class="footer">
      <div class="container">
        <p>&copy; 2026 E-Shop. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: 1.6;
  color: #2c3e50;
  background: #f8f9fa;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

.footer {
  background: #2c3e50;
  color: white;
  padding: 2rem 0;
  text-align: center;
  margin-top: auto;
}

.footer .container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.footer p {
  margin: 0;
}
</style>
