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
                            <th class="px-6 py-3">Azioni</th>
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
                                    <td class="px-6 py-4">
                                        <a href="#" class="text-[var(--c-gold)] hover:underline">Modifica</a>
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
        </div>
    </div>
</main>

<?php
$conn->close();
include 'php/footer.php';
?>
