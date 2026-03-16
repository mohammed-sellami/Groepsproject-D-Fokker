<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ChatbotModel {

    private string $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent';

    public function isConfigured(): bool {
        return defined('GEMINI_API_KEY') && ! empty( GEMINI_API_KEY );
    }

    public function callGemini( string $prompt ): array {
        $url  = $this->api_url . '?key=' . GEMINI_API_KEY;
        $body = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array( 'text' => $prompt )
                    )
                )
            )
        );
        $args = array(
            'method'  => 'POST',
            'headers' => array( 'Content-Type' => 'application/json' ),
            'body'    => wp_json_encode( $body ),
            'timeout' => 30,
        );

        $max_retries = 3;
        $attempt     = 0;
        $last_error  = null;

        while ( $attempt < $max_retries ) {
            $attempt++;
            $response = wp_remote_post( $url, $args );

            if ( is_wp_error( $response ) ) {
                $last_error = $response->get_error_message();
                continue;
            }

            $code = wp_remote_retrieve_response_code( $response );
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( $code === 429 && $attempt < $max_retries ) {
                sleep(2);
                continue;
            }

            return array( 'code' => $code, 'data' => $data );
        }

        return array( 'code' => 500, 'error' => $last_error ?: 'onbekende fout' );
    }
}
