<?php 


session_start();
// redirect user if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['saved_post'])) {
  $_SESSION['saved_post']=[];
}

if (  isset($_POST['save'])) {
  $post_id = (int) $_POST['post_id'];


   // prevent duplicates
    if (!in_array($post_id, $_SESSION['saved_post'])) {
        $_SESSION['saved_post'][] = $post_id;
    }

// test
$stmt = $pdo->query("SELECT * FROM posts");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='card'>";
    echo "<img src='{$row['image']}' alt=''>";
    echo "<h5>{$row['title']}</h5>";
    echo "</div>";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/post.css">
</head>
<body>
    <!-- navbar -->
    <nav class="navbar navbar-expand bg-dark">
  <div class="container">
    <a href="#" class="navbar-brand text-white fs-3"><i class="bi bi-brush" title="Home"></i></a>
    <ul class="navbar-nav">
      
      <li class="nav-item">
        <a href="profile.php" class="nav-link text-white"><i class="bi bi-person-circle fs-3 " title="Profile"></i></a>
      </li>
    </ul>
  </div>
</nav>

<!-- posts -->









    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
