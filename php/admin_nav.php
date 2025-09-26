<div class="flex flex-col gap-4 p-4 bg-[#192233] w-72">
    <div class="flex items-center gap-3 px-2">
        <a href="home.php" class="flex items-center gap-3">
            <svg class="h-8 w-8 text-[var(--c-gold)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 10.32L3.88 24L24 37.68L44.12 24L24 10.32ZM24 4L48 24L24 44L0 24L24 4Z" fill="currentColor"></path>
                <path d="M24 20C26.2091 20 28 21.7909 28 24C28 26.2091 26.2091 28 24 28C21.7909 28 20 26.2091 20 24C20 21.7909 21.7909 20 24 20Z" stroke="#0c142c" stroke-miterlimit="10" stroke-width="2"></path>
            </svg>
            <h1 class="text-white text-xl font-bold font-serif">Villa Paradiso</h1>
        </a>
    </div>
    <nav class="flex flex-col gap-2 flex-1">
        <a class="flex items-center gap-3 px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors" href="dasboardAdmin.php">
            <span class="material-symbols-outlined">group</span>
            <p class="text-sm font-medium">Gestione Utenti</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors" href="dasboardAdmingestionePrenotazio.php">
            <span class="material-symbols-outlined">calendar_month</span>
            <p class="text-sm font-medium">Gestione Prenotazioni</p>
        </a>
        <a class="flex items-center gap-3 px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors" href="dasboardAdminGestioneSconti.php">
            <span class="material-symbols-outlined">sell</span>
            <p class="text-sm font-medium">Gestione Sconti</p>
        </a>
        <a class="flex items-center justify-between gap-3 px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors" href="dasboardAdmin.php">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">mail</span>
                <p class="text-sm font-medium">Comunicazioni</p>
            </div>
            <span id="message-notification-badge" class="hidden h-5 w-5 flex items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"></span>
        </a>
    </nav>
    <div class="flex flex-col gap-1 border-t border-white/10 pt-4">
        <a class="flex items-center gap-3 px-3 py-2 text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors" href="php/logout.php">
            <span class="material-symbols-outlined">logout</span>
            <p class="text-sm font-medium">Logout</p>
        </a>
    </div>
</div>
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
</script>
