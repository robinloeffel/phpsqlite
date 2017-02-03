<?php
    class Database {
        private $db;

        public function __construct(string $dbName) {
            $this->db = new SQLite3($dbName);
        }

        public function getNames() {
            $result = $this->db->query('select * from names');
            $resultArray = [];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                array_push($resultArray, $row);
            }

            return json_encode($resultArray);
        }

        public function deleteNames() {
            $this->db->exec('delete from names');
        }

        public function insertName(string $name, string $first, string $second, string $third, string $fourth, string $fifth) {
            $stmt = $this->db->prepare('insert into names values (null, :name, :first, :second, :third, :fourth, :fifth)');
            $stmt->bindValue(':name', $name, SQLITE3_TEXT);
            $stmt->bindValue(':first', $first, SQLITE3_TEXT);
            $stmt->bindValue(':second', $second, SQLITE3_TEXT);
            $stmt->bindValue(':third', $third, SQLITE3_TEXT);
            $stmt->bindValue(':fourth',$fourth, SQLITE3_TEXT);
            $stmt->bindValue(':fifth', $fifth, SQLITE3_TEXT);
            $stmt->execute();
        }

        public function updateNameById(int $id, string $name) {
            $stmt = $this->db->prepare('update names set name = :name where id = :id');
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $stmt->bindValue(':name', $name, SQLITE3_TEXT);
            $stmt->execute();
        }

        public function deleteNameById(int $id) {
            $stmt = $this->db->prepare('delete from names where id = :id');
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
            $stmt->execute();
        }

        public function getLastInsertedRow() {
            $stmt = $this->db->prepare('select * from names where id = :id');
            $stmt->bindValue(':id', $this->db->lastInsertRowid(), SQLITE3_INTEGER);

            return json_encode($stmt->execute()->fetchArray(SQLITE3_ASSOC));
        }
    }
?>
