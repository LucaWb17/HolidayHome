<?php
include 'php/config.php';
// Note: admin_nav.php handles the session check and authorization.

// Fetch all users from the database along with their unread message count for the admin
$admin_id = $_SESSION['id'];
$stmt = $conn->prepare("
    SELECT u.id, u.name, u.email, u.role,
           (SELECT COUNT(*) FROM messages m WHERE m.sender_id = u.id AND m.receiver_id = ? AND m.is_read = 0) as unread_count
    FROM users u
");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <title>Admin Dashboard - Villa Paradiso</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="css/style.css" rel="stylesheet"/>
    <style type="text/tailwindcss">
        :root {
            --c-gold: #c5a87b;
            --c-gold-bright: #e6c589;
            --c-night-blue: #0c142c;
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }
    </style>
</head>
<body class="bg-[#111722]">
<div class="relative flex size-full min-h-screen flex-col overflow-x-hidden">
    <div class="flex h-full grow">
        <?php include 'php/admin_nav.php'; ?>
        <main class="flex-1 bg-[#111722] p-8">
            <h2 class="text-3xl font-bold text-white mb-2 font-serif">Gestione Utenti</h2>
            <p class="text-gray-400 mb-8">Visualizza e gestisci gli utenti registrati.</p>

            <div class="bg-[#192233] rounded-lg border border-[#324467] overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="text-xs text-white uppercase bg-[#232f48]">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Ruolo</th>
                            <th class="px-6 py-3">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="border-b border-[#324467] hover:bg-[#232f48]">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $row['role'] === 'admin' ? 'bg-yellow-900/50 text-[var(--c-gold)]' : 'bg-blue-900/50 text-blue-300'; ?>">
                                            <?php echo htmlspecialchars($row['role']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="dasboardAdminComunicazioni.php?user_id=<?php echo $row['id']; ?>" class="text-[var(--c-gold)] hover:underline">Invia Messaggio</a>
                                            <?php if ($row['unread_count'] > 0): ?>
                                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"><?php echo $row['unread_count']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Nessun utente trovato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
             <!-- Change Password Section -->
            <div class="mt-10 bg-[#192233] p-8 rounded-lg border border-[#324467]">
                <h3 class="text-2xl font-bold mb-6 text-white font-serif">Cambia la Tua Password</h3>
                <div id="change-password-message" class="text-center mb-4 text-white"></div>
                <form id="change-password-form" class="space-y-6 max-w-lg mx-auto">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-white">Password Attuale</label>
                        <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-white">Nuova Password</label>
                        <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-white">Conferma Nuova Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                            Aggiorna Password
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('change-password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('change-password-message');

    fetch('php/change_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            form.reset();
        } else {
            messageDiv.className = 'text-center mb-4 text-red-400';
        }
    })
    .catch(error => {
        messageDiv.className = 'text-center mb-4 text-red-400';
        messageDiv.textContent = 'An error occurred. Please try again.';
        console.error('Error:', error);
    });
});
</script>
</body>
</html>
<?php
$conn->close();
?>
