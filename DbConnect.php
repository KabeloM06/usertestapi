<?php
    // Connect to database

    class DbConnect {
        private $server = 'localhost';
        private $dbname = 'userstest';
        private $user = 'root';
        private $password = '';

        public function connect(){
            try {
                $conn = new PDO('mysql:host=localhost;dbname=' . $this->dbname, $this->user, $this->password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            } catch (\Exception $e){
                echo "Database Error: " . $e->getMessage();
            }
        }
    }
?>