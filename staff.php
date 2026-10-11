<?php

session_start();

// connect to the database 
require_once __DIR__ . '/config/database.php';


/* ----------  ACCESS CONTROL (role-based access) ---------- */

// if nobody is logged in, send them to the login page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit; 
}

// get the role that was saved in the session when the user logged in
$role = $_SESSION['user']['role'];

// only Staff and Admin may stay on this page
//  normal User gets sent back to the home page
if ($role != 'Staff' && $role != 'Admin') {
    header('Location: post.php');
    exit;
}


/* ----------  HIDE / UNHIDE FEATURE ---------- */


// (the form below sends post_id)
if (isset($_POST['post_id'])) {
    $post_id = (int) $_POST['post_id']; // which post was clicked ((int) makes sure it's a number)
    $hide = (int) $_POST['hide'];       // 1 = hide the post, 0 = show it again

    // update the is_hidden column for  post
    // the ? marks are filled in safely by execute() (prevents SQL injection)
    $update = $pdo->prepare("UPDATE posts SET is_hidden = ? WHERE id = ?");
    $update->execute([$hide, $post_id]);

    // reload the page so the table shows the new status
    // this also stops the form from re-sending if the user refreshes
    header('Location: staff.php');
    exit;
}


/* ----------  GET THE DATA FOR THE TABLE ---------- */

// get ALL posts, including hidden ones, so staff can unhide them
// JOIN links each post to its category name through category_id
$stmt = $pdo->query("
    SELECT posts.id, posts.title, posts.image, posts.is_hidden, categories.title AS category_name
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    ORDER BY posts.id
");

// put every row into an array so we can loop through it later
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/staff.css">
</head>
<body>

<!-- ----------  NAVBAR ---------- -->
<nav class="navbar navbar-expand" id="nav">
  <div class="container">
    <a href="post.php" class="navbar-brand text-white fs-3"><i class="bi bi-brush"></i></a>
    <!--  logout -->
    <a href="logout.php" class="nav-link text-white"><i class="bi bi-door-open fs-3"></i></a>
  </div>
</nav>

<!-- container -->
<div class="container my-4">
  <h2>Moderate Posts</h2>
  <p class="text-muted">Hide inappropriate posts</p>

  <!-- ----------  THE TABLE ---------- -->
  <table class="table align-middle">

    <!--  the header row with the column titles -->
    <thead>
      <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>

    <!--  the data rows, one per post, printed by PHP below -->
    <tbody>

    <?php
    // loop through every post, one table row each time
    foreach ($posts as $post) {

        // decide what to show depending on whether the post is hidden
        if ($post['is_hidden'] == 1) {
            // post is hidden
            $status = "<span class='badge text-bg-danger'>Hidden</span>";
            $button_text = "Unhide";
            $button_color = "btn-outline-success";
            $new_value = 0; // click sets is_hidden back to 0
        } else {
            // post is visible
            $status = "<span class='badge text-bg-success'>Visible</span>";
            $button_text = "Hide";
            $button_color = "btn-outline-danger";
            $new_value = 1; // clicking sets is_hidden to 1
        }

        // print one row
        echo "<tr>";

       
        // htmlspecialchars() stops odd text from breaking the page (security)
        echo "<td><img src='" . htmlspecialchars($post['image']) . "' style='width:60px;height:60px;object-fit:cover;'></td>";

        echo "<td>" . htmlspecialchars($post['title']) . "</td>";          // post title
        echo "<td>" . htmlspecialchars($post['category_name']) . "</td>";  // category name
        echo "<td>" . $status . "</td>";                                   // hidden or visible tag

        // the button is a small form that sends the post id and the new value
        // to this same page, where section 2 above handles it
        echo "<td>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='post_id' value='" . $post['id'] . "'>";   // which post
        echo "<input type='hidden' name='hide' value='" . $new_value . "'>";       // 1 = hide, 0 = unhide
        echo "<button class='btn btn-sm " . $button_color . "'>" . $button_text . "</button>";
        echo "</form>";
        echo "</td>";

        echo "</tr>";
    }
    ?>

    </tbody>
  </table>
</div>

</body>
</html>