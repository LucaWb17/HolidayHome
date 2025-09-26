<?php
// Genera un nonce crittograficamente sicuro
$nonce = bin2hex(random_bytes(16));

// Definisci il nonce come costante per poterlo usare facilmente nell'HTML
if (!defined('CSP_NONCE')) {
    define('CSP_NONCE', $nonce);
}

// Definisci la Content Security Policy (CSP)
// Questa policy è restrittiva e permette solo le risorse specificate.
$csp = "default-src 'self';"; // Di default, permetti solo dal nostro dominio
$csp .= " script-src 'self' 'nonce-" . CSP_NONCE . "' https://cdn.tailwindcss.com;"; // Script: solo dal nostro dominio, con il nonce, o da tailwind
$csp .= " style-src 'self' 'nonce-" . CSP_NONCE . "' https://fonts.googleapis.com;"; // Stili: solo dal nostro dominio, con il nonce, o da google fonts
$csp .= " font-src 'self' https://fonts.gstatic.com;"; // Font: solo dal nostro dominio o da gstatic
$csp .= " img-src 'self' data: https://lh3.googleusercontent.com;"; // Immagini: solo dal nostro dominio, data URIs (per favicon), o da google user content (per sfondi login)
$csp .= " connect-src 'self';"; // Connessioni (fetch/AJAX): solo al nostro dominio
$csp .= " frame-ancestors 'none';"; // Impedisce al sito di essere inserito in un iframe (protezione da clickjacking)
$csp .= " form-action 'self';"; // I form possono inviare dati solo al nostro dominio
$csp .= " base-uri 'self';"; // Limita gli URL che possono essere usati nel tag <base>
$csp .= " object-src 'none';"; // Non permettere l'inclusione di plugin come Flash

// Invia l'header CSP
header("Content-Security-Policy: " . $csp);

// Altri header di sicurezza raccomandati
// Impedisce al browser di interpretare i file in modo diverso dal tipo di contenuto dichiarato
header("X-Content-Type-Options: nosniff");

// Protezione aggiuntiva contro il clickjacking per browser più vecchi
header("X-Frame-Options: DENY");

// Forza l'uso di HTTPS per tutte le future connessioni (se il sito è su HTTPS)
// NOTA: Decommentare solo in produzione su un sito con certificato SSL valido.
// header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
?>