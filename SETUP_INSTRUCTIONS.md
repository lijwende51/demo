# E-Commerce Application - Complete Setup Guide

## Overview

This is a full-stack e-commerce application with:
- **Backend**: Laravel 13 (PHP) REST API
- **Frontend**: Vue 3 (JavaScript) SPA

## Prerequisites

Before you begin, ensure you have:

- **PHP** >= 8.3
- **Composer** (PHP package manager)
- **Node.js** >= 16
- **npm** or **yarn**
- **SQLite** (or MySQL/PostgreSQL)

## Quick Start

### 1. Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed

# Start Laravel development server
php artisan serve
```

Backend will run at: `http://localhost:8000`

### 2. Frontend Setup

```bash
# Navigate to frontend directory (in a new terminal)
cd frontend

# Install Node dependencies
npm install

# Start Vite development server
npm run dev
```

Frontend will run at: `http://localhost:5173`

## Detailed Setup

### Backend Configuration

#### 1. Database Setup

The project uses SQLite by default. The database file is at `backend/database/database.sqlite`.

**To use MySQL instead:**

1. Update `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

2. Create database:
   ```bash
   mysql -u root -p
   CREATE DATABASE ecommerce;
   exit;
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

#### 2. Laravel Sanctum (Authentication)

Sanctum is already configured. To verify:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

#### 3. CORS Configuration

Update `backend/config/cors.php` if needed:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:5173'],
'supports_credentials' => true,
```

#### 4. Create Sample Products (Optional)

You can manually add products via API or create a seeder:

```bash
php artisan make:seeder ProductSeeder
```

Then add products in the seeder and run:

```bash
php artisan db:seed --class=ProductSeeder
```

### Frontend Configuration

#### 1. API URL Configuration

The API URL is set in `frontend/src/services/api.js`:

```javascript
const API_URL = 'http://localhost:8000/api';
```

Update this if your backend runs on a different port.

#### 2. Environment Variables (Optional)

Create `frontend/.env`:

```env
VITE_API_URL=http://localhost:8000/api
```

Then update `api.js`:

```javascript
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';
```

## Testing the Application

### 1. Register a New User

1. Open `http://localhost:5173`
2. Click "Register"
3. Fill in the form:
   - Name: Test User
   - Email: test@example.com
   - Password: password123
   - Confirm Password: password123
4. Click "Register"

### 2. Add Products (Backend)

You can add products via API using Postman or curl:

```bash
# Login first to get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Use the token to create a product
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "name": "Sample Product",
    "description": "This is a sample product",
    "price": 29.99,
    "stock": 100,
    "image_url": "https://via.placeholder.com/300"
  }'
```

### 3. Test Shopping Flow

1. Browse products at `/products`
2. Click on a product to view details
3. Add to cart
4. View cart at `/cart`
5. Proceed to checkout
6. Enter shipping address
7. Select payment method
8. Place order
9. View orders at `/orders`

## API Endpoints

See `backend/API_DOCUMENTATION.md` for complete API reference.

### Quick Reference

**Authentication:**
- `POST /api/register` - Register user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user

**Products:**
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get single product
- `POST /api/products` - Create product (auth required)
- `PUT /api/products/{id}` - Update product (auth required)
- `DELETE /api/products/{id}` - Delete product (auth required)

**Cart:**
- `GET /api/carts` - List carts (auth required)
- `POST /api/carts` - Create cart (auth required)
- `PUT /api/carts/{id}` - Update cart (auth required)

**Orders:**
- `GET /api/orders` - List orders (auth required)
- `GET /api/orders/{id}` - Get single order (auth required)
- `POST /api/orders` - Create order (auth required)

**Payments:**
- `POST /api/payments/process` - Process payment (auth required)

## Project Structure

```
.
├── backend/                    # Laravel backend
│   ├── app/
│   │   ├── Http/Controllers/  # API controllers
│   │   └── Models/            # Eloquent models
│   ├── database/
│   │   ├── migrations/        # Database migrations
│   │   └── seeders/           # Database seeders
│   ├── routes/
│   │   └── api.php            # API routes
│   ├── .env                   # Environment config
│   └── API_DOCUMENTATION.md   # API docs
│
├── frontend/                   # Vue 3 frontend
│   ├── src/
│   │   ├── components/        # Vue components
│   │   ├── views/             # Page components
│   │   ├── router/            # Vue Router
│   │   ├── services/          # API service
│   │   └── stores/            # State management
│   ├── package.json
│   └── FRONTEND_GUIDE.md      # Frontend docs
│
└── SETUP_INSTRUCTIONS.md      # This file
```

## Common Issues & Solutions

### Issue: CORS Error

**Error**: "Access to XMLHttpRequest has been blocked by CORS policy"

**Solution**:
1. Check `backend/config/cors.php`
2. Ensure frontend URL is in `allowed_origins`
3. Restart Laravel server

### Issue: 401 Unauthorized

**Error**: API returns 401 even after login

**Solution**:
1. Check if token is saved in localStorage
2. Verify Sanctum is configured: `php artisan config:clear`
3. Check `backend/config/sanctum.php` stateful domains

### Issue: Database Connection Error

**Error**: "SQLSTATE[HY000] [2002] Connection refused"

**Solution**:
1. Check database credentials in `.env`
2. Ensure database server is running
3. Run `php artisan config:clear`

### Issue: Products Not Showing

**Problem**: Products page is empty

**Solution**:
1. Add products via API or seeder
2. Check browser console for errors
3. Verify backend is running
4. Check API URL in `frontend/src/services/api.js`

### Issue: npm install fails

**Error**: Dependency resolution errors

**Solution**:
```bash
rm -rf node_modules package-lock.json
npm install --legacy-peer-deps
```

## Development Tips

### Hot Reload

Both servers support hot reload:
- **Laravel**: Changes to PHP files reload automatically
- **Vite**: Changes to Vue files reload instantly

### Debugging

**Backend:**
- Check logs: `backend/storage/logs/laravel.log`
- Use `dd()` or `dump()` for debugging
- Enable debug mode in `.env`: `APP_DEBUG=true`

**Frontend:**
- Use Vue DevTools browser extension
- Check browser console for errors
- Use `console.log()` for debugging

### Database Management

**View database:**
```bash
# SQLite
sqlite3 backend/database/database.sqlite
.tables
SELECT * FROM users;

# MySQL
mysql -u root -p ecommerce
SHOW TABLES;
SELECT * FROM users;
```

**Reset database:**
```bash
php artisan migrate:fresh
php artisan db:seed
```

## Production Deployment

### Backend (Laravel)

1. Set environment to production in `.env`:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. Optimize:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. Deploy to:
   - Laravel Forge
   - AWS EC2
   - DigitalOcean
   - Heroku

### Frontend (Vue)

1. Build for production:
   ```bash
   npm run build
   ```

2. Deploy `dist/` folder to:
   - Netlify
   - Vercel
   - AWS S3 + CloudFront
   - GitHub Pages

3. Update API URL for production in `.env.production`

## Next Steps

1. ✅ Complete setup following this guide
2. ✅ Test all features
3. 📝 Add sample products
4. 🎨 Customize styling
5. 🚀 Deploy to production

## Support

- **Backend Docs**: `backend/API_DOCUMENTATION.md`
- **Frontend Docs**: `frontend/FRONTEND_GUIDE.md`
- **Fixes Applied**: `backend/FIXES_APPLIED.md`

## License

MIT License

---

**Happy Coding! 🚀**
