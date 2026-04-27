# E-Commerce Frontend Guide

## Overview
This is a Vue 3 e-commerce frontend application that connects to the Laravel backend API.

## Features

✅ **User Authentication**
- Register new account
- Login/Logout
- Protected routes

✅ **Product Management**
- Browse all products
- View product details
- Add products to cart

✅ **Shopping Cart**
- Add/remove items
- Update quantities
- View cart total
- Free shipping on orders over $50

✅ **Checkout Process**
- Enter shipping address
- Select payment method
- Place orders

✅ **Order Management**
- View order history
- Track order status
- View order details

## Tech Stack

- **Vue 3** - Progressive JavaScript framework
- **Vue Router** - Official router for Vue.js
- **Axios** - HTTP client for API calls
- **Vite** - Next generation frontend tooling

## Project Structure

```
frontend/
├── src/
│   ├── components/
│   │   └── Navbar.vue          # Navigation bar component
│   ├── router/
│   │   └── index.js            # Vue Router configuration
│   ├── services/
│   │   └── api.js              # API service layer
│   ├── stores/
│   │   └── cart.js             # Cart state management
│   ├── views/
│   │   ├── Home.vue            # Landing page
│   │   ├── Login.vue           # Login page
│   │   ├── Register.vue        # Registration page
│   │   ├── Products.vue        # Product listing
│   │   ├── ProductDetail.vue   # Single product view
│   │   ├── Cart.vue            # Shopping cart
│   │   ├── Checkout.vue        # Checkout process
│   │   ├── Orders.vue          # Order history
│   │   └── OrderDetail.vue     # Single order view
│   ├── App.vue                 # Root component
│   ├── main.js                 # Application entry point
│   └── style.css               # Global styles
├── public/                     # Static assets
├── index.html                  # HTML template
├── package.json                # Dependencies
└── vite.config.js              # Vite configuration
```

## Installation

### Prerequisites
- Node.js (v16 or higher)
- npm or yarn

### Steps

1. **Navigate to frontend directory:**
   ```bash
   cd frontend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **Start development server:**
   ```bash
   npm run dev
   ```

4. **Open browser:**
   Navigate to `http://localhost:5173`

## Configuration

### API Base URL

The API base URL is configured in `src/services/api.js`:

```javascript
const API_URL = 'http://localhost:8000/api';
```

If your Laravel backend runs on a different port, update this URL.

### CORS Configuration

Make sure your Laravel backend allows requests from the frontend origin. In Laravel, this is typically configured in `config/cors.php`.

## Available Scripts

- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run preview` - Preview production build

## Features Breakdown

### Authentication Flow

1. **Register**: User creates account → Auto-login → Redirect to products
2. **Login**: User logs in → Token stored in localStorage → Redirect to products
3. **Logout**: Clear token and cart → Redirect to login

### Shopping Flow

1. **Browse Products**: View all available products
2. **View Details**: Click on product to see full details
3. **Add to Cart**: Select quantity and add to cart
4. **View Cart**: Review items, update quantities, or remove items
5. **Checkout**: Enter shipping address and payment method
6. **Place Order**: Order created and payment processed
7. **View Orders**: See order history and track status

### Cart Management

The cart uses a reactive store (`stores/cart.js`) that:
- Persists to localStorage
- Calculates totals automatically
- Syncs across all components
- Clears on logout or successful checkout

### Protected Routes

Routes that require authentication:
- `/cart`
- `/checkout`
- `/orders`
- `/orders/:id`

Unauthenticated users are redirected to `/login`.

## API Integration

All API calls are centralized in `src/services/api.js`:

```javascript
// Example usage in components
import api from '../services/api';

// Get products
const response = await api.getProducts();

// Create order
const order = await api.createOrder(orderData);
```

### Authentication

The API service automatically:
- Adds Bearer token to requests
- Handles 401 errors (redirects to login)
- Manages token in localStorage

## Styling

The application uses:
- **Scoped CSS** in each component
- **Global styles** in `style.css`
- **Responsive design** with CSS Grid and Flexbox
- **Mobile-first approach**

### Color Scheme

- Primary: `#667eea` (Purple)
- Success: `#27ae60` (Green)
- Danger: `#e74c3c` (Red)
- Dark: `#2c3e50`
- Light: `#f8f9fa`

## Testing the Application

### 1. Start Backend
```bash
cd backend
php artisan serve
```

### 2. Start Frontend
```bash
cd frontend
npm run dev
```

### 3. Test Flow

1. **Register** a new account at `/register`
2. **Browse products** at `/products`
3. **Add items** to cart
4. **View cart** at `/cart`
5. **Checkout** at `/checkout`
6. **View orders** at `/orders`

## Common Issues

### CORS Errors

**Problem**: API requests blocked by CORS policy

**Solution**: 
1. Check Laravel `config/cors.php`
2. Ensure `'paths' => ['api/*']` is set
3. Add frontend URL to `'allowed_origins'`

### 401 Unauthorized

**Problem**: API returns 401 even with token

**Solution**:
1. Check if token is in localStorage
2. Verify Laravel Sanctum is configured
3. Check if token is expired

### Products Not Loading

**Problem**: Products page is empty

**Solution**:
1. Check if backend is running
2. Verify API URL in `api.js`
3. Check browser console for errors
4. Ensure products exist in database

## Production Build

### Build for Production

```bash
npm run build
```

This creates optimized files in the `dist/` directory.

### Deploy

You can deploy the `dist/` folder to:
- **Netlify**
- **Vercel**
- **GitHub Pages**
- **AWS S3**
- Any static hosting service

### Environment Variables

For production, update the API URL:

1. Create `.env.production`:
   ```
   VITE_API_URL=https://your-api-domain.com/api
   ```

2. Update `api.js`:
   ```javascript
   const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
   ```

## Future Enhancements

- [ ] Add product search and filters
- [ ] Implement product categories
- [ ] Add product reviews and ratings
- [ ] Implement wishlist functionality
- [ ] Add user profile page
- [ ] Implement order tracking
- [ ] Add payment gateway integration
- [ ] Implement real-time notifications
- [ ] Add admin dashboard
- [ ] Implement product image upload

## Support

For issues or questions:
1. Check the API documentation in `backend/API_DOCUMENTATION.md`
2. Review browser console for errors
3. Check Laravel logs in `backend/storage/logs/`

## License

MIT License
