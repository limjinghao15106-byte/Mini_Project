<?php 

session_start();
// connect to database
require_once __DIR__ .'/config/database.php';

$error = '';
// check if form was submitted
if ($_SERVER['REQUEST_METHOD'] ==='POST')   {
    // get from data
    $name = $_POST['name'];
    $password = $_POST['password'];

    $ORDER = $pdo->prepare('SELECT * FROM users WHERE username=:name');
    $ORDER->execute(array(':name'=> $name));
    $user = $ORDER->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password ,$user['hashedPassword'])){

    $_SESSION['user'] =['id'=> $user['id'],'name'=> $user['name'], 'email'=>$user['email']];
    header('Location:post.php');
    exit;
    
 
    }

else{

$error = 'Invalid email or password';




}


};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/login.css">
   
</head>
<body>

<div class="card p-4"  >
    <i class="bi bi-brush fs-1"></i>

    <div class="card-body p-4">
        
        <h3 class="title fw-bold d-flex justify-content-center">Log in</h3>
        
        <p class="d-flex justify-content-center">Log in to view content</p>
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
    <div class="mb-3">
    <label for="name" class="form-label ">Name:</label>
    <input type="text" id="name" name="name" class="form-control" placeholder="Full name" required>
    </div>

    

    <div class="mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>

    <button type="submit" class="btn  p-2 mb-3 w-100" id="login">Log in</button>
</form>         


            <p class="auth-switch">
                Don't have an account? <a href="register.php">Register</a>
            </p>
</div>
</div>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>