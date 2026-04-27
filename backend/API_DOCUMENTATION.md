# E-Commerce API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
This API uses Laravel Sanctum for authentication. Include the bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## Authentication Endpoints

### Register User
**POST** `/register`

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:** `201 Created`
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

---

### Login
**POST** `/login`

**Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:** `200 OK`
```json
{
  "message": "Login successful",
  "access_token": "1|abc123...",
  "token_type": "Bearer"
}
```

---

### Logout
**POST** `/logout`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "message": "Logged out successfully"
}
```

---

## Product Endpoints

### Get All Products
**GET** `/products`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
[
  {
    "id": 1,
    "name": "Product Name",
    "description": "Product description",
    "price": "99.99",
    "stock": 50,
    "image_url": "https://example.com/image.jpg",
    "category_id": null,
    "created_at": "2026-04-27T10:00:00.000000Z",
    "updated_at": "2026-04-27T10:00:00.000000Z"
  }
]
```

---

### Get Single Product
**GET** `/products/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "id": 1,
  "name": "Product Name",
  "description": "Product description",
  "price": "99.99",
  "stock": 50,
  "image_url": "https://example.com/image.jpg",
  "category_id": null,
  "created_at": "2026-04-27T10:00:00.000000Z",
  "updated_at": "2026-04-27T10:00:00.000000Z"
}
```

---

### Create Product
**POST** `/products`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "name": "New Product",
  "description": "Product description",
  "price": 99.99,
  "stock": 50,
  "image_url": "https://example.com/image.jpg",
  "category_id": null
}
```

**Response:** `201 Created`

---

### Update Product
**PUT** `/products/{id}`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "name": "Updated Product",
  "price": 89.99,
  "stock": 45
}
```

**Response:** `200 OK`

---

### Delete Product
**DELETE** `/products/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "message": "Product deleted successfully"
}
```

---

## Cart Endpoints

### Get All Carts
**GET** `/carts`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Get Single Cart
**GET** `/carts/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Create Cart
**POST** `/carts`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "user_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 99.99
    }
  ],
  "total_price": 199.98,
  "discount": 10.00,
  "final_price": 189.98
}
```

**Response:** `201 Created`

---

### Update Cart
**PUT** `/carts/{id}`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 3,
      "price": 99.99
    }
  ],
  "total_price": 299.97,
  "discount": 15.00,
  "final_price": 284.97
}
```

**Response:** `200 OK`

---

## Order Endpoints

### Get All Orders
**GET** `/orders`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Get Single Order
**GET** `/orders/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Create Order
**POST** `/orders`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "user_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 99.99
    }
  ],
  "total_price": 199.98,
  "discount": 10.00,
  "final_price": 189.98,
  "shipping_address": "123 Main St, City, Country",
  "status": "pending"
}
```

**Response:** `201 Created`

---

### Update Order
**PUT** `/orders/{id}`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "status": "shipped",
  "shipping_address": "456 New St, City, Country"
}
```

**Response:** `200 OK`

---

## Payment Endpoints

### Get All Payments
**GET** `/payments`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Get Single Payment
**GET** `/payments/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`

---

### Process Payment
**POST** `/payments/process`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "order_id": 1,
  "payment_method": "credit_card",
  "amount": 189.98,
  "status": "pending"
}
```

**Valid payment methods:** `credit_card`, `debit_card`, `paypal`, `stripe`, `cash`

**Valid statuses:** `pending`, `completed`, `failed`, `refunded`

**Response:** `201 Created`
```json
{
  "message": "Payment processed successfully",
  "payment": {
    "id": 1,
    "order_id": 1,
    "payment_method": "credit_card",
    "amount": "189.98",
    "status": "pending",
    "created_at": "2026-04-27T10:00:00.000000Z",
    "updated_at": "2026-04-27T10:00:00.000000Z"
  }
}
```

---

### Update Payment
**PUT** `/payments/{id}`

**Headers:** `Authorization: Bearer {token}`

**Body:**
```json
{
  "status": "completed"
}
```

**Response:** `200 OK`

---

### Delete Payment
**DELETE** `/payments/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "message": "Payment deleted successfully"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

---

## Order Status Values
- `pending` - Order placed but not processed
- `processing` - Order is being prepared
- `shipped` - Order has been shipped
- `delivered` - Order delivered to customer
- `cancelled` - Order cancelled

## Payment Status Values
- `pending` - Payment initiated but not completed
- `completed` - Payment successful
- `failed` - Payment failed
- `refunded` - Payment refunded to customer
