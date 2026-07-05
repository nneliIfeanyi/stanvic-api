CREATE DATABASE IF NOT EXISTS expense_tracker;
USE expense_tracker;

CREATE TABLE IF NOT EXISTS transactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type ENUM('income', 'expense') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  description VARCHAR(255) NOT NULL,
  category VARCHAR(100) NOT NULL DEFAULT 'Others',
  transaction_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO categories (name) VALUES
('Salary'),
('Business'),
('Food'),
('Transport'),
('Shopping'),
('Bills'),
('Health'),
('Entertainment'),
('Investment'),
('Education'),
('Gift'),
('Others');

INSERT INTO transactions (type, amount, description, category, transaction_date, created_at) VALUES
('income', 5000.00, 'Salary deposit', 'Salary', '2026-06-01', '2026-06-01 09:00:00'),
('expense', 125.50, 'Groceries', 'Food', '2026-06-02', '2026-06-02 19:00:00'),
('income', 800.00, 'Freelance project', 'Business', '2026-06-03', '2026-06-03 10:30:00');
