<?php 
session_start();
require_once __DIR__ .'/config/database.php';

// redirect user if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// save buttons
if (isset($_POST['save'])) {
    $post_id = (int) $_POST['post_id'];
    $user_id = $_SESSION['user']['id'];

    // prevent duplicates in DB
    $check = $pdo->prepare("SELECT COUNT(*) FROM saved_post WHERE user_id = ? AND post_id = ?");
    $check->execute([$user_id, $post_id]); 

    if ($check->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO saved_post (user_id, post_id) VALUES (?, ?)");
        $insert->execute([$user_id, $post_id]);
    }

    echo 'saved (user ' . $user_id . ', post ' . $post_id . ')';
    exit; // stops here so the AJAX call doesn't get the whole page back
}

// display query (no echo here)
$display = $pdo->query("
    SELECT posts.id, posts.title, posts.image, categories.title AS category_name
    FROM posts
    JOIN categories ON posts.category_id = categories.id
");
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penterest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/post.css">
    <link rel="stylesheet" href="CSS/nofi.css">
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

<!-- notify msg-->

 <div id="notify">Post saved!</div>

 <?php
echo "<div class='posts-container'>";
while ($row = $display->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='card rounded border' id='card' style='width:18rem;'>";
    echo "<img src='{$row['image']}' alt='{$row['title']}'>";
    echo "<h3>{$row['title']}</h3>";
    echo "<p id='tag'>{$row['category_name']}</p>";
    echo "<button type='button' id='save-button' onclick='showNotification(event, {$row['id']})'>Save</button>";
    echo "</div>";
}
echo "</div>";
?>



  <!-- JS for notify-->
<script>
function showNotification(event, postId) {
  event.preventDefault(); // stop the page from refreshing

  // show the notification
  let box = document.getElementById("notify");
  box.classList.add("show");
  setTimeout(() => {
    box.classList.remove("show");
  }, 3000);

  // send the postId to PHP via AJAX
 fetch('post.php', {
  method: 'POST',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: 'save=1&post_id=' + postId
})
.then(response => response.text())


}
</script>
<!-- ------------------------------------------- -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>






