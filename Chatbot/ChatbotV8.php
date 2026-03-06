<?php
/**
 * VERSIE 8 - 27 FEBRUARI 2026
 * GEMINI 2.5 FLASH + VOLLEDIGE PROMPT TERUG
 * Fix: Model geupgraded naar gemini-2.5-flash
 * Fix: Volledige gedetailleerde prompt terug
 * Fix: History werkt stabiel
 * Vervang je hele functions.php met deze code
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.5' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );
define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}
		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}
		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}
		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'html5', [
				'search-form', 'comment-form', 'comment-list',
				'gallery', 'caption', 'script', 'style', 'navigation-widgets',
			]);
			add_theme_support( 'custom-logo', [
				'height'      => 100,
				'width'       => 350,
				'flex-height' => true,
				'flex-width'  => true,
			]);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				add_theme_support( 'woocommerce' );
				add_theme_support( 'wc-product-gallery-zoom' );
				add_theme_support( 'wc-product-gallery-lightbox' );
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	$hello_theme_db_version    = get_option( $theme_version_option_name );
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;
		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style( 'hello-elementor', HELLO_THEME_STYLE_URL . 'reset.css', [], HELLO_ELEMENTOR_VERSION );
		}
		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style( 'hello-elementor-theme-style', HELLO_THEME_STYLE_URL . 'theme.css', [], HELLO_ELEMENTOR_VERSION );
		}
		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style( 'hello-elementor-header-footer', HELLO_THEME_STYLE_URL . 'header-footer.css', [], HELLO_ELEMENTOR_VERSION );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}
		if ( ! is_singular() ) {
			return;
		}
		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

require get_template_directory() . '/includes/settings-functions.php';
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}
		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';
HelloTheme\Theme::instance();

// ============================================================================
// CHATBOT CONFIG - GEMINI 2.5 FLASH
// ============================================================================

define('GEMINI_API_KEY', 'AIzaSyBjENPAcyPiVIAkgjnciJUleo0nAdhRf8I');

// ============================================================================
// REST API ENDPOINTS
// ============================================================================

add_action('rest_api_init', function () {

    register_rest_route('chatbot/v1', '/order', array(
        'methods'             => 'POST',
        'callback'            => 'chatbot_get_order_status',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('chatbot/v1', '/ai-chat', array(
        'methods'             => 'POST',
        'callback'            => 'chatbot_get_gemini_response',
        'permission_callback' => '__return_true'
    ));

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

    register_rest_route('chatbot/v1', '/test-gemini', array(
        'methods'             => 'GET',
        'callback'            => function () {
            if (!defined('GEMINI_API_KEY') || strlen(GEMINI_API_KEY) < 20) {
                return array('success' => false, 'error' => 'API key niet ingesteld');
            }
            // ✅ GEUPGRADED: gemini-2.5-flash
            $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY;
            $body_data = array(
                'contents' => array(
                    array('parts' => array(array('text' => 'Zeg hallo in het Nederlands')))
                )
            );
            $response = wp_remote_post($api_url, array(
                'headers' => array('Content-Type' => 'application/json'),
                'body'    => wp_json_encode($body_data),
                'timeout' => 30
            ));
            if (is_wp_error($response)) {
                return array('success' => false, 'error' => $response->get_error_message());
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
// GEMINI RESPONSE FUNCTIE - GEMINI 2.5 FLASH
// ============================================================================

function chatbot_get_gemini_response($request) {
    $message              = sanitize_text_field($request->get_param('message'));
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

    // ✅ GEUPGRADED: gemini-2.5-flash
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
        'message' => 'Onverwacht response formaat.',
        'debug'   => $body
    ), 500);
}

// ============================================================================
// PROMPT OPBOUW - ✅ VOLLEDIGE PROMPT TERUG
// ============================================================================

function chatbot_build_prompt($user_message, $history = array()) {
    $context = '';

    if (!empty($history) && is_array($history)) {
        $context        = "GESPREKSGESCHIEDENIS:\n";
        $recent_history = array_slice($history, -3);
        foreach ($recent_history as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $context .= ucfirst($msg['role']) . ': ' . $msg['content'] . "\n";
            }
        }
        $context .= "\n";
    }

    // ✅ VOLLEDIGE PROMPT TERUG
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
// ORDER STATUS FUNCTIE
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
        'pending'    => 'In behandeling',
        'processing' => 'Wordt verwerkt',
        'on-hold'    => 'In wachtstand',
        'completed'  => 'Voltooid',
        'cancelled'  => 'Geannuleerd',
        'refunded'   => 'Terugbetaald',
        'failed'     => 'Mislukt'
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
            'number'          => $order->get_order_number(),
            'status'          => $status,
            'status_name'     => $status_name,
            'date'            => $order->get_date_created()->date('d-m-Y H:i'),
            'total'           => wc_price($order->get_total()),
            'items'           => $items,
            'tracking_number' => $tracking_number ?: null,
            'shipping'        => array('address' => $shipping_address),
            'billing'         => array('address' => $billing_address)
        )
    ), 200);
}
