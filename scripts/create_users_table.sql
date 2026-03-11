-- Create users table with email verification and password reset functionality
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verify_token VARCHAR(255),
    is_email_verified INT DEFAULT 0,
    email_verified_at TIMESTAMP NULL,
    reset_token VARCHAR(255),
    reset_token_expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create index on email for faster lookups
CREATE INDEX idx_email ON users(email);

-- Create index on verify_token for email verification
CREATE INDEX idx_verify_token ON users(verify_token);

-- Create index on reset_token for password reset
CREATE INDEX idx_reset_token ON users(reset_token);
