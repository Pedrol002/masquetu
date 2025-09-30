CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON user_tn.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;
