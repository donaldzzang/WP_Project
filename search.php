<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$db = new mysqli('localhost', 'root', '', 'book_platform');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_term = $db->real_escape_string($_POST['search']);
    $query = "SELECT * FROM books WHERE title LIKE '%$search_term%' OR author LIKE '%$search_term%'";
    $result = $db->query($query);

    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
}

include 'header.php';
?>

<div class="container">
    <h1>Search Books</h1>
    <form method="post" action="search.php">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" required>
        <ul id="suggestions" class="suggestions-list"></ul>
        <input type="submit" value="Search">
    </form>

    <?php if (!empty($search_results)) : ?>
        <h2>Search Results:</h2>
        <div class="book-list">
            <?php foreach ($search_results as $book) : ?>
                <div class="book-item">
                    <h2><?= htmlspecialchars($book['title']) ?></h2>
                    <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                    <a href="book.php?id=<?= $book['id'] ?>" class="button">Read More</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('search').addEventListener('input', function() {
    var searchTerm = this.value;
    if (searchTerm.length > 1) {
        fetch('search_suggestions.php?term=' + searchTerm)
            .then(response => response.json())
            .then(data => {
                var suggestionsList = document.getElementById('suggestions');
                suggestionsList.innerHTML = '';
                data.forEach(function(suggestion) {
                    var li = document.createElement('li');
                    li.textContent = suggestion;
                    li.addEventListener('click', function() {
                        document.getElementById('search').value = suggestion;
                        suggestionsList.innerHTML = '';
                    });
                    suggestionsList.appendChild(li);
                });
            });
    } else {
        document.getElementById('suggestions').innerHTML = '';
    }
});
</script>

<?php include 'footer.php'; ?>
