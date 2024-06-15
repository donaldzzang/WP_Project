<?php
$db = new mysqli('localhost', 'root', '', 'book_platform');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_GET['term'])) {
    $term = $db->real_escape_string($_GET['term']);
    $query = "SELECT title FROM books WHERE title LIKE '%$term%' LIMIT 5";
    $result = $db->query($query);

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['title'];
    }

    echo json_encode($suggestions);
}
?>
