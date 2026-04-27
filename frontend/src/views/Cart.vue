<template>
  <div class="cart-page">
    <div class="container">
      <h1>Shopping Cart</h1>
      
      <div v-if="cart.items.length === 0" class="empty-cart">
        <p>Your cart is empty</p>
        <router-link to="/products" class="btn btn-primary">Continue Shopping</router-link>
      </div>
      
      <div v-else class="cart-content">
        <div class="cart-items">
          <div v-for="item in cart.items" :key="item.product.id" class="cart-item">
            <div class="item-image">
              <img 
                :src="item.product.image_url || 'https://via.placeholder.com/100?text=Product'" 
                :alt="item.product.name"
              />
            </div>
            
            <div class="item-info">
              <h3>{{ item.product.name }}</h3>
              <p class="item-price">${{ parseFloat(item.product.price).toFixed(2) }}</p>
            </div>
            
            <div class="item-quantity">
              <button @click="decreaseQuantity(item.product.id)" :disabled="item.quantity <= 1">
                -
              </button>
              <span>{{ item.quantity }}</span>
              <button @click="increaseQuantity(item.product.id)" :disabled="item.quantity >= item.product.stock">
                +
              </button>
            </div>
            
            <div class="item-total">
              <p>${{ (parseFloat(item.product.price) * item.quantity).toFixed(2) }}</p>
            </div>
            
            <button @click="removeItem(item.product.id)" class="btn-remove">
              ✕
            </button>
          </div>
        </div>
        
        <div class="cart-summary">
          <h2>Order Summary</h2>
          
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
          
          <router-link to="/checkout" class="btn btn-primary btn-block">
            Proceed to Checkout
          </router-link>
          
          <router-link to="/products" class="btn btn-secondary btn-block">
            Continue Shopping
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { cartStore } from '../stores/cart';

const cart = cartStore.state;

const finalTotal = computed(() => {
  const shipping = cart.total >= 50 ? 0 : 5;
  return cart.total + shipping;
});

const increaseQuantity = (productId) => {
  const item = cart.items.find(i => i.product.id === productId);
  if (item) {
    cartStore.updateQuantity(productId, item.quantity + 1);
  }
};

const decreaseQuantity = (productId) => {
  const item = cart.items.find(i => i.product.id === productId);
  if (item && item.quantity > 1) {
    cartStore.updateQuantity(productId, item.quantity - 1);
  }
};

const removeItem = (productId) => {
  if (confirm('Remove this item from cart?')) {
    cartStore.removeItem(productId);
  }
};
</script>

<style scoped>
.cart-page {
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

.empty-cart p {
  font-size: 1.25rem;
  color: #7f8c8d;
  margin-bottom: 2rem;
}

.cart-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
}

.cart-items {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
}

.cart-item {
  display: grid;
  grid-template-columns: 100px 1fr auto auto auto;
  gap: 1.5rem;
  align-items: center;
  padding: 1.5rem 0;
  border-bottom: 1px solid #ecf0f1;
}

.cart-item:last-child {
  border-bottom: none;
}

.item-image {
  width: 100px;
  height: 100px;
  overflow: hidden;
  border-radius: 8px;
  background: #f8f9fa;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.item-info h3 {
  margin: 0 0 0.5rem 0;
  color: #2c3e50;
}

.item-price {
  color: #27ae60;
  font-weight: bold;
}

.item-quantity {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.item-quantity button {
  width: 32px;
  height: 32px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s;
}

.item-quantity button:hover:not(:disabled) {
  background: #f8f9fa;
}

.item-quantity button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.item-quantity span {
  min-width: 30px;
  text-align: center;
  font-weight: bold;
}

.item-total {
  font-size: 1.25rem;
  font-weight: bold;
  color: #2c3e50;
}

.btn-remove {
  width: 32px;
  height: 32px;
  border: none;
  background: #e74c3c;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-remove:hover {
  background: #c0392b;
}

.cart-summary {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  height: fit-content;
}

.cart-summary h2 {
  margin: 0 0 1.5rem 0;
  color: #2c3e50;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
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

.btn {
  padding: 1rem;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s;
  font-size: 1rem;
  display: block;
}

.btn-block {
  width: 100%;
  margin-bottom: 0.5rem;
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

@media (max-width: 768px) {
  .cart-content {
    grid-template-columns: 1fr;
  }
  
  .cart-item {
    grid-template-columns: 80px 1fr;
    gap: 1rem;
  }
  
  .item-quantity,
  .item-total {
    grid-column: 2;
  }
  
  .btn-remove {
    grid-column: 2;
    justify-self: end;
  }
}
</style>
