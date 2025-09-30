<?php
function send_booking_emails($customer_email, $customer_name, $check_in, $check_out, $guests) {
    // Assicurati che la configurazione sia caricata per accedere a ADMIN_EMAIL
    // Questo è già gestito in create_booking.php, quindi non è necessario un require qui.

    // Email to customer
    $customer_subject = 'Your Booking Confirmation';
    $customer_message = "
        <html>
        <head>
            <title>Your Booking Confirmation</title>
        </head>
        <body>
            <p>Dear $customer_name,</p>
            <p>Thank you for your booking. Here are your details:</p>
            <ul>
                <li>Check-in: $check_in</li>
                <li>Check-out: $check_out</li>
                <li>Guests: $guests</li>
            </ul>
            <p>We look forward to welcoming you.</p>
        </body>
        </html>
    ";
    $customer_headers = "MIME-Version: 1.0" . "\r\n";
    $customer_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $customer_headers .= "From: no-reply@example.com" . "\r\n";

    mail($customer_email, $customer_subject, $customer_message, $customer_headers);

    // Email to admin
    $admin_subject = 'New Booking Received';
    $admin_message = "
        <html>
        <head>
            <title>New Booking Received</title>
        </head>
        <body>
            <p>A new booking has been made.</p>
            <p>Details:</p>
            <ul>
                <li>Name: $customer_name</li>
                <li>Email: $customer_email</li>
                <li>Check-in: $check_in</li>
                <li>Check-out: $check_out</li>
                <li>Guests: $guests</li>
            </ul>
        </body>
        </html>
    ";
    $admin_headers = "MIME-Version: 1.0" . "\r\n";
    $admin_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $admin_headers .= "From: system@example.com" . "\r\n";

    mail(ADMIN_EMAIL, $admin_subject, $admin_message, $admin_headers);
}

function send_password_reset_email($user_email, $reset_link) {
    $subject = 'Richiesta di Reset Password - Villa Paradiso';
    $message = "
        <html>
        <head>
            <title>Reset della tua Password</title>
        </head>
        <body>
            <p>Ciao,</p>
            <p>Abbiamo ricevuto una richiesta di reset della password per il tuo account.</p>
            <p>Clicca sul link qui sotto per scegliere una nuova password. Il link scadrà tra un'ora.</p>
            <p><a href='" . htmlspecialchars($reset_link) . "'>Reimposta la tua password</a></p>
            <p>Se non hai richiesto tu il reset, puoi tranquillamente ignorare questa email.</p>
            <p>Grazie,<br>Il team di Villa Paradiso</p>
        </body>
        </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@villaparadiso.com" . "\r\n"; // Usa un indirizzo no-reply

    // Invia l'email
    if (!mail($user_email, $subject, $message, $headers)) {
        // Lancia un'eccezione se l'invio fallisce, che può essere catturata nel chiamante
        throw new Exception("La funzione mail() ha restituito false.");
    }
}
?>