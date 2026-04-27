<template>
  <div class="orders-page">
    <div class="container">
      <h1>My Orders</h1>
      
      <div v-if="loading" class="loading">Loading orders...</div>
      
      <div v-else-if="error" class="error-message">
        {{ error }}
      </div>
      
      <div v-else-if="orders.length === 0" class="empty-state">
        <p>You haven't placed any orders yet.</p>
        <router-link to="/products" class="btn btn-primary">Start Shopping</router-link>
      </div>
      
      <div v-else class="orders-list">
        <div v-for="order in orders" :key="order.id" class="order-card">
          <div class="order-header">
            <div>
              <h3>Order #{{ order.id }}</h3>
              <p class="order-date">{{ formatDate(order.created_at) }}</p>
            </div>
            <span class="status-badge" :class="`status-${order.status}`">
              {{ order.status }}
            </span>
          </div>
          
          <div class="order-body">
            <div class="order-items">
              <p><strong>Items:</strong> {{ order.items.length }} item(s)</p>
              <ul>
                <li v-for="(item, index) in order.items" :key="index">
                  Product ID: {{ item.product_id }} - Quantity: {{ item.quantity }}
                </li>
              </ul>
            </div>
            
            <div class="order-details">
              <p><strong>Shipping Address:</strong></p>
              <p>{{ order.shipping_address }}</p>
            </div>
          </div>
          
          <div class="order-footer">
            <div class="order-total">
              <span>Total:</span>
              <span class="amount">${{ parseFloat(order.final_price).toFixed(2) }}</span>
            </div>
            <router-link :to="`/orders/${order.id}`" class="btn btn-secondary">
              View Details
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';

const orders = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  await fetchOrders();
});

const fetchOrders = async () => {
  try {
    const response = await api.getOrders();
    // Filter orders for current user
    const user = JSON.parse(localStorage.getItem('user'));
    orders.value = response.data.filter(order => order.user_id === user.id);
  } catch (err) {
    error.value = 'Failed to load orders. Please try again later.';
    console.error('Error fetching orders:', err);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>

<style scoped>
.orders-page {
  padding: 2rem 0;
  min-height: calc(100vh - 200px);
  background: #f8f9fa;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  margin-bottom: 2rem;
  color: #2c3e50;
}

.loading,
.empty-state {
  text-align: center;
  padding: 3rem;
  background: white;
  border-radius: 8px;
}

.empty-state p {
  color: #7f8c8d;
  margin-bottom: 1.5rem;
}

.error-message {
  background: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 4px;
  text-align: center;
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.order-card {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.order-header h3 {
  margin: 0 0 0.25rem 0;
  color: #2c3e50;
}

.order-date {
  color: #7f8c8d;
  font-size: 0.875rem;
  margin: 0;
}

.status-badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  text-transform: capitalize;
}

.status-pending {
  background: #fff3cd;
  color: #856404;
}

.status-processing {
  background: #cfe2ff;
  color: #084298;
}

.status-shipped {
  background: #d1ecf1;
  color: #0c5460;
}

.status-delivered {
  background: #d4edda;
  color: #155724;
}

.status-cancelled {
  background: #f8d7da;
  color: #721c24;
}

.order-body {
  margin-bottom: 1.5rem;
}

.order-items {
  margin-bottom: 1rem;
}

.order-items ul {
  margin: 0.5rem 0 0 1.5rem;
  padding: 0;
  color: #7f8c8d;
}

.order-items li {
  margin-bottom: 0.25rem;
}

.order-details p {
  margin: 0.25rem 0;
  color: #7f8c8d;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid #ecf0f1;
}

.order-total {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.125rem;
}

.order-total .amount {
  font-size: 1.5rem;
  font-weight: bold;
  color: #27ae60;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-primary {
  background: #667eea;
  color: white;
}

.btn-primary:hover {
  background: #5568d3;
}

.btn-secondary {
  background: #ecf0f1;
  color: #2c3e50;
}

.btn-secondary:hover {
  background: #d5dbdb;
}
</style>
