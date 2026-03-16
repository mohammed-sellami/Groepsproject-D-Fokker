<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ChatbotController {

    private ChatbotModel $model;
    private ChatbotView  $view;

    public function __construct() {
        $this->model = new ChatbotModel();
        $this->view  = new ChatbotView();

        add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
    }

    public function registerRoutes(): void {
        register_rest_route( 'chatbot/v1', '/test', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'healthCheck' ),
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'chatbot/v1', '/test-gemini', array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'testGemini' ),
            'permission_callback' => '__return_true',
        ));

        register_rest_route( 'chatbot/v1', '/ai-chat', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'aiChat' ),
            'permission_callback' => '__return_true',
        ));
    }

    public function healthCheck(): WP_REST_Response {
        return new WP_REST_Response(array(
            'success'            => true,
            'woocommerce_active' => class_exists('WooCommerce'),
            'gemini_configured'  => $this->model->isConfigured(),
        ), 200);
    }

    public function testGemini(): WP_REST_Response {
        if ( ! $this->model->isConfigured() ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'GEMINI_API_KEY ontbreekt in wp-config.php',
            ), 500);
        }

        $result = $this->model->callGemini('Zeg kort hallo in het Nederlands.');

        return new WP_REST_Response(array(
            'success' => $result['code'] === 200,
            'status'  => $result['code'],
            'data'    => $result['data'] ?? null,
        ), $result['code']);
    }

    public function aiChat( WP_REST_Request $request ): WP_REST_Response {
        if ( ! $this->model->isConfigured() ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'GEMINI_API_KEY ontbreekt in wp-config.php',
            ), 500);
        }

        $message = sanitize_textarea_field( $request->get_param('message') );
        $history = $request->get_param('history') ?? array();

        if ( empty( $message ) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Bericht mag niet leeg zijn',
            ), 400);
        }

        $prompt = $this->view->buildPrompt( $message, is_array($history) ? $history : array() );
        $result = $this->model->callGemini( $prompt );

        if ( $result['code'] !== 200 ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Gemini fout: ' . ($result['error'] ?? $result['code']),
            ), $result['code']);
        }

        $text = $result['data']['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return new WP_REST_Response(array(
            'success' => true,
            'message' => $text,
        ), 200);
    }
}

new ChatbotController();
