<?php
include 'php/config.php';

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch user data from the database
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

include 'php/header.php';
?>

<main class="flex-1 px-10 py-12 md:px-20 lg:px-40 bg-gray-900 text-white">
    <div class="mx-auto max-w-5xl pt-20">
        <h2 class="text-5xl font-bold mb-12 font-serif">Il Mio Profilo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="md:col-span-1 flex flex-col items-center md:items-start">
                <div class="relative mb-6">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-40 h-40 border-4 border-[var(--c-gold)]" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBP5YnpfnbFZLkUC9R8NLO3A4KnBnTg6Bo3wMaoARd01QyqfKtDUKo0X3l4JBZWezeN3RimC89DYUaLraWaIIZyY15AlNf2hQOdGrOZpF7vKQKmL1Trp-mVSw9ECNErFvgAwDG9_1Y6-PFtPFyeY-G3I59I3_AlhcPyk0_DeGJKROorHh1aem9o376bxfFaPP9yXGHRDYnFWhOUzXvlHN6KEDo_DmOTMmciNjCAqCzT0qF-EKO3EVqmb-y0QmHbp2vuqdVwyOJ1KBY");'></div>
                    <button class="absolute bottom-1 right-1 flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-[var(--c-gold)] text-black transition-transform hover:scale-110">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                </div>
                <h3 class="text-3xl font-bold font-serif"><?php echo htmlspecialchars($name); ?></h3>
            </div>
            <div class="md:col-span-2">
                <div class="bg-black/20 p-8 rounded-xl backdrop-blur-sm">
                    <h4 class="text-3xl font-bold mb-6 text-[var(--c-gold)] font-serif">Dati Personali</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-b border-white/20 pb-4">
                            <p class="text-gray-300 font-semibold">Nome</p>
                            <p class="col-span-2"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 border-b border-white/20 pb-4">
                            <p class="text-gray-300 font-semibold">Email</p>
                            <p class="col-span-2"><?php echo htmlspecialchars($email); ?></p>
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
    </div>
</main>

<?php include 'php/footer.php'; ?>
