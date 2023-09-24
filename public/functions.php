<?php

use Hashids\Hashids;

class DBControlClass
{
    private $dsn;
    private $db_user;
    private $db_password;

    private $dbh;

    public function __construct()
    {
        $this->dbset();
        $this->connect();
    }

    public function __destruct()
    {
        $this->dsn = null;
    }

    private function dbset()
    {
        require '../vendor/autoload.php';
        \Dotenv\Dotenv::createImmutable(__DIR__)->load();

        $this->dsn = "mysql:dbname=" . $_ENV['DB_NAME'] . ";host=" . $_ENV['DB_HOST'] . ";charset=utf8mb4";
        $this->db_user = $_ENV['DB_USER'];
        $this->db_password = $_ENV['DB_PASSWORD'];
    }

    private function connect()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        );

        try {
            $this->dbh = new PDO($this->dsn, $this->db_user, $this->db_password, $options);
        } catch (PDOException $e) {
            echo "DB接続エラー:". $e->getMessage();
            exit();
            return $e->getMessage();
        }
        return 0;
    }

    public function init()
    {
        try {
            $q = "DROP TABLE IF EXISTS visitor";
            $this->dbh->query($q);
            $q = "DROP TABLE IF EXISTS exhibition";
            $this->dbh->query($q);
            $q = "DROP TABLE IF EXISTS path";
            $this->dbh->query($q);

            $q = "CREATE TABLE IF NOT EXISTS visitor (
                uid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                status INT NOT NULL CHECK (0 <= status AND status <= 2)
                );
            ";
            $this->dbh->query($q);

            $q = "CREATE TABLE IF NOT EXISTS exhibition (
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                category VARCHAR(10) NOT NULL,
                title VARCHAR(255) NOT NULL,
                club_name VARCHAR(255) NOT NULL
            );";
            $this->dbh->query($q);

            $q = "CREATE TABLE IF NOT EXISTS path (
                path_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                uid INT NOT NULL,
                exhibition_id INT NOT NULL,
                datetime DATETIME(3) DEFAULT CURRENT_TIMESTAMP(3),
                flag INT NOT NULL CHECK (0 <= flag AND flag <= 2)
            );";
            $this->dbh->query($q);

            for ($i=0; $i<15000; $i++) {
                $this->dbh->query("INSERT INTO visitor (status) VALUES (0)");
            }

            $q = "INSERT INTO exhibition (category, title, club_name) VALUES ";
            $f = fopen("./exhibition.csv", 'r');
            while ($line = fgetcsv($f)) {
                if ($line[0]=="企画id") {
                    continue;
                }
                $q .= "('{$line[1]}', '{$line[3]}', '{$line[2]}'),";
            }
            $q = rtrim($q, ',');
            $q .= ";";

            $this->dbh->query($q);

            return 0;
        } catch (PDOException $e) {
            return $e->getMessage();
            die();
        }
    }


    public function select($q)
    {
        try {
            $res = $this->dbh->query($q);
            return $res->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
            die();
        }
    }

    public function get_exhibition_num_list()
    {
        try {
            $res = $this->dbh->query("SELECT * FROM exhibition WHERE 1;");
            $number_of_rows = $res->rowCount();
            for ($i = 0; $i < $number_of_rows; $i++) {
            }
        } catch (PDOException $e) {
            return $e->getMessage();
            die();
        }
    }

    public function get_status($uid)
    {
        try {
            $sth = $this->dbh->prepare("SELECT status FROM visitor WHERE uid = :uid");
            $sth->execute(['uid' => $uid]);

            return $sth->fetchAll()[0]['status'];
        } catch (PDOException $e) {
            return $e->getMessage();
            die();
        }
    }

    public function get_previous_exhibition_id($uid) {
        echo $uid;
    }

}


class UidClass extends DBControlClass
{
    public $uid = null;

    public function __construct($uid)
    {
        require '../vendor/autoload.php';
        \Dotenv\Dotenv::createImmutable(__DIR__)->load();
        \Dotenv\Dotenv::createImmutable(__DIR__.'/..')->load();

        if (isset($uid)) {
            $this->uid = $uid;
        }
    }

    public function get_id(): int
    {
        $hashids = new Hashids($_ENV['SALT'], 8, "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ");
        $id = $hashids->decode($this->uid);

        return intval($id[0]);
    }
}
