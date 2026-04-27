import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token to requests if available
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle 401 errors (unauthorized)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default {
  // Auth
  register(data) {
    return api.post('/register', data);
  },
  login(data) {
    return api.post('/login', data);
  },
  logout() {
    return api.post('/logout');
  },
  getUser() {
    return api.get('/user');
  },

  // Products
  getProducts() {
    return api.get('/products');
  },
  getProduct(id) {
    return api.get(`/products/${id}`);
  },
  createProduct(data) {
    return api.post('/products', data);
  },
  updateProduct(id, data) {
    return api.put(`/products/${id}`, data);
  },
  deleteProduct(id) {
    return api.delete(`/products/${id}`);
  },

  // Cart
  getCarts() {
    return api.get('/carts');
  },
  getCart(id) {
    return api.get(`/carts/${id}`);
  },
  createCart(data) {
    return api.post('/carts', data);
  },
  updateCart(id, data) {
    return api.put(`/carts/${id}`, data);
  },

  // Orders
  getOrders() {
    return api.get('/orders');
  },
  getOrder(id) {
    return api.get(`/orders/${id}`);
  },
  createOrder(data) {
    return api.post('/orders', data);
  },
  updateOrder(id, data) {
    return api.put(`/orders/${id}`, data);
  },

  // Payments
  processPayment(data) {
    return api.post('/payments/process', data);
  },
  getPayments() {
    return api.get('/payments');
  },
  getPayment(id) {
    return api.get(`/payments/${id}`);
  },
};
