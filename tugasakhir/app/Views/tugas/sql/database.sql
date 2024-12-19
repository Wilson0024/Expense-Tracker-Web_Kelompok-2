CREATE DATABASE PemWebSem3;

USE PemWebSem3;

CREATE TABLE users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token_hash varchar(64) DEFAULT NULL,
    reset_token_expires_at datetime DEFAULT NULL
);

CREATE TABLE income (
    income_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL, 
    income_name VARCHAR(50) NOT NULL, -- e.g., JOB, INVESTMENT, FREELANCE
    amount INT(11) NOT NULL, 
    income_date DATE NOT NULL, 
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE expense (
    expense_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL, 
    expense_name VARCHAR(50) NOT NULL, -- e.g., FOOD & DRINKS, SHOPPING, TRANSPORTATION
    amount INT(11) NOT NULL, 
    expense_date DATE NOT NULL, 
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Create index on user_id in the income table
CREATE INDEX idx_user_id_income ON income(user_id);

-- Create index on user_id in the expense table
CREATE INDEX idx_user_id_expense ON expense(user_id);
