<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ChatbotView {

    public function buildPrompt( string $message, array $history = array() ): string {
        $base = "
Je bent een behulpzame Nederlandstalige slaapspecialist van Multimo Beds.
Je geeft advies over matrassen, boxsprings en slaapcomfort.
Gebruik een vriendelijke, duidelijke toon in jij-vorm.
";

        $history_text = '';
        if ( ! empty( $history ) ) {
            foreach ( array_slice( $history, -3 ) as $item ) {
                $role    = strtoupper( $item['role'] ?? 'user' );
                $content = $item['content'] ?? '';
                $history_text .= "{$role}: {$content}\n";
            }
        }

        return $base . "\n\nGESPREK TOT NU TOE:\n" . $history_text .
               "\nNIEUWE VRAAG VAN KLANT:\n" . $message .
               "\n\nGEWENST ANTWOORD:\n" .
               "- Kort en duidelijk.\n" .
               "- Geef waar mogelijk een concreet hardheidsadvies (H1 t/m H4).\n" .
               "- Stel 1 gerichte vervolgvraag als dat helpt.\n";
    }
}
