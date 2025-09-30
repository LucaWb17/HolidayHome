<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Dimenticata - Villa Paradiso</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'php/header.php'; ?>

    <main class="form-container">
        <h2>Password Dimenticata</h2>
        <p>Inserisci il tuo indirizzo email per ricevere un link per reimpostare la tua password.</p>
        <form id="forgot-password-form" action="php/request_password_reset.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn">Invia link di reset</button>
        </form>
        <div id="form-messages" class="form-messages"></div>
    </main>

    <?php include 'php/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>