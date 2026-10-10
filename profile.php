<?php
session_start();
require_once __DIR__ . '/config/database.php'; 

// redirect user if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}



$user_id = $_SESSION['user']['id']; // logged-in user ID

// Get saved posts from DB
$stmt = $pdo->prepare("
    SELECT posts.id, posts.title, posts.image, categories.title AS category_name
    FROM saved_post
    JOIN posts ON saved_post.post_id = posts.id
    JOIN categories ON posts.category_id = categories.id
    WHERE saved_post.user_id = ?
");
$stmt->execute([$user_id]);
$savedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penterest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="CSS/profile.css">
  
</head>
<body>
    <!-- navbar -->
     <nav class="navbar navbar-expand" id="nav">
  <div class="container">
    <a href="#" class="navbar-brand text-white fs-3"><i class="bi bi-brush" title="Home"></i></a>
    <ul class="navbar-nav">
    <!-- log out button -->
      <li>
        <a href="logout.php" class="nav-link text-white"><i class="bi bi-door-open fs-3 " title="Logout"></i></a>
      </li>
      <!-- profile button -->
      <li class="nav-item">
        <a href="profile.php" class="nav-link text-white"><i class="bi bi-person-circle fs-3 " title="Profile"></i></a>
      </li>
    </ul>
  </div>
</nav>

<h2>Your Saved Posts</h2>

<div class="posts-container">
<?php
if (!empty($savedPosts)) {
    foreach ($savedPosts as $post) {
        echo "<div class='card rounded border' id='card' style='width:18rem;'>";
        echo "<img src='" . htmlspecialchars($post['image']) . "' alt='" . htmlspecialchars($post['title']) . "'>";
        echo "<h3 class=' fw-bolder'>" . htmlspecialchars($post['title']) . "</h3>";
        echo "<p class='tag'>" . htmlspecialchars($post['category_name']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No posts saved yet.</p>";
}
?>
</div>









<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
