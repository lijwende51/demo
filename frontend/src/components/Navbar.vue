<template>
  <nav class="navbar">
    <div class="container">
      <router-link to="/" class="logo">
        <h1>🛒 E-Shop</h1>
      </router-link>
      
      <div class="nav-links">
        <router-link to="/">Home</router-link>
        <router-link to="/products">Products</router-link>
        
        <template v-if="isAuthenticated">
          <router-link to="/orders">Orders</router-link>
          <router-link to="/cart" class="cart-link">
            Cart
            <span v-if="cartCount > 0" class="badge">{{ cartCount }}</span>
          </router-link>
          <button @click="handleLogout" class="btn-logout">Logout</button>
        </template>
        
        <template v-else>
          <router-link to="/login">Login</router-link>
          <router-link to="/register">Register</router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { cartStore } from '../stores/cart';
import api from '../services/api';

const router = useRouter();
const isAuthenticated = ref(false);

const cartCount = computed(() => cartStore.getItemCount());

onMounted(() => {
  checkAuth();
});

const checkAuth = () => {
  isAuthenticated.value = !!localStorage.getItem('token');
};

const handleLogout = async () => {
  try {
    await api.logout();
  } catch (error) {
    console.error('Logout error:', error);
  } finally {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    cartStore.clearCart();
    isAuthenticated.value = false;
    router.push('/login');
  }
};
</script>

<style scoped>
.navbar {
  background: #2c3e50;
  color: white;
  padding: 1rem 0;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo h1 {
  margin: 0;
  font-size: 1.5rem;
  color: white;
}

.logo {
  text-decoration: none;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  transition: color 0.3s;
}

.nav-links a:hover,
.nav-links a.router-link-active {
  color: #42b983;
}

.cart-link {
  position: relative;
}

.badge {
  position: absolute;
  top: -8px;
  right: -10px;
  background: #e74c3c;
  color: white;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 0.75rem;
  font-weight: bold;
}

.btn-logout {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-logout:hover {
  background: #c0392b;
}
</style>
