<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['save'])) {
    $pin_id = (int) $_POST['pin_id'];

    if (!isset($_SESSION['saved_pins'])) {
        $_SESSION['saved_pins'] = [];
    }

    if (!in_array($pin_id, $_SESSION['saved_pins'])) {
        $_SESSION['saved_pins'][] = $pin_id;
    }

    $message = "Pin $pin_id saved to your profile!";
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php if (!empty($message)) echo "<p>$message</p>"; ?>

    <!-- Show saved pins -->
    <?php
    if (!empty($_SESSION['saved_pins'])) {
        foreach ($_SESSION['saved_pins'] as $pin) {
            echo "<div class='card'><h5>Pin $pin</h5></div>";
        }
    }
    ?>
</body>
</html>
