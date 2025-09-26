<?php
include 'php/config.php';
include 'php/admin_security.php';

// Fetch all bookings from the database
$result = $conn->query("SELECT * FROM bookings ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <title>Admin Dashboard - Gestione Prenotazioni</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <!-- flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            <h2 class="text-3xl font-bold text-white mb-2 font-serif">Gestione Prenotazioni</h2>
            <p class="text-gray-400 mb-8">Visualizza, conferma o cancella le prenotazioni dei clienti.</p>
            <div id="booking-message" class="text-center mb-4 text-white"></div>
            <div class="bg-[#192233] rounded-lg border border-[#324467] overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="text-xs text-white uppercase bg-[#232f48]">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Nome Cliente</th>
                            <th class="px-6 py-3">Contatti</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3 text-center">Ospiti</th>
                            <th class="px-6 py-3 text-center">Stato</th>
                            <th class="px-6 py-3 text-right">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php
                            while($row = $result->fetch_assoc()):
                                $status = trim(strtolower($row['status']));
                                $status_classes = [
                                    'pending' => 'bg-yellow-500/30 text-yellow-400',
                                    'confirmed' => 'bg-green-500/30 text-green-400',
                                    'cancelled' => 'bg-red-500/30 text-red-400',
                                ];
                                $status_class = $status_classes[$status] ?? 'bg-gray-500/30 text-gray-400';
                            ?>
                                <tr id="booking-row-<?php echo $row['id']; ?>" class="border-b border-[#324467] hover:bg-[#232f48]">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-6 py-4 font-medium text-white"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="px-6 py-4">
                                        <div><?php echo htmlspecialchars($row['email']); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($row['check_in']) . ' - ' . htmlspecialchars($row['check_out']); ?></td>
                                    <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($row['guests']); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <?php if ($status === 'pending'): ?>
                                            <button onclick="updateStatus(<?php echo $row['id']; ?>, 'confirmed')" class="text-green-400 hover:underline text-xs">Conferma</button>
                                            <button onclick="updateStatus(<?php echo $row['id']; ?>, 'cancelled')" class="text-red-400 hover:underline ml-2 text-xs">Cancella</button>
                                        <?php else: ?>
                                            <span class="text-gray-500 text-xs">Nessuna azione</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">Nessuna prenotazione trovata.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add new booking form -->
            <div class="mt-10 bg-[#192233] p-8 rounded-lg border border-[#324467]">
                <h3 class="text-2xl font-bold mb-6 text-white font-serif">Aggiungi Nuova Prenotazione</h3>
                <div id="add-booking-message" class="text-center mb-4 text-white"></div>
                <form id="add-booking-form" class="space-y-6 max-w-2xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-white">Nome Cliente</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white">Email Cliente</label>
                            <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-white">Telefono Cliente</label>
                        <input type="tel" name="phone" id="phone" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="admin-date-range" class="block text-sm font-medium text-white">Periodo del Soggiorno</label>
                        <input type="text" id="admin-date-range" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50" placeholder="Seleziona le date...">
                        <!-- Hidden inputs to store the actual dates for the form submission -->
                        <input type="hidden" name="check_in" id="check_in">
                        <input type="hidden" name="check_out" id="check_out">
                    </div>
                     <div>
                        <label for="guests" class="block text-sm font-medium text-white">Numero di Ospiti</label>
                        <input type="number" name="guests" id="guests" min="1" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                            Crea Prenotazione
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
function updateStatus(bookingId, newStatus) {
    const messageDiv = document.getElementById('booking-message');
    if (!confirm(`Sei sicuro di voler impostare lo stato su '${newStatus}' per questa prenotazione?`)) {
        return;
    }

    const formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('new_status', newStatus);

    fetch('php/update_booking_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            setTimeout(() => location.reload(), 1500); // Reload to see the change
        } else {
            messageDiv.className = 'text-center mb-4 text-red-400';
        }
    })
    .catch(error => {
        messageDiv.className = 'text-center mb-4 text-red-400';
        messageDiv.textContent = 'An error occurred. Please try again.';
        console.error('Error:', error);
    });
}
</script>

            <!-- Add new booking form -->
            <div class="mt-10 bg-[#192233] p-8 rounded-lg border border-[#324467]">
                <h3 class="text-2xl font-bold mb-6 text-white font-serif">Aggiungi Nuova Prenotazione</h3>
                <div id="add-booking-message" class="text-center mb-4 text-white"></div>
                <form id="add-booking-form" class="space-y-6 max-w-2xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-white">Nome Cliente</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white">Email Cliente</label>
                            <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-white">Telefono Cliente</label>
                        <input type="tel" name="phone" id="phone" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="check_in" class="block text-sm font-medium text-white">Data di Check-in</label>
                            <input type="date" name="check_in" id="check_in" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="check_out" class="block text-sm font-medium text-white">Data di Check-out</label>
                            <input type="date" name="check_out" id="check_out" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                        </div>
                    </div>
                     <div>
                        <label for="guests" class="block text-sm font-medium text-white">Numero di Ospiti</label>
                        <input type="number" name="guests" id="guests" min="1" required class="mt-1 block w-full rounded-md border-gray-500 bg-[#111722] text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                            Crea Prenotazione
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
<script>
document.getElementById('add-booking-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('add-booking-message');

    fetch('php/admin_create_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            form.reset();
            setTimeout(() => location.reload(), 1500); // Reload to see the new booking
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
<!-- flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Italian locale for flatpickr (must be after the main script) -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch booked dates and initialize the calendar for the admin form
    fetch('php/get_booked_dates.php')
        .then(response => response.json())
        .then(bookedDates => {
            flatpickr("#admin-date-range", {
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "it", // Set Italian locale
                disable: bookedDates,
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        document.getElementById('check_in').value = instance.formatDate(selectedDates[0], "Y-m-d");
                        document.getElementById('check_out').value = instance.formatDate(selectedDates[1], "Y-m-d");
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching booked dates for admin calendar:', error));
});
</script>
</body>
</html>
<?php
$conn->close();
?>
