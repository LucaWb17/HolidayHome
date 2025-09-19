<?php include 'php/header.php'; ?>
<main class="flex-grow">
    <section class="relative h-screen min-h-[800px] w-full text-white">
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <div class="absolute inset-0 h-full w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD66_GBzzaX3wkjiQnhZGxC4ptjfI-MpKeod70b3IaQLA_zjfUIkJMM-QqM1py1jp2MT5WPBI4Sg6S85bWvG4a9zGfQ8LoBnoJ3s1-rkGoMN61BJcegyitv0qusJmc4g5qPINSNHhI0QbX3SUb3d79m7yezF_gcRKNolp5GTHMdue0iSQKiOP0u7KZFDOxmPpPuHkUe6i3Tb9CGcYG2IKYfNyXApauXxNV-YZxYmqkkge61tqKn9fgLYe_xWyigIygzFdcRHZ8PA-Q");'></div>
        <div class="relative z-20 flex h-full flex-col items-center justify-center px-4 text-center">
            <h1 class="font-serif text-5xl font-bold leading-tight md:text-7xl">Benvenuti a Villa Paradiso</h1>
            <p class="mt-4 max-w-2xl text-lg font-light text-gray-200">
                Immergiti nel lusso e nella tranquillità, un rifugio esclusivo dove l'eleganza incontra il comfort.
            </p>
            <div class="mt-12 w-full max-w-4xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-full p-2 flex items-center shadow-lg border border-white/20">
                    <div class="flex-1 px-4 py-2 cursor-pointer hover:bg-white/20 rounded-full">
                        <label class="block text-xs font-bold text-white uppercase tracking-wider" for="checkin">Check-in</label>
                        <input class="w-full bg-transparent text-white placeholder-gray-300 focus:outline-none text-base" id="checkin" type="text" value="Aggiungi data"/>
                    </div>
                    <div class="w-px h-10 bg-white/30"></div>
                    <div class="flex-1 px-4 py-2 cursor-pointer hover:bg-white/20 rounded-full">
                        <label class="block text-xs font-bold text-white uppercase tracking-wider" for="checkout">Check-out</label>
                        <input class="w-full bg-transparent text-white placeholder-gray-300 focus:outline-none text-base" id="checkout" type="text" value="Aggiungi data"/>
                    </div>
                    <div class="w-px h-10 bg-white/30"></div>
                    <div class="flex-1 px-4 py-2 cursor-pointer hover:bg-white/20 rounded-full">
                        <label class="block text-xs font-bold text-white uppercase tracking-wider" for="guests">Ospiti</label>
                        <input class="w-full bg-transparent text-white placeholder-gray-300 focus:outline-none text-base" id="guests" type="text" value="Aggiungi ospiti"/>
                    </div>
                    <button class="bg-[var(--c-gold-bright)] text-black rounded-full p-3 ml-2 hover:bg-[var(--c-gold)] transition-colors">
                        <span class="material-symbols-outlined text-2xl">search</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-serif text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Un'Esperienza Indimenticabile</h2>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Situata in un contesto idilliaco, Villa Paradiso offre un'esperienza indimenticabile, perfetta per una vacanza da sogno. Scopri la bellezza dei nostri spazi, curati in ogni dettaglio per garantirti il massimo relax.
                </p>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-y-10 sm:mt-24 sm:grid-cols-2 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
                <div class="overflow-hidden rounded-2xl shadow-xl transition-shadow duration-300 hover:shadow-2xl">
                    <div class="h-64 w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCl9tY6QvRMb8Ko8nt-hncH_QkDThsZoYSIEleebke3UScu0UOsbYNGJ8a_nwyCuLyDIUEagezMSwGBj-mHDy63MgPlKNbhHX3XHC69kb1V9ls4Ek68pRRUm2TwWR0gd5pxueigKLfC9-A7uBg-NArZqr-7M8vrJ_p0FkmL4NSJbl7JcqPn1ZcrBbetX03x6qepXazeb-BKWB-2R3b8sHzp-aak-PctipvW_th3MIPzzvm0AGXIvlPO-rUFLzrqXjE5qcjtfQPmPyE");'></div>
                </div>
                <div class="overflow-hidden rounded-2xl shadow-xl transition-shadow duration-300 hover:shadow-2xl">
                    <div class="h-64 w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCAoUmDOGRNady5ZnI9pFMSCaUFWc5PKTjxXmRNprMPXPwQN7ps9WebgTnJh9uyk55O3r65kZBpvpmPnW1J5vFqxL5epS8ZFHG0_QfK6xntU51RISGsumUlT7vxFje4JYspo9cMzFYltgGv0hTEePjOpZTkO5H9u5pYpoAodidmpDo7XTMbvnOIg0hvzvq9thRcXnl4F6y4BkVSim0_9typQmWvUAdCEIRq1O0dCSfFMUZ-yUlj3JwvQAqz4YzQeDEqik2CEfQ0UFk");'></div>
                </div>
                <div class="overflow-hidden rounded-2xl shadow-xl transition-shadow duration-300 hover:shadow-2xl">
                    <div class="h-64 w-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAWTzafLr5TfpQ85kj253G1gkhhDSF2g_YBwsYWrCi8PBld4L-HDC_fqoxYsLwLRpx7sFhVjCyZxGaYmU1ZyNcAXNn85lx376Gt93-1dsuExn2R3fBB0DrXgdVAE8vta2QtvrAziQ29d2-4L4ZebqP6X8JXNlCI6kouqL9eBi4NYREdUxl8MOcn0kuSneAy6CxZvDTu3sY9O-oQk74Sfzzwtiz6o-91uLxjcD1Sgcoylhq4a_zBEOsHOHh1Tj0ZvLKH6R6HNNvGFgc");'></div>
                </div>
            </div>
            <div class="mt-16 flex justify-center">
                <a class="flex min-w-[150px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-full h-12 px-8 bg-[var(--c-night-blue)] text-white text-base font-bold transition-transform hover:scale-105" href="appointment.php">
                    <span class="truncate">Prenota il tuo soggiorno</span>
                </a>
            </div>
        </div>
    </section>
</main>
<?php include 'php/footer.php'; ?>
