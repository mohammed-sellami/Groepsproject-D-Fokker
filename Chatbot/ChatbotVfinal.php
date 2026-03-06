<?php
// ============================================================================
// CHATBOT CONFIG – GEMINI 2.5 FLASH
// ============================================================================

// VUL HIER JE ECHTE GEMINI API KEY IN
define('GEMINI_API_KEY', 'HIER_JOUW_API_KEY');

// ============================================================================
// REST API ENDPOINTS
// ============================================================================

add_action('rest_api_init', function () {

    // Orderstatus (WooCommerce)
    register_rest_route('chatbot/v1', '/order', array(
        'methods'             => 'POST',
        'callback'            => 'chatbot_get_order_status',
        'permission_callback' => '__return_true'
    ));

    // AI-chat endpoint (hoofdchatbot)
    register_rest_route('chatbot/v1', '/ai-chat', array(
        'methods'             => 'POST',
        'callback'            => 'chatbot_get_gemini_response',
        'permission_callback' => '__return_true'
    ));

    // Simpele test zonder AI
    register_rest_route('chatbot/v1', '/test', array(
        'methods'             => 'GET',
        'callback'            => function () {
            return array(
                'success'            => true,
                'message'            => 'Chatbot API werkt!',
                'woocommerce_active' => class_exists('WooCommerce'),
                'gemini_configured'  => defined('GEMINI_API_KEY') && strlen(GEMINI_API_KEY) > 20,
            );
        },
        'permission_callback' => '__return_true'
    ));

    // Testcall naar Gemini – handig voor debugging
    register_rest_route('chatbot/v1', '/test-gemini', array(
        'methods'             => 'GET',
        'callback'            => function () {

            if (!defined('GEMINI_API_KEY') || strlen(GEMINI_API_KEY) < 20) {
                return array(
                    'success' => false,
                    'error'   => 'API key niet ingesteld'
                );
            }

            $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY;

            $body_data = array(
                'contents' => array(
                    array(
                        'parts' => array(
                            array('text' => 'Zeg hallo in het Nederlands')
                        )
                    )
                )
            );

            $response = wp_remote_post($api_url, array(
                'headers' => array('Content-Type' => 'application/json'),
                'body'    => wp_json_encode($body_data),
                'timeout' => 30
            ));

            if (is_wp_error($response)) {
                return array(
                    'success' => false,
                    'error'   => $response->get_error_message()
                );
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);

            return array(
                'success'       => true,
                'response_code' => wp_remote_retrieve_response_code($response),
                'body'          => $body
            );
        },
        'permission_callback' => '__return_true'
    ));

});

// ============================================================================
// GEMINI CHAT ENDPOINT
// ============================================================================

function chatbot_get_gemini_response($request) {
    $message             = sanitize_text_field($request->get_param('message'));
    $conversation_history = $request->get_param('history');

    if (empty($message)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Geen bericht ontvangen.'
        ), 400);
    }

    if (!defined('GEMINI_API_KEY') || strlen(GEMINI_API_KEY) < 20) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Gemini API key is niet ingesteld.'
        ), 500);
    }

    $prompt = chatbot_build_prompt($message, $conversation_history);

    $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY;

    $body_data = array(
        'contents' => array(
            array(
                'parts' => array(
                    array('text' => $prompt)
                )
            )
        )
    );

    // Kleine retry-loop i.v.m. 429
    $max_retries = 3;
    $retry_count = 0;
    $response    = false;

    while (true) {
        $response = wp_remote_post($api_url, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body'    => wp_json_encode($body_data),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            break;
        }

        $code = wp_remote_retrieve_response_code($response);
        if ($code === 429 && $retry_count < $max_retries) {
            sleep(2);
            $retry_count++;
            continue;
        }

        break;
    }

    if (is_wp_error($response)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Sorry, ik kan momenteel geen verbinding maken met de AI.',
            'debug'   => $response->get_error_message()
        ), 500);
    }

    $body          = json_decode(wp_remote_retrieve_body($response), true);
    $response_code = wp_remote_retrieve_response_code($response);

    if ($response_code !== 200) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'API Error (' . $response_code . ')',
            'debug'   => array(
                'response_code' => $response_code,
                'response_body' => $body ? $body : 'empty body',
                'prompt'        => substr($prompt, 0, 200) . '...'
            )
        ), 500);
    }

    if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
        $ai_response = $body['candidates'][0]['content']['parts'][0]['text'];

        return new WP_REST_Response(array(
            'success' => true,
            'message' => $ai_response
        ), 200);
    }

    return new WP_REST_Response(array(
        'success' => false,
        'message' => 'Unexpected response format',
        'debug'   => array('body' => $body)
    ), 500);
}

