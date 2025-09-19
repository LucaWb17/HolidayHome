<!DOCTYPE html>
<html lang="it"><head>
<meta charset="utf-8"/>
<link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
<link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Playfair+Display:wght@400;700&amp;family=Montserrat:wght@300;400;500" onload="this.rel='stylesheet'" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<title>Stitch Design</title>
<link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style type="text/tailwindcss">
      :root {
        --primary-color: #d4af37;
        --secondary-color: #001f3f;
        --background-color: #000000;
        --text-color: #ffffff;
        --accent-color: #f0f0f0;
      }
      body {
        font-family: 'Montserrat', sans-serif;
      }
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        font-family: 'Playfair Display', serif;
      }
      .coupon-card {
        background: linear-gradient(145deg, var(--secondary-color), #0a2d50);
        border: 1px solid var(--primary-color);
      }
    </style>
</head>
<body class="bg-[var(--background-color)] text-[var(--text-color)]">
<div class="relative flex size-full min-h-screen flex-col overflow-x-hidden">
<header class="sticky top-0 z-50 flex items-center justify-between whitespace-nowrap bg-[var(--secondary-color)]/80 px-10 py-4 backdrop-blur-md">
<div class="flex items-center gap-4">
<svg class="h-8 w-8 text-[var(--primary-color)]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 2L1 9L12 16L23 9L12 2Z"></path>
<path d="M1 9V17L12 22L23 17V9"></path>
</svg>
<h2 class="text-2xl font-bold tracking-wider text-[var(--primary-color)]" style="font-family: 'Playfair Display', serif">Aura Suites</h2>
</div>
<nav class="hidden items-center gap-8 md:flex">
<a class="text-sm font-medium uppercase tracking-widest transition-colors hover:text-[var(--primary-color)]" href="#">Home</a>
<a class="text-sm font-medium uppercase tracking-widest transition-colors hover:text-[var(--primary-color)]" href="#">Destinazioni</a>
<a class="text-sm font-medium uppercase tracking-widest transition-colors hover:text-[var(--primary-color)]" href="#">Esperienze</a>
<a class="text-sm font-medium uppercase tracking-widest text-[var(--primary-color)]" href="#">Offerte</a>
<a class="text-sm font-medium uppercase tracking-widest transition-colors hover:text-[var(--primary-color)]" href="#">Contatti</a>
</nav>
<div class="flex items-center gap-4">
<button class="flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-transparent text-[var(--text-color)] transition-colors hover:bg-[var(--primary-color)] hover:text-[var(--secondary-color)]">
<span class="material-symbols-outlined"> language </span>
</button>
<div class="aspect-square size-10 rounded-full border-2 border-[var(--primary-color)] bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuASxq1L5Avn1hrGJJsFaFWmGwyeYVi0eJnfVfpXyFzHZkgD7tHB5UxzofxCumAPi_1FmJhJZei9sq0F6iqwkcia3FAhcgKl71m9Jd81LfN_hjlcTX9S1NZ9GezieFTthJiyk7_g7KXC7f4yC_I5-pPiSdyk5Qiyw2ESNuAgGAVnFavODlsBgcNzKRM-8HjGyfJkjgQoR4jcQGLhmCFdf2WHqkPpHHoundd2xkAdmwGnXyYvXqfiK9QOFBmoJ4Dt-giCvARKgj7Ga1w");'></div>
</div>
</header>
<main class="flex-1">
<section class="relative bg-cover bg-fixed bg-center py-40" style='background-image: linear-gradient(rgba(0,31,63,0.7), rgba(0,31,63,0.7)), url("https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&amp;w=2070&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.0.3&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D")'>
<div class="container mx-auto text-center">
<h1 class="text-6xl font-bold text-[var(--primary-color)]">Offerte Esclusive &amp; Pacchetti</h1>
<p class="mt-4 text-lg text-[var(--accent-color)]">Concedetevi il lusso con la nostra selezione curata di offerte e pacchetti speciali.</p>
</div>
</section>
<section class="py-20">
<div class="container mx-auto px-10">
<h2 class="mb-12 text-center text-4xl font-bold text-[var(--primary-color)]">Le Nostre Offerte</h2>
<div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
<div class="group overflow-hidden rounded-lg bg-[var(--secondary-color)] shadow-lg shadow-black/30 transition-transform duration-300 hover:-translate-y-2">
<div class="relative">
<div class="aspect-video w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuApZx6ymuxDuPgRNGmeS0nxHx9p-dxy5ilapBzJ4MlCCtpHtjd6P1Wq2wqxFoLt7nflZMYHVIgRaxv3zMti_QlvzhN6ZHoiL9GhSUGKJmJk2zu-u83ow6ia23rqeAa-_P51B2cBtH06XzMQ5TDHxGhicuXeRNwcWJXr1bzS2amxdc3QEbckIknU4YtNcyOf3FcHrWWeI9W29mbuiQ-J9mnZPFZV5schgeuBcHBDTDfMORBO64KKuj2Hz47Zgd9kvYdKF-GpOfw2vnM");'></div>
<div class="absolute inset-0 bg-black/30"></div>
<div class="absolute bottom-4 left-4 rounded-full bg-[var(--primary-color)] px-4 py-1 text-sm font-bold text-[var(--secondary-color)]">Fino al 20% di Sconto</div>
</div>
<div class="p-6">
<h3 class="text-xl font-bold text-white">Fuga Estiva</h3>
<p class="mt-2 text-sm text-[var(--accent-color)]">
                    Prenotate la vostra vacanza estiva e godetevi il massimo del lusso e del relax.
                  </p>
