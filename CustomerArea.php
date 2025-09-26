<?php
include 'php/config.php';

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch user data from the database
$stmt = $conn->prepare("SELECT name, email, phone, profile_image_path FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $profile_image_path);
$stmt->fetch();
$stmt->close();

include 'php/header.php';
?>

<main class="flex-1 px-10 py-12 md:px-20 lg:px-40 bg-gray-900 text-white">
    <div class="mx-auto max-w-5xl pt-20">
        <h2 class="text-5xl font-bold mb-12 font-serif">Il Mio Profilo</h2>
        <div id="avatar-message" class="text-center mb-4 text-white"></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="md:col-span-1 flex flex-col items-center md:items-start">
                <form id="avatar-form" class="relative mb-6">
                    <img id="avatar-image" src="<?php echo htmlspecialchars($profile_image_path ?? 'uploads/avatars/default.png'); ?>" alt="Immagine Profilo" class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-40 h-40 border-4 border-[var(--c-gold)]">
                    <input type="file" name="profile_image" id="profile_image_input" class="hidden" accept="image/png, image/jpeg, image/gif">
                    <button type="button" id="edit-avatar-button" class="absolute bottom-1 right-1 flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-[var(--c-gold)] text-black transition-transform hover:scale-110">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                </form>
                <h3 class="text-3xl font-bold font-serif"><?php echo htmlspecialchars($name); ?></h3>
            </div>
            <div class="md:col-span-2">
                <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-3xl font-bold text-[var(--c-gold)] font-serif">Dati Personali</h4>
                        <button id="edit-profile-button" class="text-sm font-semibold text-[var(--c-gold-bright)] hover:text-white transition-colors">Modifica Dati</button>
                    </div>

                    <!-- User Data Display -->
                    <div id="user-data-display" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-b border-white/20 pb-4">
                            <p class="text-gray-300 font-semibold">Nome</p>
                            <p class="col-span-2"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-b border-white/20 pb-4">
                            <p class="text-gray-300 font-semibold">Email</p>
                            <p class="col-span-2"><?php echo htmlspecialchars($email); ?></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-b border-white/20 pb-4">
                            <p class="text-gray-300 font-semibold">Telefono</p>
                            <p class="col-span-2"><?php echo htmlspecialchars($phone ?? 'N/A'); ?></p>
                        </div>
                    </div>

                    <!-- User Data Edit Form (Initially Hidden) -->
                    <div id="user-data-edit-form" class="hidden mt-6">
                        <div id="update-profile-message" class="text-center mb-4 text-white"></div>
                        <form id="update-profile-form" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300">Nome</label>
                                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required class="mt-1 block w-full rounded-md border-gray-500 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required class="mt-1 block w-full rounded-md border-gray-500 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-300">Telefono</label>
                                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-500 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                            </div>
                            <div class="flex justify-end gap-4 mt-6">
                                <button type="button" id="cancel-edit-button" class="py-2 px-4 rounded-full text-sm font-semibold hover:bg-white/10 transition-colors">Annulla</button>
                                <button type="submit" class="py-2 px-4 rounded-full text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] transition-colors">Salva Modifiche</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Admin Contact Info -->
                <div class="mt-8 bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                    <h4 class="text-3xl font-bold mb-6 text-[var(--c-gold)] font-serif">Contatta l'Amministratore</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <p class="text-gray-300 font-semibold">Email</p>
                            <p class="col-span-2">admin@villaparadiso.com</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <p class="text-gray-300 font-semibold">Telefono</p>
                            <p class="col-span-2">+39 123 456 7890</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-16">
            <div class="mb-12">
                <h3 class="text-4xl font-bold mb-8 font-serif">Le Mie Prenotazioni</h3>
                <div class="text-center bg-black/20 p-10 rounded-xl flex flex-col items-center gap-6">
                    <h4 class="text-2xl font-bold font-serif">Nessuna prenotazione trovata</h4>
                    <p class="text-gray-300 max-w-md">Non hai ancora effettuato prenotazioni. Inizia ora e scopri il lusso di Villa Paradiso.</p>
                    <a href="appointment.php" class="mt-4 flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-8 bg-[var(--c-gold)] text-black text-base font-bold tracking-wide transition-transform hover:scale-105">
                        <span class="truncate">Prenota Ora</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- My Messages Section -->
        <div class="mt-16">
            <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                <div class="flex items-center gap-4 mb-8">
                    <h3 class="text-4xl font-bold font-serif">I Miei Messaggi</h3>
                    <span id="message-notification-badge" class="hidden h-6 w-6 flex items-center justify-center rounded-full bg-red-500 text-sm font-bold text-white"></span>
                </div>
                <div class="space-y-4 max-h-[500px] overflow-y-auto">
                    <?php
                        $user_id = $_SESSION['id'];

                        // Mark all user's messages as read
                        $update_stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ?");
                        $update_stmt->bind_param("i", $user_id);
                        $update_stmt->execute();
                        $update_stmt->close();

                        // Fetch all messages for the user
                        $messages_stmt = $conn->prepare("SELECT * FROM messages WHERE receiver_id = ? ORDER BY timestamp DESC");
                        $messages_stmt->bind_param("i", $user_id);
                        $messages_stmt->execute();
                        $messages_result = $messages_stmt->get_result();
                        if ($messages_result->num_rows > 0):
                            while($msg = $messages_result->fetch_assoc()):
                    ?>
                                <div id="message-<?php echo $msg['id']; ?>" class="p-4 rounded-lg bg-[#111722]">
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="font-bold text-white"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                        <div class="flex items-center">
                                            <span class="text-xs text-gray-400 mr-4"><?php echo date("d/m/Y H:i", strtotime($msg['timestamp'])); ?></span>
                                            <button onclick="deleteMessage(<?php echo $msg['id']; ?>)" class="text-red-500 hover:text-red-400">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-gray-300"><?php echo nl2br(htmlspecialchars($msg['body'])); ?></p>
                                </div>
                            <?php
                            endwhile;
                        else:
                    ?>
                        <p class="text-gray-400 text-center">Non hai ricevuto nessun messaggio.</p>
                    <?php
                        endif;
                        $messages_stmt->close();
                    ?>
                </div>
            </div>
        </div>

        <!-- Send Message to Admin Section -->
        <div class="mt-16">
            <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                <h3 class="text-4xl font-bold mb-8 font-serif">Invia un Messaggio all'Amministratore</h3>
                <div id="user-message-response" class="text-center mb-4 text-white"></div>
                <form id="user-send-message-form" class="space-y-6 max-w-lg mx-auto">
                    <div>
                        <label for="user_subject" class="block text-sm font-medium text-white">Oggetto</label>
                        <input type="text" name="subject" id="user_subject" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="user_body" class="block text-sm font-medium text-white">Il tuo messaggio</label>
                        <textarea name="body" id="user_body" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                            Invia Messaggio
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="mt-16">
            <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                <h4 class="text-3xl font-bold mb-6 text-[var(--c-gold)] font-serif">Cambia Password</h4>
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
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBadge = document.getElementById('message-notification-badge');

    function fetchUnreadMessages() {
        fetch('php/get_unread_messages.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.unread_count > 0) {
                    notificationBadge.textContent = data.unread_count;
                    notificationBadge.classList.remove('hidden');
                } else {
                    notificationBadge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching unread messages:', error));
    }

    // Fetch immediately on page load
    fetchUnreadMessages();

    // And then fetch every 30 seconds
    setInterval(fetchUnreadMessages, 30000);
});

