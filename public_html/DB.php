<?php

$db = new db();

$results = $db->query("SHOW VARIABLES LIKE '%timeout%'", TRUE);
echo "<pre>";
var_dump($results);
echo "</pre>";

$results = $db->query("SET session wait_timeout=28800", FALSE);
// UPDATE - this is also needed
$results = $db->query("SET session interactive_timeout=28800", FALSE);

$results = $db->query("SHOW VARIABLES LIKE '%timeout%'", TRUE);
echo "<pre>";
var_dump($results);
echo "</pre>";


class db {

    public $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli('localhost', 'u261640848_gittservices', '!q8Sr-PYn@u.ghY', 'u261640848_ship_data');
        if (mysqli_connect_errno()) {
            exit();
        }
    }

    public function __destruct() {
        $this->disconnect();
        unset($this->mysqli);
    }

    public function disconnect() {
        $this->mysqli->close();
    }

    function query($q, $resultset) {

        /* create a prepared statement */
        if (!($stmt = $this->mysqli->prepare($q))) {
            echo("Sql Error: " . $q . ' Sql error #: ' . $this->mysqli->errno . ' - ' . $this->mysqli->error);
            return false;
        }

        /* execute query */
        $stmt->execute();

        if ($stmt->errno) {
            echo("Sql Error: " . $q . ' Sql error #: ' . $stmt->errno . ' - ' . $stmt->error);
            return false;
        }
        if ($resultset) {
            $result = $stmt->get_result();
            for ($set = array(); $row = $result->fetch_assoc();) {
            $set[] = $row;
            }
            $stmt->close();
            return $set;
        }
    }
}