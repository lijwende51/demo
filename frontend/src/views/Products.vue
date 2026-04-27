<template>
  <div class="products-page">
    <div class="container">
      <h1>Our Products</h1>
      
      <div v-if="loading" class="loading">Loading products...</div>
      
      <div v-else-if="error" class="error-message">
        {{ error }}
      </div>
      
      <div v-else-if="products.length === 0" class="empty-state">
        <p>No products available at the moment.</p>
      </div>
      
      <div v-else class="products-grid">
        <div v-for="product in products" :key="product.id" class="product-card">
          <div class="product-image">
            <img 
              :src="product.image_url || 'https://via.placeholder.com/300x200?text=Product'" 
              :alt="product.name"
            />
          </div>
          <div class="product-info">
            <h3>{{ product.name }}</h3>
            <p class="description">{{ truncateText(product.description, 100) }}</p>
            <div class="product-footer">
              <span class="price">${{ parseFloat(product.price).toFixed(2) }}</span>
              <span class="stock" :class="{ 'out-of-stock': product.stock === 0 }">
                {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
              </span>
            </div>
            <div class="product-actions">
              <router-link :to="`/products/${product.id}`" class="btn btn-secondary">
                View Details
              </router-link>
              <button 
                @click="addToCart(product)" 
                class="btn btn-primary"
                :disabled="product.stock === 0"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import { cartStore } from '../stores/cart';

const products = ref([]);
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  await fetchProducts();
});

const fetchProducts = async () => {
  try {
    const response = await api.getProducts();
    products.value = response.data;
  } catch (err) {
    error.value = 'Failed to load products. Please try again later.';
    console.error('Error fetching products:', err);
  } finally {
    loading.value = false;
  }
};

const addToCart = (product) => {
  cartStore.addItem(product, 1);
  alert(`${product.name} added to cart!`);
};

const truncateText = (text, maxLength) => {
  if (!text) return '';
  if (text.length <= maxLength) return text;
  return text.substring(0, maxLength) + '...';
};
</script>

<style scoped>
.products-page {
  padding: 2rem 0;
  min-height: calc(100vh - 200px);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

h1 {
  text-align: center;
  margin-bottom: 2rem;
  color: #2c3e50;
}

.loading,
.empty-state {
  text-align: center;
  padding: 3rem;
  color: #7f8c8d;
}

.error-message {
  background: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 4px;
  text-align: center;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}

.product-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.product-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
  background: #f8f9fa;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-info {
  padding: 1.5rem;
}

.product-info h3 {
  margin: 0 0 0.5rem 0;
  color: #2c3e50;
  font-size: 1.25rem;
}

.description {
  color: #7f8c8d;
  margin-bottom: 1rem;
  min-height: 3rem;
}

.product-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.price {
  font-size: 1.5rem;
  font-weight: bold;
  color: #27ae60;
}

.stock {
  font-size: 0.875rem;
  color: #27ae60;
}

.stock.out-of-stock {
  color: #e74c3c;
}

.product-actions {
  display: flex;
  gap: 0.5rem;
}

.btn {
  flex: 1;
  padding: 0.75rem;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s;
  font-size: 0.875rem;
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
}

.btn-secondary:hover {
  background: #d5dbdb;
}
</style>
