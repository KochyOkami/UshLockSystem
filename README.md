project-root/
│
├── assets/
│   ├── css/
│   │   ├── style.css        # Your CSS styles
│   │
│   ├── js/
│       ├── main.js          # JavaScript (if needed)
│
├── includes/
│   ├── db_connection.php    # Database connection script
│   ├── functions.php        # PHP functions and utilities
│
├── templates/
│   ├── header.php           # Common header template
│   ├── footer.php           # Common footer template
│
├── index.php                # Homepage
├── login.php                # User login page
├── register.php             # User registration page
├── dashboard.php            # User dashboard page
├── profile.php              # User profile page
├── devices.php              # Device management page
├── control.php              # Remote control page (if applicable)
│
├── .htaccess                # Apache configuration (for URL rewriting)
├── config.php               # Configuration file (e.g., database credentials)
│
├── README.md                # Project documentation

-- User table --
CREATE TABLE Users (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(255),
  password VARCHAR(255),
  PRIMARY KEY(id),
  UNIQUE (username)
);

CREATE TABLE Images (
  id INT NOT NULL AUTO_INCREMENT,
  path VARCHAR(255),
  type VARCHAR(255),
  user_id VARCHAR(255),
);

 id | wearer | keyholder | timer_start         | timer_end 

INSERT INTO Users (username, password) VALUES ("Maitre", "a");

INSERT INTO Locks (wearer, keyholder, timer_start, timer_end) VALUES (1,2,NOW(), NOW());

errors:

98 -> uknow user
99 -> wrong password
100 ->
101 -> db errro
102 -> registring password != password2