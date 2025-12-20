CREATE DATABASE order_service;

USE order_service;

CREATE TABLE users (
    id VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    role ENUM('customer', 'seller', 'admin'),
    balance DECIMAL(10, 2) DEFAULT 0.00,
    purchase_history JSON
);

CREATE TABLE products (
    id VARCHAR(255) PRIMARY KEY,
    seller_id VARCHAR(255),
    title VARCHAR(255),
    price DECIMAL(10, 2),
    images JSON,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE cart_items (
    user_id VARCHAR(255),
    product_id VARCHAR(255),
    quantity INT DEFAULT 1,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
    id VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    order_id VARCHAR(255),
    product_id VARCHAR(255),
    quantity INT,
    price_locked DECIMAL(10, 2),
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);