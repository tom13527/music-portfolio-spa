<?php

    header('Content-type: application/xml');

    $urlParameter = $_SERVER['QUERY_STRING'];

    $queryStringParam = str_replace('%20', ' ', $urlParameter);

    $db = new mysqli("localhost", "root", "", "music_portfolio");

    $sql = "select * from song where projectname = '$queryStringParam'";

    $stmt = $db->prepare($sql);

    $stmt->bind_result($SongId, $Name, $ProjectName, $Description, $FilePath);

    $stmt->execute();

    $project = "<projects>";
    while($stmt->fetch()) {
        $project .= "<project>";
            $project .="<SongId>$SongId</SongId>";
            $project .="<Name>$Name</Name>";
            $project .="<ProjectName>$ProjectName</ProjectName>";
            $project .="<Description>$Description</Description>";
            $project .="<FilePath>$FilePath</FilePath>";
        $project .= "</project>";
    }
    $project .="</projects>";



    echo $project;

?>


