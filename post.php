<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
// initialize array
if (!isset($_SESSION['pins'])) {
    $_SESSION['pins'] = [];
}
if (!isset($_SESSION['saved_pins'])) {
    $_SESSION['saved_pins'] = [];
}
// when the save button is pressed
if (isset($_POST['save'])) {
    $pin_id = (int) $_POST['pin_id'];

    // Save pin ID into saved_pins
    if (!in_array($pin_id, $_SESSION['saved_pins'])) {
        $_SESSION['saved_pins'][] = $pin_id;
    }

    // Redirect back to post.php so user stays on same page
    header('Location: post.php');
    exit;
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


<div class="card" style="width: 18rem;">
  <img src="https://i.pinimg.com/736x/d9/c5/13/d9c513083766ef64bfd5fe0d710af28a.jpg" class="card-img-top" alt="post">
  <div class="card-body">
    <h5 class="card-title">Muscle Sketch</h5>
    <p class="card-text">Hope yall like it</p>
    <p class="card-text"></p>
    <form method="POST" action="post.php">
    <input type="hidden" name="pin_id" value="123"> <!-- ID of the pin -->
    <input type="hidden" name="title" value="My Pin Title"> <!--Title-->
        <input type="hidden" name="image" value="example.jpg"> <!--Image-->
        <input type="hidden" name="link" value="https://example.com"> <!--Link-->
    <button type="submit" name="save" class="btn btn-primary">Save</button>
</form>
  </div>
</div>







    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
