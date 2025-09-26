<?php require_once 'php/security_headers.php'; ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&amp;family=Poppins:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <title>Login - Villa Paradiso</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss" nonce="<?php echo CSP_NONCE; ?>">
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
<body class="bg-gray-100 text-gray-800">
    <div class="relative flex min-h-screen flex-col items-center justify-center bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD66_GBzzaX3wkjiQnhZGxC4ptjfI-MpKeod70b3IaQLA_zjfUIkJMM-QqM1py1jp2MT5WPBI4Sg6S85bWvG4a9zGfQ8LoBnoJ3s1-rkGoMN61BJcegyitv0qusJmc4g5qPINSNHhI0QbX3SUb3d79m7yezF_gcRKNolp5GTHMdue0iSQKiOP0u7KZFDOxmPpPuHkUe6i3Tb9CGcYG2IKYfNyXApauXxNV-YZxYmqkkge61tqKn9fgLYe_xWyigIygzFdcRHZ8PA-Q");'>
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <div class="relative z-20 w-full max-w-md p-8 bg-white/10 backdrop-blur-sm rounded-xl shadow-lg border border-white/20">
            <div class="text-center mb-8">
                <a class="flex items-center justify-center gap-3 text-white" href="home.php">
                    <svg class="h-8 w-8 text-[var(--c-gold)]" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 10.32L3.88 24L24 37.68L44.12 24L24 10.32ZM24 4L48 24L24 44L0 24L24 4Z" fill="currentColor"></path>
                        <path d="M24 20C26.2091 20 28 21.7909 28 24C28 26.2091 26.2091 28 24 28C21.7909 28 20 26.2091 20 24C20 21.7909 21.7909 20 24 20Z" stroke="white" stroke-miterlimit="10" stroke-width="2"></path>
                    </svg>
                    <h2 class="font-serif text-3xl font-bold text-white">Villa Paradiso</h2>
                </a>
            </div>
            <h3 class="font-serif text-2xl font-bold text-center text-white mb-6">Accedi al tuo account</h3>
            <div id="message" class="text-center mb-4 text-white"></div>
            <form id="login-form" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 bg-white/20 text-white shadow-sm focus:border-[var(--c-gold)] focus:ring focus:ring-[var(--c-gold)] focus:ring-opacity-50">
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-full shadow-sm text-sm font-bold text-black bg-[var(--c-gold-bright)] hover:bg-[var(--c-gold)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--c-gold)] transition-all">
                        Accedi
                    </button>
                </div>
            </form>
            <p class="mt-6 text-center text-sm text-gray-300">
                Non hai un account? <a href="register.php" class="font-medium text-[var(--c-gold-bright)] hover:text-[var(--c-gold)]">Registrati</a>
            </p>
        </div>
    </div>

    <script nonce="<?php echo CSP_NONCE; ?>">
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const messageDiv = document.getElementById('message');

            fetch('php/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                if (data.status === 'success') {
                    messageDiv.className = 'text-center mb-4 text-green-400';
                    if (data.role === 'admin') {
                        window.location.href = 'dasboardAdmin.php';
                    } else {
                        window.location.href = 'CustomerArea.php';
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
        });
    </script>
</body>
</html>
