<?php
$db = new mysqli('localhost', 'root', '', '');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$query = "CREATE DATABASE IF NOT EXISTS book_platform";
if ($db->query($query) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $db->error . "<br>";
}

$db->select_db('book_platform');

$query = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)";
if ($db->query($query) === TRUE) {
    echo "Table 'users' created successfully<br>";
} else {
    echo "Error creating table 'users': " . $db->error . "<br>";
}

$query = "CREATE TABLE IF NOT EXISTS profiles (
    user_id INT UNSIGNED NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    bio TEXT,
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($db->query($query) === TRUE) {
    echo "Table 'profiles' created successfully<br>";
} else {
    echo "Error creating table 'profiles': " . $db->error . "<br>";
}

$query = "CREATE TABLE IF NOT EXISTS books (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    likes INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($db->query($query) === TRUE) {
    echo "Table 'books' created successfully<br>";
} else {
    echo "Error creating table 'books': " . $db->error . "<br>";
}

$query = "CREATE TABLE IF NOT EXISTS comments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    book_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($db->query($query) === TRUE) {
    echo "Table 'comments' created successfully<br>";
} else {
    echo "Error creating table 'comments': " . $db->error . "<br>";
}

if ($db->query($query) === TRUE) {
    echo "Table 'likes' created successfully<br>";
} else {
    echo "Error creating table 'likes': " . $db->error . "<br>";
}

$db->close();
?>
