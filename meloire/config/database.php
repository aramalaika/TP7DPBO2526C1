
<?php

class Database {
    private $host = "localhost";
    private $dbname = "meloire_db";
    private $username = "root";
    private $password = "";

    public function connect() {
        try {
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password
            );

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $connection;

        } catch (PDOException $error) {
            die("Oops! Database connection failed: " . $error->getMessage());
        }
    }
}
