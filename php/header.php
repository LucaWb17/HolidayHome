<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <title>Villa Paradiso</title>
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
        .calendar-day.selected {
            background-color: var(--c-gold);
            color: black;
        }
    </style>
</head>
<body class="bg-white text-gray-800">
<div class="relative flex min-h-screen flex-col overflow-x-hidden">
    <header class="absolute top-0 left-0 z-20 w-full px-10 py-5">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <a class="flex items-center gap-3 text-white" href="home.php">
                <svg class="h-8 w-8 text-[var(--c-gold)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M24 10.32L3.88 24L24 37.68L44.12 24L24 10.32ZM24 4L48 24L24 44L0 24L24 4Z" fill="currentColor"></path>
                    <path d="M24 20C26.2091 20 28 21.7909 28 24C28 26.2091 26.2091 28 24 28C21.7909 28 20 26.2091 20 24C20 21.7909 21.7909 20 24 20Z" stroke="white" stroke-miterlimit="10" stroke-width="2"></path>
                </svg>
                <h2 class="font-serif text-2xl font-bold text-white">Villa Paradiso</h2>
            </a>
            <nav class="hidden items-center gap-8 md:flex">
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="home.php#panoramica">Panoramica</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="gallery.php">Camere</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="home.php#servizi">Servizi</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="home.php#posizione">Posizione</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="reviews.php">Recensioni</a>
            </nav>
            <div class="hidden items-center gap-4 md:flex">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <a class="relative text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="<?php echo $_SESSION['role'] === 'admin' ? 'dasboardAdmin.php' : 'CustomerArea.php'; ?>">
                        <span>Area Riservata</span>
                        <?php if ($_SESSION['role'] !== 'admin'): ?>
                            <span id="header-message-badge" class="absolute top-0 right-0 -mt-2 -mr-3 hidden h-5 w-5 flex items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"></span>
                        <?php endif; ?>
                    </a>
                    <a class="min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-full border border-[var(--c-gold)] bg-transparent h-11 px-6 text-sm font-bold text-white transition-all hover:bg-[var(--c-gold)] hover:text-black flex" href="php/logout.php">
                        <span class="truncate">Logout</span>
                    </a>
                <?php else: ?>
                    <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="login.php">Accedi</a>
                    <a class="min-w-[120px] cursor-pointer items-center justify-center overflow-hidden rounded-full border border-[var(--c-gold)] bg-transparent h-11 px-6 text-sm font-bold text-white transition-all hover:bg-[var(--c-gold)] hover:text-black flex" href="register.php">
                        <span class="truncate">Registrati</span>
                    </a>
                <?php endif; ?>
            </div>
            <button id="mobile-menu-button" class="md:hidden text-white">
                <span class="material-symbols-outlined text-3xl">menu</span>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-night-blue/80 backdrop-blur-sm rounded-lg mt-4" style="opacity: 0; transition: opacity 0.3s ease-in-out;">
            <nav class="flex flex-col items-center gap-4 p-4">
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="home.php">Panoramica</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="gallery.php">Gallery</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="reviews.php">Recensioni</a>
                <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="appointment.php">Prenota</a>
                 <div class="border-t border-white/20 w-full my-2"></div>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="<?php echo $_SESSION['role'] === 'admin' ? 'dasboardAdmin.php' : 'CustomerArea.php'; ?>">
                        Area Riservata
                    </a>
                    <a class="w-full flex items-center justify-center cursor-pointer rounded-full border border-[var(--c-gold)] bg-transparent h-11 px-6 text-sm font-bold text-white transition-all hover:bg-[var(--c-gold)] hover:text-black" href="php/logout.php">
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a class="text-white hover:text-[var(--c-gold)] transition-colors text-base font-medium" href="login.php">Accedi</a>
                    <a class="w-full flex items-center justify-center cursor-pointer rounded-full border border-[var(--c-gold)] bg-transparent h-11 px-6 text-sm font-bold text-white transition-all hover:bg-[var(--c-gold)] hover:text-black" href="register.php">
                        <span>Registrati</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton.addEventListener('click', function() {
        const isHidden = mobileMenu.classList.contains('hidden');
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            setTimeout(() => mobileMenu.style.opacity = 1, 10);
        } else {
            mobileMenu.style.opacity = 0;
            setTimeout(() => mobileMenu.classList.add('hidden'), 300);
        }
    });

    // --- User Message Notification Logic ---
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['role'] !== 'admin'): ?>
    const notificationBadge = document.getElementById('header-message-badge');

    function fetchUnreadMessages() {
        if (!notificationBadge) return; // Exit if badge element doesn't exist

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
    <?php endif; ?>
});
</script>
