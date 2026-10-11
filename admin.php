<?php
// start the session so we can read who is logged in
session_start();

// connect to the database (gives us the $pdo variable)
require_once __DIR__ . '/config/database.php';


/* ---------- ACCESS CONTROL (Admin only) ---------- */

// if nobody is logged in, send them to the login page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// get the role saved in the session at login
// the ?? '' means "use empty text if there is no role", so it won't crash
$role = $_SESSION['user']['role'] ?? '';

// only Admin may stay, everyone else goes back to the home page
if ($role != 'Admin') {
    header('Location: post.php');
    exit;
}


/* ----------  ROLE ASSIGNMENT FEATURE ---------- */

// this message is shown on the page after a role change
$message = '';

//  runs only when a Save button in the role table was pressed
if (isset($_POST['user_id']) && isset($_POST['role'])) {
    $target_id = (int) $_POST['user_id'];  // which user is being changed
    $new_role = $_POST['role'];            // the role picked in the dropdown

    // safety check: only these three roles are allowed
    if ($new_role != 'Admin' && $new_role != 'Staff' && $new_role != 'User') {
        $message = 'Invalid role.';
    }
    // stop the admin from changing their own role (so they can't lock themselves out)
    else if ($target_id == $_SESSION['user']['id']) {
        $message = "You can't change your own role.";
    }
    //  update the role in the database
    else {
        $update = $pdo->prepare("UPDATE users SET role = ? WHERE ID = ?");
        $update->execute([$new_role, $target_id]);
        $message = 'Role updated.';
    }
}


/* ----------  ANALYTICS DATA ---------- */

// the four numbers shown in the boxes at the top
// COUNT(*) counts rows, fetchColumn() gives back that single number
$totalUsers  = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPosts  = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalSaves  = $pdo->query("SELECT COUNT(*) FROM saved_post")->fetchColumn();
$totalHidden = $pdo->query("SELECT COUNT(*) FROM posts WHERE is_hidden = 1")->fetchColumn();

// how many users saved each post
// LEFT JOIN keeps posts that nobody saved 
// GROUP BY makes one row per post, COUNT counts the saves for it
$stmt = $pdo->query("
    SELECT posts.id, posts.title, categories.title AS category_name,
        COUNT(DISTINCT saved_post.user_id) AS saves
    FROM posts
    JOIN categories ON posts.category_id = categories.id
    LEFT JOIN saved_post ON saved_post.post_id = posts.id
    GROUP BY posts.id, posts.title, categories.title
    ORDER BY saves DESC, posts.id
");
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// find the biggest save count, so the bars can be drawn relative to it
// start at 1 so we never divide by zero when nobody saved anything
$maxSaves = 1;
foreach ($stats as $row) {
    if ($row['saves'] > $maxSaves) {
        $maxSaves = $row['saves'];
    }
}

// all users, for the role assignment table
$stmt = $pdo->query("SELECT ID, username, email, role FROM users ORDER BY ID");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body>

<!-- ----------  NAVBAR ---------- -->
<nav class="navbar navbar-expand" id="nav">
  <div class="container">
    <a href="post.php" class="navbar-brand text-white fs-3"><i class="bi bi-brush"></i></a>
    <a href="logout.php" class="nav-link text-white"><i class="bi bi-door-open fs-3"></i></a>
  </div>
</nav>

<div class="container my-4">
  <h2>Admin Dashboard</h2>

  <!-- ----------  SUMMARY BOXES ---------- -->
  <div class="row g-3 my-3">
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <small class="text-muted">Users</small>
        <h3><?php echo $totalUsers; ?></h3>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <small class="text-muted">Posts</small>
        <h3><?php echo $totalPosts; ?></h3>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <small class="text-muted">Total saves</small>
        <h3><?php echo $totalSaves; ?></h3>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card p-3">
        <small class="text-muted">Hidden posts</small>
        <h3><?php echo $totalHidden; ?></h3>
      </div>
    </div>
  </div>

  <!-- ----------  SAVES PER POST (analytics) ---------- -->
  <h4 class="mt-4">Saves per post</h4>
  <table class="table align-middle">
    <thead>
      <tr>
        <th>Post</th>
        <th>Category</th>
        <th style="width:40%">Users who saved it</th>
      </tr>
    </thead>
    <tbody>
    <?php
    // one table row for every post
    foreach ($stats as $row) {

        // bar width as a percentage: the most-saved post gets 100%
        $width = ($row['saves'] / $maxSaves) * 100;

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
        echo "<td>";
        echo "<div class='progress' style='height:22px;'>";
        echo "<div class='progress-bar' style='width:" . $width . "%'>" . $row['saves'] . "</div>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
  </table>

  <!-- ----------  ROLE ASSIGNMENT ---------- -->
  <h4 class="mt-5">Role assignment</h4>

  <?php
  // show the result message (like "Role updated.") only if there is one
  if ($message != '') {
      echo "<div class='alert alert-info'>" . htmlspecialchars($message) . "</div>";
  }
  ?>

  <table class="table align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
    <?php
    // one row for every user
    foreach ($users as $user) {

        // build the dropdown choices: Admin, Staff, User
        // the user's current role gets "selected" so it shows first
        $options = "";
        $all_roles = ['Admin', 'Staff', 'User'];

        foreach ($all_roles as $r) {
            if ($user['role'] == $r) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $options = $options . "<option value='" . $r . "' " . $selected . ">" . $r . "</option>";
        }

        echo "<tr>";
        echo "<td>" . $user['ID'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";

        // sends the user id and the chosen role to this same page
        // section 2 at the top then updates the database
        echo "<td>";
        echo "<form method='POST' class='d-flex gap-2'>";
        echo "<input type='hidden' name='user_id' value='" . $user['ID'] . "'>";
        echo "<select name='role' class='form-select form-select-sm w-auto'>" . $options . "</select>";
        echo "<button class='btn btn-sm btn-primary'>Save</button>";
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