function deleteMessage(messageId) {
    if (!confirm('Sei sicuro di voler cancellare questo messaggio?')) {
        return;
    }

    const formData = new FormData();
    formData.append('message_id', messageId);
    const messageDiv = document.getElementById('user-message-response');

    fetch('php/delete_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            const messageElement = document.getElementById('message-' + messageId);
            if (messageElement) {
                messageElement.remove();
            }
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

// --- Avatar Upload Logic ---
document.getElementById('edit-avatar-button').addEventListener('click', function() {
    document.getElementById('profile_image_input').click();
});

document.getElementById('profile_image_input').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_image', file);
        const messageDiv = document.getElementById('avatar-message');

        fetch('php/upload_avatar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.textContent = data.message;
            if (data.status === 'success') {
                messageDiv.className = 'text-center mb-4 text-green-400';
                // Update the image src on success without a full page reload
                document.getElementById('avatar-image').src = data.new_image_path + '?t=' + new Date().getTime();
            } else {
                messageDiv.className = 'text-center mb-4 text-red-400';
            }
        })
        .catch(error => {
            messageDiv.className = 'text-center mb-4 text-red-400';
            messageDiv.textContent = 'An error occurred during upload.';
            console.error('Error:', error);
        });
    }
});

// --- User Send Message Logic ---
document.getElementById('user-send-message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('user-message-response');

    fetch('php/user_send_message.php', {
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


// --- Profile Edit Toggle Logic ---
const editProfileButton = document.getElementById('edit-profile-button');
const cancelEditButton = document.getElementById('cancel-edit-button');
const displayDiv = document.getElementById('user-data-display');
const editFormDiv = document.getElementById('user-data-edit-form');

editProfileButton.addEventListener('click', () => {
    displayDiv.classList.add('hidden');
    editFormDiv.classList.remove('hidden');
});

cancelEditButton.addEventListener('click', () => {
    editFormDiv.classList.add('hidden');
    displayDiv.classList.remove('hidden');
    document.getElementById('update-profile-message').textContent = '';
});


// --- Update Profile Logic ---
document.getElementById('update-profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('update-profile-message');

    fetch('php/update_user_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        if (data.status === 'success') {
            messageDiv.className = 'text-center mb-4 text-green-400';
            // Reload the page to show updated data
            setTimeout(() => location.reload(), 1500);
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


// --- Change Password Logic ---
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

<?php include 'php/footer.php'; ?>
