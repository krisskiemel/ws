<?php
    $sname = "localhost";
    $uname = "root";
    $password = "";

    $db_name = "ws";

    $conn = new mysqli($sname, $uname, $password, $db_name);
    
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

?>