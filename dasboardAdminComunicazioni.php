<?php
include 'php/config.php';
// Note: admin_nav.php handles the session check and authorization.

$receiver_id = isset($_GET['user_id']) ? filter_var($_GET['user_id'], FILTER_VALIDATE_INT) : null;
if (!$receiver_id) {
    // Redirect or show an error if no user is specified
    header('Location: dasboardAdmin.php');
    exit;
}

// Fetch the receiver's details
$user_stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $receiver_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$receiver = $user_result->fetch_assoc();
$user_stmt->close();

if (!$receiver) {
    // Redirect or show an error if user not found
    header('Location: dasboardAdmin.php');
    exit;
}

// Fetch message history
$admin_id = $_SESSION['id'];
$messages_stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
$messages_stmt->bind_param("iiii", $admin_id, $receiver_id, $receiver_id, $admin_id);
$messages_stmt->execute();
$messages_result = $messages_stmt->get_result();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <title>Admin Dashboard - Invia Messaggio</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="css/style.css" rel="stylesheet"/>
    <style type="text/tailwindcss">
        :root {
            --c-gold: #c5a87b;
            --c-gold-bright: #e6c589;
            --c-night-blue: #0c142c;
        }
        body { font-family: 'Poppins', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-serif { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="bg-[#111722]">
<div class="relative flex size-full min-h-screen flex-col overflow-x-hidden">
    <div class="flex h-full grow">
        <?php include 'php/admin_nav.php'; ?>
        <main class="flex-1 bg-[#111722] p-8">
            <h2 class="text-3xl font-bold text-white mb-2 font-serif">Comunicazioni</h2>
            <p class="text-gray-400 mb-8">Invia un messaggio a <?php echo htmlspecialchars($receiver['name']); ?> (<?php echo htmlspecialchars($receiver['email']); ?>)</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                 <!-- Message History -->
                <div class="lg:col-span-2 bg-[#192233] p-6 rounded-lg border border-[#324467] max-h-[600px] overflow-y-auto">
                    <h3 class="text-xl font-bold text-white mb-4 font-serif">Storico Messaggi</h3>
                    <div class="space-y-4">
                        <?php if ($messages_result->num_rows > 0): ?>
                            <?php while($msg = $messages_result->fetch_assoc()): ?>
                                <div class="p-4 rounded-lg <?php echo ($msg['sender_id'] === $admin_id) ? 'bg-[#111722]' : 'bg-black/20'; ?>">
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="font-bold text-white"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                        <span class="text-xs text-gray-400"><?php echo date("d/m/Y H:i", strtotime($msg['timestamp'])); ?></span>
                                    </div>
                                    <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($msg['body'])); ?></p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-gray-400">Nessun messaggio trovato con questo utente.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Send Message Form -->
                <div class="lg:col-span-1 bg-[#192233] p-6 rounded-lg border border-[#324467] h-fit">
                    <h3 class="text-xl font-bold text-white mb-4 font-serif">Invia Nuovo Messaggio</h3>
                    <div id="message-response" class="text-center mb-4 text-white"></div>
                    <form id="send-message-form" class="space-y-4">
                        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-300">Oggetto</label>
                            <input type="text" name="subject" id="subject" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-300">Messaggio</label>
                            <textarea name="body" id="body" rows="6" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                                Invia Messaggio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('send-message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('message-response');

    fetch('php/send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            form.reset();
            setTimeout(() => location.reload(), 1500); // Reload to see the new message
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
$messages_stmt->close();
$conn->close();
?>
