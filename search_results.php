<?php
$db = mysqli_connect('localhost', 'root', '', 'book_platform') or die('Unable to connect. Check your connection parameters.');

$q = $_GET['q'];

$query = "SELECT * FROM books WHERE title LIKE '%$q%' OR author LIKE '%$q%'";
$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='book'>";
        echo "<h3><a href='book.php?id=" . $row["id"] . "'>" . $row["title"] . "</a></h3>";
        echo "<p>Author: " . $row["author"] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found";
}

mysqli_close($db);
?>
