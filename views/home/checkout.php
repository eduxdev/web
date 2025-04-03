<?php
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = "sk_test_51R9TMuH9I0WWLjb1HPrnQM9Sq99zxbe4CwVeJvPrfy14BSj0BVEApnBuvENxzmF7qsZg1fpKCbKIy56N8NCw4wKG00auv4Agrj";

\Stripe\Stripe::setApiKey($stripe_secret_key);

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    // Al pago exitoso redirigimos de nuevo a comentarios_pagados.php?paid=1
    "success_url" => "http://localhost:3000/views/home/comentarios_pagados.php?paid=1",
    "cancel_url" => "http://localhost:3000/views/home/home.php",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "mxn",
                "unit_amount" => 1000,
                "product_data" => [
                    "name" => "Anuncio Pagado"
                ]
            ]
        ]
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
