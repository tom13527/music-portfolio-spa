<?php
    $userName = "root";
    $password = "";
    $dbName = "music_portfolio";
    $server = "localhost";


    $db = new mysqli($server, $userName, $password, $dbName);

    $sql = "select from tasks where Name = ?";

    $stmt = $db->prepare($sql);

    $stmt->bind_param("i", $_REQUEST["Name"]);

    $stmt->execute();

    $returnVal = $stmt->affected_rows;
    $stmt->close();
    $db->close();
    echo $returnVal;

?>