// ============================================================================
// PROMPT OPBOUW
// ============================================================================

function chatbot_build_prompt($user_message, $history = array()) {
    $context = '';

    if (!empty($history) && is_array($history)) {
        $context = "GESPREKSGESCHIEDENIS:\n";
        $recent_history = array_slice($history, -3); // laatste 3 berichten
        foreach ($recent_history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $context .= ucfirst($msg['role']) . ': ' . $msg['content'] . "\n";
            }
        }
        $context .= "\n";
    }

    $prompt = "Je bent een vriendelijke slaap- en matrasexpert voor een Nederlandse beddenwinkel.\n\n"
        . "PRODUCTINFORMATIE:\n"
        . "- We verkopen Bremafa matrassen.\n"
        . "- Pocketvering: goede ventilatie.\n"
        . "- Koudschuim: stevig, hypoallergeen.\n"
        . "- Latex: elastisch en duurzaam.\n"
        . "- Traagschuim: drukverdelend.\n\n"
        . "HARDHEDEN:\n"
        . "- H1/Zacht: < 60kg.\n"
        . "- H2/Medium: 60-80kg.\n"
        . "- H3/Medium-hard: 80-100kg.\n"
        . "- H4/Hard: > 100kg.\n\n"
        . "ADVIES:\n"
        . "- Zijslapers → H2.\n"
        . "- Rugslapers → H2-H3.\n"
        . "- Buikslapers → H3.\n"
        . "- Rugpijn → H3-H4.\n\n"
        . "GEDRAG:\n"
        . "- Stel 1-2 gerichte vragen (slaaphouding, gewicht, klachten).\n"
        . "- Antwoord in maximaal 150 woorden.\n"
        . "- Je mag maximaal 2 emoji gebruiken.\n\n"
        . $context
        . "VRAAG VAN KLANT: " . $user_message . "\n\n"
        . "GEFORMATTEERD ANTWOORD:";

    return $prompt;
}

// ============================================================================
// ORDERSTATUS (WooCommerce) – ONGEWIJZIGD UIT JE OUDE CODE
// ============================================================================

function chatbot_get_order_status($request) {
    if (!class_exists('WooCommerce')) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'WooCommerce is niet actief.'
        ), 500);
    }

    $order_number = sanitize_text_field($request->get_param('order_number'));
    $email        = sanitize_email($request->get_param('email'));

    if (empty($order_number) || empty($email)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Ordernummer en e-mailadres zijn verplicht.'
        ), 400);
    }

    $order_number = str_replace('#', '', $order_number);
    $order        = wc_get_order($order_number);

    if (!$order || strtolower($order->get_billing_email()) !== strtolower($email)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => 'Order niet gevonden.'
        ), 404);
    }

    $items = array();
    foreach ($order->get_items() as $item) {
        $items[] = array(
            'name'     => $item->get_name(),
            'quantity' => $item->get_quantity(),
            'price'    => wc_price($item->get_total())
        );
    }

    $status_names = array(
        'pending'   => 'In behandeling',
        'processing'=> 'Wordt verwerkt',
        'on-hold'   => 'In wachtstand',
        'completed' => 'Voltooid',
        'cancelled' => 'Geannuleerd',
        'refunded'  => 'Terugbetaald',
        'failed'    => 'Mislukt'
    );

    $status      = $order->get_status();
    $status_name = isset($status_names[$status]) ? $status_names[$status] : ucfirst($status);

    $tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
    if (empty($tracking_number)) {
        $tracking_number = $order->get_meta('_shipment_tracking_number');
    }

    $shipping_address = $order->get_formatted_shipping_address();
    $billing_address  = $order->get_formatted_billing_address();
    if (empty($shipping_address)) {
        $shipping_address = $billing_address;
    }

    return new WP_REST_Response(array(
        'success' => true,
        'order'   => array(
            'number'   => $order->get_order_number(),
            'status'   => $status,
            'status_name' => $status_name,
            'date'     => $order->get_date_created()->date('d-m-Y H:i'),
            'total'    => wc_price($order->get_total()),
            'items'    => $items,
            'tracking_number' => $tracking_number ?: null,
            'shipping' => array('address' => $shipping_address),
            'billing'  => array('address' => $billing_address)
        )
    ), 200);
}
