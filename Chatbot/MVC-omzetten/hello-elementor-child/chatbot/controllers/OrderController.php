<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class OrderController {

    private OrderModel $model;

    public function __construct() {
        $this->model = new OrderModel();
        add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
    }

    public function registerRoutes(): void {
        register_rest_route( 'chatbot/v1', '/order', array(
            'methods'             => 'POST',
            'callback'            => array( $this, 'getOrder' ),
            'permission_callback' => '__return_true',
        ));
    }

    public function getOrder( WP_REST_Request $request ): WP_REST_Response {
        if ( ! $this->model->isWooCommerceActive() ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'WooCommerce niet actief',
            ), 500);
        }

        $order_number = sanitize_text_field( $request->get_param('order_number') );
        $email        = sanitize_email( $request->get_param('email') );

        if ( empty($order_number) || empty($email) ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Ordernummer en e-mail zijn verplicht',
            ), 400);
        }

        $order = $this->model->getOrder( $order_number, $email );

        if ( ! $order ) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Order niet gevonden of e-mail klopt niet',
            ), 404);
        }

        return new WP_REST_Response(array(
            'success' => true,
            'order'   => $order,
        ), 200);
    }
}

new OrderController();
