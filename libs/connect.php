<?php
    $base = dirname(dirname(__FILE__));
    include_once($base.'/config.php');

    function connect($db_server,$db_username,$db_password, $db_name) {
        $mysqli = new mysqli($db_server, $db_username, $db_password, $db_name);
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_error) {
            Echo "Website is unavailable.\n\n";
            echo "Error: Failed to make a MySQL connection, here is why: \n";
            echo "Errno: " . $mysqli->connect_errno . "\n";
            echo "Error: " . $mysqli->connect_error . "\n";
            exit();
        }

        return $mysqli;
    }

    $db = connect($db_server, $db_username, $db_password, $db_name);

    function db_query($q) {
        global $db;

        $out = $db->query($q);

        if (!empty($db->error)) {
            die("database error: " . $db->error );
        }

        return $out;
    }

    function getResource($in) {
        if (is_string($in)) {
            return db_query($in);
        }

        return $in;
    }

    function db_row($in) {

        return getResource($in)->fetch_row();
    }


    function db_assoc($in) {

        return getResource($in)->fetch_assoc();
    }

    function db_all($in) {

        return getResource($in)->fetch_all();
    }

    function db_esc($in) {
        global $db;

        return mysqli_real_escape_string($db, $in);
    }

    function db_num_rows($resource) {

        return $resource->num_rows();
    }

    function db_insert_id() {
        global $db;

        return $db->insert_id;
    }

?>