<template>
  <div class="order-detail-page">
    <div class="container">
      <div v-if="loading" class="loading">Loading order details...</div>
      
      <div v-else-if="error" class="error-message">
        {{ error }}
        <router-link to="/orders" class="btn btn-secondary">Back to Orders</router-link>
      </div>
      
      <div v-else-if="order" class="order-detail">
        <div class="order-header">
          <div>
            <h1>Order #{{ order.id }}</h1>
            <p class="order-date">Placed on {{ formatDate(order.created_at) }}</p>
          </div>
          <span class="status-badge" :class="`status-${order.status}`">
            {{ order.status }}
          </span>
        </div>
        
        <div class="order-content">
          <div class="order-section">
            <h2>Order Items</h2>
            <div class="items-list">
              <div v-for="(item, index) in order.items" :key="index" class="item-row">
                <div class="item-info">
                  <p class="item-name">Product ID: {{ item.product_id }}</p>
                  <p class="item-details">Quantity: {{ item.quantity }}</p>
                </div>
                <div class="item-price">
                  ${{ (parseFloat(item.price) * item.quantity).toFixed(2) }}
                </div>
              </div>
            </div>
          </div>
          
          <div class="order-section">
            <h2>Shipping Information</h2>
            <p>{{ order.shipping_address }}</p>
          </div>
          
          <div class="order-section">
            <h2>Payment Information</h2>
            <div v-if="payment" class="payment-info">
              <p><strong>Payment Method:</strong> {{ formatPaymentMethod(payment.payment_method) }}</p>
              <p><strong>Payment Status:</strong> 
                <span class="status-badge" :class="`status-${payment.status}`">
                  {{ payment.status }}
                </span>
              </p>
              <p><strong>Amount Paid:</strong> ${{ parseFloat(payment.amount).toFixed(2) }}</p>
            </div>
            <p v-else class="text-muted">Payment information not available</p>
          </div>
          
          <div class="order-summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
              <span>Subtotal:</span>
              <span>${{ parseFloat(order.total_price).toFixed(2) }}</span>
            </div>
            <div class="summary-row">
              <span>Discount:</span>
              <span>-${{ parseFloat(order.discount).toFixed(2) }}</span>
            </div>
            <div class="summary-row total">
              <span>Total:</span>
              <span>${{ parseFloat(order.final_price).toFixed(2) }}</span>
            </div>
          </div>
        </div>
        
        <div class="order-actions">
          <router-link to="/orders" class="btn btn-secondary">Back to Orders</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../services/api';

const route = useRoute();
const order = ref(null);
const payment = ref(null);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  await fetchOrderDetails();
});

const fetchOrderDetails = async () => {
  try {
    const orderResponse = await api.getOrder(route.params.id);
    order.value = orderResponse.data;
    
    // Try to fetch payment info
    try {
      const paymentsResponse = await api.getPayments();
      payment.value = paymentsResponse.data.find(p => p.order_id === order.value.id);
    } catch (err) {
      console.log('Could not fetch payment info:', err);
    }
  } catch (err) {
    error.value = 'Order not found.';
    console.error('Error fetching order:', err);
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

const formatPaymentMethod = (method) => {
  return method.split('_').map(word => 
    word.charAt(0).toUpperCase() + word.slice(1)
  ).join(' ');
};
</script>

<style scoped>
.order-detail-page {
  padding: 2rem 0;
  min-height: calc(100vh - 200px);
  background: #f8f9fa;
}

.container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 1rem;
}

.loading {
  text-align: center;
  padding: 3rem;
  color: #7f8c8d;
}

.error-message {
  background: #fee;
  color: #c33;
  padding: 2rem;
  border-radius: 4px;
  text-align: center;
}

.order-detail {
  background: white;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid #ecf0f1;
}

.order-header h1 {
  margin: 0 0 0.5rem 0;
  color: #2c3e50;
}

.order-date {
  color: #7f8c8d;
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

.status-completed {
  background: #d4edda;
  color: #155724;
}

.status-failed {
  background: #f8d7da;
  color: #721c24;
}

.order-content {
  margin-bottom: 2rem;
}

.order-section {
  margin-bottom: 2rem;
}

.order-section h2 {
  margin: 0 0 1rem 0;
  color: #2c3e50;
  font-size: 1.25rem;
}

.items-list {
  border: 1px solid #ecf0f1;
  border-radius: 4px;
  overflow: hidden;
}

.item-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.item-row:last-child {
  border-bottom: none;
}

.item-info p {
  margin: 0.25rem 0;
}

.item-name {
  font-weight: 500;
  color: #2c3e50;
}

.item-details {
  color: #7f8c8d;
  font-size: 0.875rem;
}

.item-price {
  font-size: 1.125rem;
  font-weight: bold;
  color: #27ae60;
}

.payment-info p {
  margin: 0.5rem 0;
  color: #7f8c8d;
}

.text-muted {
  color: #7f8c8d;
  font-style: italic;
}

.order-summary {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 4px;
}

.order-summary h2 {
  margin: 0 0 1rem 0;
  color: #2c3e50;
  font-size: 1.25rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  color: #7f8c8d;
}

.summary-row.total {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2c3e50;
  padding-top: 1rem;
  border-top: 2px solid #ddd;
  margin-top: 1rem;
}

.order-actions {
  display: flex;
  justify-content: center;
  padding-top: 1.5rem;
  border-top: 1px solid #ecf0f1;
}

.btn {
  padding: 0.75rem 2rem;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-secondary {
  background: #ecf0f1;
  color: #2c3e50;
}

.btn-secondary:hover {
  background: #d5dbdb;
}
</style>
