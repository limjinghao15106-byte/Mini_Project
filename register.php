<?php 
session_start();
require_once __DIR__ . '\config\database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // to check if email already exists //
    $statement = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $statement->execute([$email]);
    $existeduser = $statement->fetch(PDO::FETCH_ASSOC);

    if ($existeduser) {
        // email alrd inside the db//
        $error = 'That email is already registered.';
    } else {
        // hashedpass//
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // insert new user//
        $statement = $pdo->prepare("INSERT INTO users (username, email, hashedPassword) VALUES (:username, :email, :password)");
        $statement->execute([
            ':username' => $name,
            ':email'=>$email,
            ':password'=> $hashedPassword   
        ]);

        // save user in session//
        $_SESSION['user'] = [
            'id'    => $pdo->lastInsertId(),
            'name'  => $name,
            'email' => $email
        ];

        header('Location: post.php');
        exit();
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
        <link rel="stylesheet" href="CSS/register.css">
    </head>
    <body>

    <div class="card p-4"  >
        <i class="bi bi-brush fs-1"></i>

        <div class="card-body p-4">
            
            <h3 class="title fw-bold">Create account</h3>
            
            <p>Sign up to view content</p>
            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="register.php">
        <div class="mb-3">
        <label for="name" class="form-label ">Name:</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Full name" required>
        </div>

        <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" required>
        </div>

        <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn  p-2 mb-3 w-100" id="create">Create Account</button>
    </form>


                <p class="auth-switch">
                    Already have an account? <a href="login.php">Log in</a>
                </p>
    </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
    </html>