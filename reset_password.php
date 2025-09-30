<?php
// We need to start the session to get the CSRF token, but we do it after checking the token
// to avoid creating unnecessary sessions.
$token = isset($_GET['token']) ? $_GET['token'] : '';
if (empty($token)) {
    die("Token non valido o mancante.");
}
require_once 'php/config.php'; // This will start the session and generate a CSRF token
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimposta Password - Villa Paradiso</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'php/header.php'; ?>

    <main class="form-container">
        <h2>Reimposta la tua Password</h2>
        <form id="reset-password-form" action="php/update_password.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label for="new_password">Nuova Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Conferma Nuova Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Reimposta Password</button>
        </form>
        <div id="form-messages" class="form-messages"></div>
    </main>

    <?php include 'php/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>