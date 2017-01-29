<?php
    class Request {
        private $method;
        private $uri;
        private $data;

        public function __construct(array $server) {
            $this->method = $server['REQUEST_METHOD'];
            $this->uri = $server['REQUEST_URI'];
            $this->data = json_decode(file_get_contents('php://input'));
        }

        public function getMethod() {
            return $this->method;
        }

        public function getUri() {
            return $this->uri;
        }

        public function getData() {
            return $this->data;
        }

        public function setMethod(string $value) {
            return $this->method = $value;
        }

        public function setUri(string $value) {
            return $this->uri = $value;
        }

        public function setData(array $value) {
            return $this->data = $value;
        }

        public function isMethod(string $method) {
            return strcasecmp($this->method, $method) === 0;
        }

        public function isAction(string $action) {
            $regex = '/' . str_replace('\:id', '\d+', preg_quote($action, '/')) . '$/i';

            return preg_match($regex, $this->uri) === 1;
        }

        public function getId() {
            preg_match('/\d+$/', $this->uri, $id);

            return $id[0];
        }
    }
?>
