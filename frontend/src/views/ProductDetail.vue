<template>
  <div class="product-detail-page">
    <div class="container">
      <div v-if="loading" class="loading">Loading product...</div>
      
      <div v-else-if="error" class="error-message">
        {{ error }}
        <router-link to="/products" class="btn btn-secondary">Back to Products</router-link>
      </div>
      
      <div v-else-if="product" class="product-detail">
        <div class="product-image">
          <img 
            :src="product.image_url || 'https://via.placeholder.com/600x400?text=Product'" 
            :alt="product.name"
          />
        </div>
        
        <div class="product-info">
          <h1>{{ product.name }}</h1>
          
          <div class="price-section">
            <span class="price">${{ parseFloat(product.price).toFixed(2) }}</span>
            <span class="stock" :class="{ 'out-of-stock': product.stock === 0 }">
              {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
            </span>
          </div>
          
          <div class="description">
            <h3>Description</h3>
            <p>{{ product.description || 'No description available.' }}</p>
          </div>
          
          <div class="quantity-selector">
            <label for="quantity">Quantity:</label>
            <div class="quantity-controls">
              <button @click="decreaseQuantity" :disabled="quantity <= 1">-</button>
              <input 
                type="number" 
                id="quantity" 
                v-model.number="quantity" 
                min="1" 
                :max="product.stock"
              />
              <button @click="increaseQuantity" :disabled="quantity >= product.stock">+</button>
            </div>
          </div>
          
          <div class="actions">
            <button 
              @click="addToCart" 
              class="btn btn-primary btn-large"
              :disabled="product.stock === 0"
            >
              {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
            <router-link to="/products" class="btn btn-secondary">
              Back to Products
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../services/api';
import { cartStore } from '../stores/cart';

const route = useRoute();
const router = useRouter();
const product = ref(null);
const loading = ref(true);
const error = ref('');
const quantity = ref(1);

onMounted(async () => {
  await fetchProduct();
});

const fetchProduct = async () => {
  try {
    const response = await api.getProduct(route.params.id);
    product.value = response.data;
  } catch (err) {
    error.value = 'Product not found.';
    console.error('Error fetching product:', err);
  } finally {
    loading.value = false;
  }
};

const increaseQuantity = () => {
  if (quantity.value < product.value.stock) {
    quantity.value++;
  }
};

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

const addToCart = () => {
  cartStore.addItem(product.value, quantity.value);
  alert(`${quantity.value} x ${product.value.name} added to cart!`);
  router.push('/cart');
};
</script>

<style scoped>
.product-detail-page {
  padding: 2rem 0;
  min-height: calc(100vh - 200px);
}

.container {
  max-width: 1200px;
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

.product-detail {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-image {
  width: 100%;
  height: 400px;
  overflow: hidden;
  border-radius: 8px;
  background: #f8f9fa;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-info h1 {
  margin: 0 0 1.5rem 0;
  color: #2c3e50;
}

.price-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #ecf0f1;
}

.price {
  font-size: 2rem;
  font-weight: bold;
  color: #27ae60;
}

.stock {
  font-size: 1rem;
  color: #27ae60;
  font-weight: 500;
}

.stock.out-of-stock {
  color: #e74c3c;
}

.description {
  margin-bottom: 2rem;
}

.description h3 {
  margin-bottom: 0.5rem;
  color: #2c3e50;
}

.description p {
  color: #7f8c8d;
  line-height: 1.6;
}

.quantity-selector {
  margin-bottom: 2rem;
}

.quantity-selector label {
  display: block;
  margin-bottom: 0.5rem;
  color: #2c3e50;
  font-weight: 500;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.quantity-controls button {
  width: 40px;
  height: 40px;
  border: 1px solid #ddd;
  background: white;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1.25rem;
  transition: background 0.3s;
}

.quantity-controls button:hover:not(:disabled) {
  background: #f8f9fa;
}

.quantity-controls button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.quantity-controls input {
  width: 80px;
  height: 40px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.actions {
  display: flex;
  gap: 1rem;
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
  display: inline-block;
}

.btn-large {
  flex: 2;
}

.btn-primary {
  background: #667eea;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #5568d3;
}

.btn-primary:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.btn-secondary {
  background: #ecf0f1;
  color: #2c3e50;
  flex: 1;
}

.btn-secondary:hover {
  background: #d5dbdb;
}

@media (max-width: 768px) {
  .product-detail {
    grid-template-columns: 1fr;
  }
}
</style>
