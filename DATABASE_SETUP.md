## Database Setup Instructions

You need to create the `users` table in your MySQL database. Follow these steps:

### Option 1: Using phpMyAdmin (Easiest)

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click on your database `practicework` in the left panel
3. Click the **SQL** tab at the top
4. Copy and paste the entire SQL code below:

```sql
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verify_token VARCHAR(255),
    is_verified INT DEFAULT 0,
    reset_token VARCHAR(255),
    reset_token_expire DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verify_token (verify_token),
    INDEX idx_reset_token (reset_token)
);
```

5. Click the **Go** button to execute
6. You should see the message: "1 table(s) have been created successfully."

### Option 2: Using MySQL Command Line

1. Open Command Prompt/Terminal
2. Navigate to your MySQL bin directory:
   ```
   cd C:\xamppp\mysql\bin
   ```

3. Connect to MySQL:
   ```
   mysql -u root -p
   ```
   (Press Enter when prompted for password if you have no password)

4. Select your database:
   ```
   USE practicework;
   ```

5. Copy and paste the SQL code from Option 1 above

6. Press Enter to execute

### Option 3: Import SQL File

1. In phpMyAdmin, click **Import** tab
2. Click **Choose File** and select `database_setup.sql`
3. Click **Go** button

### Verify the Table Was Created

In phpMyAdmin:
1. Click on your `practicework` database
2. You should see the `users` table listed on the left
3. Click on the `users` table to see its structure

### Table Structure

The `users` table has these columns:

| Column | Type | Purpose |
|--------|------|---------|
| id | INT | Unique user ID (auto-increment) |
| name | VARCHAR(255) | User's full name |
| phone | VARCHAR(20) | User's phone number |
| email | VARCHAR(255) UNIQUE | Email address (must be unique) |
| password | VARCHAR(255) | Hashed password |
| verify_token | VARCHAR(255) | Email verification token |
| is_verified | INT | Verification status (0=not verified, 1=verified) |
| reset_token | VARCHAR(255) | Password reset token |
| reset_token_expire | DATETIME | When reset token expires |
| created_at | DATETIME | Account creation timestamp |
| updated_at | DATETIME | Last update timestamp |

### Next Steps

Once the table is created:

1. Update Gmail credentials in `code.php`:
   - Line 11: Replace `yourgmail@gmail.com` with your Gmail
   - Line 12: Replace `your_app_password` with your 16-character Gmail app password

2. Go to http://localhost/practicework1/register.php

3. Test the registration system

### Troubleshooting

**Error: "Table already exists"**
- The table was already created successfully
- You can proceed with registration

**Error: "Syntax error"**
- Copy the SQL code exactly as shown above
- Make sure you're in the correct database (practicework)
- Check for any typos

**Error: "Access denied"**
- Check your MySQL username and password in `dbcon.php`
- Make sure the user has permission to create tables

### Reset Database (If Needed)

To delete and recreate the table:

```sql
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verify_token VARCHAR(255),
    is_verified INT DEFAULT 0,
    reset_token VARCHAR(255),
    reset_token_expire DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verify_token (verify_token),
    INDEX idx_reset_token (reset_token)
);
```

**Warning**: This will delete all user data if the table already exists!

---

Once the table is created, your authentication system will be ready to use!
