<?php
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/db_config.php";

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Leer la opción de pago y la bandera enviada por GET
$option = $_GET['option'] ?? null;
$flag = $_GET['flag'] ?? null;

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
    // Redirigimos a comentarios_pagados.php pasando los parámetros paid=1, la opción elegida y la bandera
    "success_url" => "http://localhost:3000/comentarios_pagados.php?paid=1&option=" . urlencode($option) . "&flag=" . urlencode($flag),
    "cancel_url" => "http://localhost:3000/comentarios.php?flag=" . urlencode($flag),
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
