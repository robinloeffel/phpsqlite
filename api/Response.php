<?php
    class Response {
        public static function send(int $code, array $headers, string $data) {
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }
            http_response_code($code);
            echo $data;
            exit();
        }
    }
?>
