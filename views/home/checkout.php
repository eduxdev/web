<?php
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "sk_test_51R9TMuH9I0WWLjb1HPrnQM9Sq99zxbe4CwVeJvPrfy14BSj0BVEApnBuvENxzmF7qsZg1fpKCbKIy56N8NCw4wKG00auv4Agrj";
\Stripe\Stripe::setApiKey($stripe_secret_key);

// Leer la opción de pago enviada por GET
$option = $_GET['option'] ?? null;

if ($option === 'comentario') {
    $unit_amount = 1100 * 100; // MXN 1100 en centavos
    $product_name = "Comentario Pagado";
} elseif ($option === 'media') {
    $unit_amount = 7000 * 100; // MXN 7000 en centavos
    $product_name = "Foto o Video Pagado";
} else {
    die("Opción de pago no válida.");
}

// Crear sesión de Checkout con Stripe
$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    // Redirigimos a comentarios_pagados.php pasando los parámetros paid=1 y la opción elegida
    "success_url" => "http://localhost:3000/views/home/comentarios_pagados.php?paid=1&option=" . urlencode($option),
    "cancel_url" => "http://localhost:3000/views/home/home.php",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "mxn",
                "unit_amount" => $unit_amount,
                "product_data" => [
                    "name" => $product_name
                ]
            ]
        ]
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
