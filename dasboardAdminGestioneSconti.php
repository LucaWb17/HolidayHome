<?php
include 'php/config.php';
include 'php/admin_security.php';

// Fetch all users to populate the dropdown
$users_result = $conn->query("SELECT id, name, email FROM users WHERE role = 'user'");

// Fetch all discounts to display in the table
$discounts_result = $conn->query("SELECT d.*, u.name as user_name FROM discounts d JOIN users u ON d.user_id = u.id ORDER BY d.id DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <title>Admin Dashboard - Gestione Sconti</title>
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
            <h2 class="text-3xl font-bold text-white mb-2 font-serif">Gestione Sconti</h2>
            <p class="text-gray-400 mb-8">Crea e assegna sconti ai clienti registrati.</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Create Discount Form -->
                <div class="lg:col-span-1 bg-[#192233] p-6 rounded-lg border border-[#324467]">
                    <h3 class="text-xl font-bold text-white mb-4 font-serif">Crea Nuovo Sconto</h3>
                    <div id="discount-message" class="text-center mb-4 text-white"></div>
                    <form id="create-discount-form" class="space-y-4">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-300">Seleziona Utente</label>
                            <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                                <option value="">-- Seleziona un utente --</option>
                                <?php while($user = $users_result->fetch_assoc()): ?>
                                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['name']) . ' (' . htmlspecialchars($user['email']) . ')'; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-300">Codice Sconto</label>
                            <input type="text" name="code" id="code" required placeholder="Es. BENVENUTO10" class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="percentage" class="block text-sm font-medium text-gray-300">Percentuale (%)</label>
                            <input type="number" step="0.01" name="percentage" id="percentage" required placeholder="Es. 10.00" class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-300">Data di Scadenza</label>
                            <input type="date" name="expiry_date" id="expiry_date" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                                Crea Sconto
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Discounts Table -->
                <div class="lg:col-span-2 bg-[#192233] rounded-lg border border-[#324467] overflow-x-auto">
                     <table class="w-full text-left text-sm text-gray-300">
                        <thead class="text-xs text-white uppercase bg-[#232f48]">
                            <tr>
                                <th class="px-6 py-3">Codice</th>
                                <th class="px-6 py-3">Utente</th>
                                <th class="px-6 py-3">Percentuale</th>
                                <th class="px-6 py-3">Scadenza</th>
                                <th class="px-6 py-3">Stato</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($discounts_result && $discounts_result->num_rows > 0): ?>
                                <?php while($row = $discounts_result->fetch_assoc()): ?>
                                    <tr class="border-b border-[#324467] hover:bg-[#232f48]">
                                        <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($row['code']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['percentage']); ?>%</td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($row['expiry_date']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $row['is_active'] ? 'bg-green-500/30 text-green-400' : 'bg-red-500/30 text-red-400'; ?>">
                                                <?php echo $row['is_active'] ? 'Attivo' : 'Scaduto'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">Nessuno sconto trovato.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('create-discount-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('discount-message');

    fetch('php/create_discount.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            form.reset();
            setTimeout(() => location.reload(), 1500); // Reload to see the new discount
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
