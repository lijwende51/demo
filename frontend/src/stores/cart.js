import { reactive } from 'vue';

const state = reactive({
  items: [],
  total: 0,
});

export const cartStore = {
  state,

  addItem(product, quantity = 1) {
    const existingItem = state.items.find(item => item.product.id === product.id);
    
    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      state.items.push({
        product,
        quantity,
      });
    }
    
    this.calculateTotal();
    this.saveToLocalStorage();
  },

  removeItem(productId) {
    state.items = state.items.filter(item => item.product.id !== productId);
    this.calculateTotal();
    this.saveToLocalStorage();
  },

  updateQuantity(productId, quantity) {
    const item = state.items.find(item => item.product.id === productId);
    if (item) {
      item.quantity = quantity;
      if (item.quantity <= 0) {
        this.removeItem(productId);
      } else {
        this.calculateTotal();
        this.saveToLocalStorage();
      }
    }
  },

  clearCart() {
    state.items = [];
    state.total = 0;
    this.saveToLocalStorage();
  },

  calculateTotal() {
    state.total = state.items.reduce((sum, item) => {
      return sum + (parseFloat(item.product.price) * item.quantity);
    }, 0);
  },

  saveToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify(state.items));
  },

  loadFromLocalStorage() {
    const saved = localStorage.getItem('cart');
    if (saved) {
      state.items = JSON.parse(saved);
      this.calculateTotal();
    }
  },

  getItemCount() {
    return state.items.reduce((sum, item) => sum + item.quantity, 0);
  },
};

// Load cart on initialization
cartStore.loadFromLocalStorage();
