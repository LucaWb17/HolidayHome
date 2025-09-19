<?php
include 'php/config.php';

$name = '';
$email = '';

// If the user is logged in, pre-fill the form with their data
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    $stmt->fetch();
    $stmt->close();
}

include 'php/header.php';
?>

<main class="flex-1 px-10 py-12 md:px-20 lg:px-40 bg-gray-900 text-white">
    <div class="mx-auto max-w-3xl pt-20">
        <h2 class="text-5xl font-bold mb-8 font-serif text-center text-[var(--c-gold)]">Prenota il Tuo Soggiorno</h2>
        <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm shadow-2xl border border-white/10">
            <div id="message" class="text-center mb-4 text-white"></div>
            <form id="booking-form" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-white">Nome Completo</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50" value="<?php echo htmlspecialchars($name); ?>">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50" value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="check_in" class="block text-sm font-medium text-white">Data di Check-in</label>
                        <input type="date" name="check_in" id="check_in" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="check_out" class="block text-sm font-medium text-white">Data di Check-out</label>
                        <input type="date" name="check_out" id="check_out" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label for="guests" class="block text-sm font-medium text-white">Numero di Ospiti</label>
                    <input type="number" name="guests" id="guests" min="1" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-lg font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                        Invia Richiesta di Prenotazione
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
document.getElementById('booking-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('message');

    fetch('php/create_booking.php', {
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

<?php include 'php/footer.php'; ?>
