<template>
  <div class="checkout-page">
    <div class="container">
      <h1>Checkout</h1>
      
      <div v-if="cart.items.length === 0" class="empty-cart">
        <p>Your cart is empty</p>
        <router-link to="/products" class="btn btn-primary">Continue Shopping</router-link>
      </div>
      
      <div v-else class="checkout-content">
        <div class="checkout-form">
          <h2>Shipping Information</h2>
          
          <div v-if="error" class="error-message">
            {{ error }}
          </div>
          
          <form @submit.prevent="handleCheckout">
            <div class="form-group">
              <label for="address">Shipping Address *</label>
              <textarea
                id="address"
                v-model="form.shipping_address"
                required
                rows="3"
                placeholder="Enter your full shipping address"
              ></textarea>
            </div>
            
            <h2>Payment Method</h2>
            
            <div class="form-group">
              <label>Select Payment Method *</label>
              <div class="payment-methods">
                <label class="payment-option">
                  <input type="radio" v-model="form.payment_method" value="credit_card" required />
                  <span>💳 Credit Card</span>
                </label>
                <label class="payment-option">
                  <input type="radio" v-model="form.payment_method" value="debit_card" />
                  <span>💳 Debit Card</span>
                </label>
                <label class="payment-option">
                  <input type="radio" v-model="form.payment_method" value="paypal" />
                  <span>🅿️ PayPal</span>
                </label>
                <label class="payment-option">
                  <input type="radio" v-model="form.payment_method" value="cash" />
                  <span>💵 Cash on Delivery</span>
                </label>
              </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-large" :disabled="loading">
              {{ loading ? 'Processing...' : 'Place Order' }}
            </button>
          </form>
        </div>
        
        <div class="order-summary">
          <h2>Order Summary</h2>
          
          <div class="summary-items">
            <div v-for="item in cart.items" :key="item.product.id" class="summary-item">
              <span>{{ item.product.name }} x {{ item.quantity }}</span>
              <span>${{ (parseFloat(item.product.price) * item.quantity).toFixed(2) }}</span>
            </div>
          </div>
          
          <div class="summary-totals">
            <div class="summary-row">
              <span>Subtotal:</span>
              <span>${{ cart.total.toFixed(2) }}</span>
            </div>
            
            <div class="summary-row">
              <span>Shipping:</span>
              <span>{{ cart.total >= 50 ? 'FREE' : '$5.00' }}</span>
            </div>
            
            <div class="summary-row total">
              <span>Total:</span>
              <span>${{ finalTotal.toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { cartStore } from '../stores/cart';
import api from '../services/api';

const router = useRouter();
const cart = cartStore.state;
const loading = ref(false);
const error = ref('');

const form = ref({
  shipping_address: '',
  payment_method: 'credit_card',
});

const finalTotal = computed(() => {
  const shipping = cart.total >= 50 ? 0 : 5;
  return cart.total + shipping;
});

const handleCheckout = async () => {
  loading.value = true;
  error.value = '';
  
  try {
    const user = JSON.parse(localStorage.getItem('user'));
    
    // Prepare order items
    const items = cart.items.map(item => ({
      product_id: item.product.id,
      quantity: item.quantity,
      price: parseFloat(item.product.price),
    }));
    
    // Create order
    const orderData = {
      user_id: user.id,
      items: items,
      total_price: cart.total,
      discount: 0,
      final_price: finalTotal.value,
      shipping_address: form.value.shipping_address,
      status: 'pending',
    };
    
    const orderResponse = await api.createOrder(orderData);
    const order = orderResponse.data;
    
    // Process payment
    const paymentData = {
      order_id: order.id,
      payment_method: form.value.payment_method,
      amount: finalTotal.value,
      status: 'completed',
    };
    
    await api.processPayment(paymentData);
    
    // Clear cart
    cartStore.clearCart();
    
    // Redirect to orders page
    alert('Order placed successfully!');
    router.push('/orders');
    
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to place order. Please try again.';
    console.error('Checkout error:', err);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.checkout-page {
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

.empty-cart {
  text-align: center;
  padding: 4rem 2rem;
  background: white;
  border-radius: 8px;
}

.checkout-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
}

.checkout-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
}

.checkout-form h2 {
  margin: 0 0 1.5rem 0;
  color: #2c3e50;
}

.form-group {
  margin-bottom: 2rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

textarea,
input[type="text"] {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  font-family: inherit;
  box-sizing: border-box;
}

textarea:focus,
input:focus {
  outline: none;
  border-color: #667eea;
}

.payment-methods {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.payment-option {
  display: flex;
  align-items: center;
  padding: 1rem;
  border: 2px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  transition: border-color 0.3s;
}

.payment-option:hover {
  border-color: #667eea;
}

.payment-option input[type="radio"] {
  margin-right: 0.5rem;
}

.payment-option span {
  font-size: 1rem;
}

.error-message {
  background: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1.5rem;
}

.btn {
  padding: 1rem 2rem;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s;
  font-size: 1rem;
}

.btn-large {
  width: 100%;
  font-size: 1.125rem;
}

.btn-primary {
  background: #667eea;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #5568d3;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.order-summary {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  height: fit-content;
}

.order-summary h2 {
  margin: 0 0 1.5rem 0;
  color: #2c3e50;
}

.summary-items {
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #ecf0f1;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  color: #7f8c8d;
}

.summary-totals {
  margin-top: 1.5rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  color: #7f8c8d;
}

.summary-row.total {
  font-size: 1.25rem;
  font-weight: bold;
  color: #2c3e50;
  padding-top: 1rem;
  border-top: 2px solid #ecf0f1;
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .checkout-content {
    grid-template-columns: 1fr;
  }
  
  .payment-methods {
    grid-template-columns: 1fr;
  }
}
</style>
