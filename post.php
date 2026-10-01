<?php 
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}


if (!isset($_SESSION['Pin'])) {
    $_SESSION['Pin'] = [];


}

if ($action === 'save') {
    $id = (int) $_POST['id'];
    $title = htmlspecialchars($_POST['title']);
    $image = htmlspecialchars($_POST['image']);
    $link = htmlspecialchars($_POST['link']);

    if (!isset($_SESSION['pins'][$id])) {
        $_SESSION['pins'][ $id ] = [
        'title'=> $title,
        'image'=> $image,
        'link'=> $link
        ];
}

    header('Location:pins.php');
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






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
