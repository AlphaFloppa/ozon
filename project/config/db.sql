CREATE DATABASE order_service;

USE order_service;

CREATE TABLE user (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    role ENUM('customer', 'seller', 'admin'),
    balance DECIMAL(10, 2) DEFAULT 0.00
);

CREATE TABLE product (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id INT UNSIGNED,
    title VARCHAR(50),
    price DECIMAL(10, 2),
    description VARCHAR(200),
    images JSON,
    deleted_at TIMESTAMP DEFAULT NULL,
    FOREIGN KEY (seller_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE cart_item (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    product_id INT UNSIGNED,
    quantity INT UNSIGNED DEFAULT 1,
    CONSTRAINT `cart-item bond unique` UNIQUE (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

CREATE TABLE `order` (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE order_item (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED,
    product_id INT UNSIGNED,
    quantity INT UNSIGNED DEFAULT 1,
    price_locked DECIMAL(10, 2),
    CONSTRAINT `order-item bond unique` UNIQUE (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

CREATE TABLE user_order (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNSIGNED,
    order_id INT UNSIGNED,
    CONSTRAINT `customer-order bond unique` UNIQUE (customer_id, order_id),
    FOREIGN KEY (customer_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE
);