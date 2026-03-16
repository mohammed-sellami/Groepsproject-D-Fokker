<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class OrderModel {

    public function isWooCommerceActive(): bool {
        return class_exists('WooCommerce');
    }

    public function getOrder( string $order_number, string $email ): array|false {
        $order_number = str_replace('#', '', $order_number);
        $order = wc_get_order( $order_number );

        if ( ! $order || strtolower( $order->get_billing_email() ) !== strtolower( $email ) ) {
            return false;
        }

        $items = array();
        foreach ( $order->get_items() as $item ) {
            $items[] = array(
                'name'     => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'total'    => wc_price( $item->get_total() ),
            );
        }

        $statuses    = wc_get_order_statuses();
        $status_slug = $order->get_status();

        return array(
            'id'              => $order->get_id(),
            'status'          => $status_slug,
            'status_name'     => $statuses['wc-' . $status_slug] ?? $status_slug,
            'date'            => $order->get_date_created()->date('d-m-Y H:i'),
            'total'           => wc_price( $order->get_total() ),
            'items'           => $items,
            'tracking_number' => $order->get_meta('_tracking_number') ?: null,
            'shipping'        => array( 'address' => $order->get_formatted_shipping_address() ),
            'billing'         => array( 'address' => $order->get_formatted_billing_address() ),
        );
    }
}
