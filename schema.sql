CREATE DATABASE IF NOT EXISTS employee_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE employee_portal;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password_hash VARCHAR(255),
  role ENUM('admin','employee') DEFAULT 'employee'
);
INSERT INTO users (username, password_hash, role)
VALUES ('admin', '$2y$10$8o0u7rC6t5MZ3tN8zQm9b.tQ4vQz4v8kLxkQmXKq2qU9b9e1I3bQO', 'admin');
CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100),
  personnel_code VARCHAR(50),
  national_id VARCHAR(50),
  join_date DATE,
  position VARCHAR(50),
  department VARCHAR(50)
);
CREATE TABLE ticker (
  id INT PRIMARY KEY,
  daily_resources BIGINT,
  daily_resources_change DECIMAL(5,2),
  resource_target BIGINT,
  profit_loss BIGINT,
  profit_loss_change DECIMAL(5,2),
  expenses BIGINT,
  expenses_change DECIMAL(5,2)
);
INSERT INTO ticker (id, daily_resources, daily_resources_change, resource_target, profit_loss, profit_loss_change, expenses, expenses_change)
VALUES (1, 2450000000, 12.5, 3000000000, 185000000, 8.2, 1265000000, -3.1)
ON DUPLICATE KEY UPDATE id=id;