<button class="mt-4 flex items-center gap-2 rounded-full bg-[var(--primary-color)] px-6 py-2 text-sm font-bold text-[var(--secondary-color)] transition-all hover:bg-white">
                    Scopri di più <span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</div>
</div>
<div class="group overflow-hidden rounded-lg bg-[var(--secondary-color)] shadow-lg shadow-black/30 transition-transform duration-300 hover:-translate-y-2">
<div class="relative">
<div class="aspect-video w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBY0FD2S3UDB_tr9yvUWTt8wQ6UeZ15iy0bZS3HhLrXLZFOTkywu-YwyiMoWTzUFMV4lq_skjMJKZL3dV8rj4z-kGmcIGmVT2Eabd6becp4sLThDgyzJ579vIw5NjRXkRa410JfdyJm_gimWO1iuChTCROHGtsTntZo2_T6uUc3NBn43zehbCQq91a44Ko-stEoXCZBS9HKv2C_SeHrBEwvpyBmY43zaFEcyeHCmE8uQfFufSOONA5fStEgTiyEcffB_1ecbn-3FY4");'></div>
<div class="absolute inset-0 bg-black/30"></div>
</div>
<div class="p-6">
<h3 class="text-xl font-bold text-white">Pacchetto Romantico</h3>
<p class="mt-2 text-sm text-[var(--accent-color)]">
                    Cena privata, massaggio di coppia e champagne all'arrivo per ricordi indimenticabili.
                  </p>
<button class="mt-4 flex items-center gap-2 rounded-full bg-[var(--primary-color)] px-6 py-2 text-sm font-bold text-[var(--secondary-color)] transition-all hover:bg-white">
                    Vedi Pacchetto <span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</div>
</div>
<div class="group overflow-hidden rounded-lg bg-[var(--secondary-color)] shadow-lg shadow-black/30 transition-transform duration-300 hover:-translate-y-2">
<div class="relative">
<div class="aspect-video w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC8cUkEfaCbULO7I5VihxNhowauDo7PDA1fPx5FOkDADc6y8cIR7g7VmuPlirtplmvJtgTgBcYKRlMVlXaT4CIJPo1SIJuXSUrWPj-R-gMYjC8lfwB114k9nc82-qC12hMGWOx3Ou_3Cm3gALCbWSqBICZkY9hw8qNUGuehDzDyxe0DHuPC5p8kofAFjpE7HtEouqWlBTa2R1tMqn_G4cJNHHeL8Q3v9qAZ_BEYgWscEn4rolQgrE-i4VDPco7RmXcU0HICL7DwEms");'></div>
<div class="absolute inset-0 bg-black/30"></div>
</div>
<div class="p-6">
<h3 class="text-xl font-bold text-white">Avventura in Famiglia</h3>
<p class="mt-2 text-sm text-[var(--accent-color)]">
                    Tour guidati, attività per bambini e pasti gratuiti per i più piccoli.
                  </p>
<button class="mt-4 flex items-center gap-2 rounded-full bg-[var(--primary-color)] px-6 py-2 text-sm font-bold text-[var(--secondary-color)] transition-all hover:bg-white">
                    Esplora Pacchetto <span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</div>
</div>
</div>
</div>
</section>
<section class="bg-[var(--secondary-color)] py-20">
<div class="container mx-auto px-10">
<h2 class="mb-12 text-center text-4xl font-bold text-[var(--primary-color)]">Codici Promozionali</h2>
<div class="mx-auto max-w-4xl space-y-8">
<div class="coupon-card flex flex-col items-center justify-between gap-6 rounded-lg p-8 md:flex-row">
<div class="flex-1 text-center md:text-left">
<h3 class="text-2xl font-bold text-[var(--primary-color)]">LUXURY10</h3>
<p class="mt-2 text-white">Usa il codice per il 10% di sconto sulla tua prossima prenotazione.</p>
</div>
<button class="w-full rounded-full border-2 border-dashed border-[var(--primary-color)] bg-transparent px-8 py-3 font-bold text-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-[var(--secondary-color)] md:w-auto">
                  COPIA CODICE
                </button>
</div>
<div class="coupon-card flex flex-col items-center justify-between gap-6 rounded-lg p-8 md:flex-row">
<div class="flex-1 text-center md:text-left">
<h3 class="text-2xl font-bold text-[var(--primary-color)]">FIRSTSTAY</h3>
<p class="mt-2 text-white">Nuovo cliente? Usa il codice per uno sconto speciale di benvenuto.</p>
</div>
<button class="w-full rounded-full border-2 border-dashed border-[var(--primary-color)] bg-transparent px-8 py-3 font-bold text-[var(--primary-color)] transition-all hover:bg-[var(--primary-color)] hover:text-[var(--secondary-color)] md:w-auto">
                  COPIA CODICE
                </button>
</div>
</div>
</div>
</section>
</main>
<footer class="bg-[var(--secondary-color)] py-10">
<div class="container mx-auto text-center text-sm text-[var(--accent-color)]">
<p>© 2024 Aura Suites. Tutti i diritti riservati.</p>
<p class="mt-2">Un'esperienza di lusso senza pari.</p>
</div>
</footer>
</div>

</body></html>
