<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Parent styles laden
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'hello-elementor-parent',
        get_template_directory_uri() . '/style.css'
    );
});

// MVC chatbot laden
require_once get_stylesheet_directory() . '/chatbot/models/ChatbotModel.php';
require_once get_stylesheet_directory() . '/chatbot/models/OrderModel.php';
require_once get_stylesheet_directory() . '/chatbot/views/ChatbotView.php';
require_once get_stylesheet_directory() . '/chatbot/controllers/ChatbotController.php';
require_once get_stylesheet_directory() . '/chatbot/controllers/OrderController.php';
