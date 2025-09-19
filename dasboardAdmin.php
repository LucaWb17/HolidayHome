<?php
include 'php/config.php';

// Authentication and Authorization
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all users from the database
$result = $conn->query("SELECT id, name, email, role FROM users");

include 'php/header.php';
?>

<main class="flex-1 px-10 py-12 md:px-20 lg:px-40 bg-gray-900 text-white">
    <div class="mx-auto max-w-7xl pt-20">
        <h2 class="text-5xl font-bold mb-8 font-serif">Pannello di Amministrazione</h2>

        <!-- User Management -->
        <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
            <h3 class="text-3xl font-bold mb-6 text-[var(--c-gold)] font-serif">Gestione Utenti</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="text-xs text-white uppercase bg-white/10">
                        <tr>
                            <th class="px-6 py-3">ID Utente</th>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Ruolo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="border-b border-white/10 hover:bg-white/5">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $row['role'] === 'admin' ? 'bg-yellow-900/50 text-[var(--c-gold)]' : 'bg-blue-900/50 text-blue-300'; ?>">
                                            <?php echo htmlspecialchars($row['role']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">Nessun utente trovato.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="mt-16 bg-black/20 p-8 rounded-xl backdrop-blur-sm">
            <h3 class="text-3xl font-bold mb-6 text-[var(--c-gold)] font-serif">Cambia la Tua Password</h3>
            <div id="change-password-message" class="text-center mb-4 text-white"></div>
            <form id="change-password-form" class="space-y-6 max-w-lg mx-auto">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-white">Password Attuale</label>
                    <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-white">Nuova Password</label>
                    <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-white">Conferma Nuova Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                        Cambia Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

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

<?php
$conn->close();
include 'php/footer.php';
?>
