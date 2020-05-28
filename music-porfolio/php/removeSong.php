<?php
    require_once("authorize.php");
    session_start();
    require_once("connectvars.php");
    require_once("header.php");
    
    if (isset($_GET['SongId']) && isset($_GET['Name']) && isset($_GET['ProjectName'])
            && isset($_GET['Description']) && isset($_GET['FilePath'])) 
    {
        // Grab the album data from the GET
        $songId = $_GET['SongId'];
        $name = $_GET['Name'];
        $projectName = $_GET['ProjectName'];
        $description = $_GET['Description'];
        $filePath = $_GET['FilePath'];
    } 
    else if (isset($_POST['SongId']) && isset($_POST['Name']) && isset($_POST['ProjectName'])) 
    {
        // Grab the album data from the POST
        $songId = $_POST['SongId'];
        $name = $_POST['Name'];
        $projectName = $_POST['ProjectName'];
        $description = $_POST['Description'];
        $filePath = $_POST['FilePath'];
    }
    else
    {
        echo '<p class="error">Sorry, no album was specified for removal.</p>';
    }
    
    if (isset($_POST['submit'])) 
    {
        if ($_POST['confirm'] == 'Yes') 
        {
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die("Error in connecting to db."); 
    
            // Delete the album data from the database
            $query = "DELETE FROM song WHERE SongId = $songId";
            mysqli_query($dbc, $query)
                    or die("Error in query.");
    
            // Confirm success with the user
            echo '<p>The song ' . $name . ' was successfully removed.';
            echo '<p><a href="./admin.php">&lt;&lt; Back to admin page</a></p>';

            
        }
        else 
        {
        echo '<p class="error">The song was not removed.</p>';
        }
    }
    else if (isset($songId) && isset($name) && isset($projectName)
            && isset($description) && isset($filePath)) 
    {
        echo '<p>Are you sure you want to delete the following song?</p>';
        echo '<p><strong>Name: </strong>' . $name . '<br />';
        echo '<form method="post" action="removeSong.php">';
        echo '<input type="radio" name="confirm" value="Yes" /> Yes ';
        echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br />';
        echo '<input type="submit" value="Submit" name="submit" />';
        echo '<input type="hidden" name="SongId" value="' . $songId . '" />';
        echo '<input type="hidden" name="Name" value="' . $name . '" />';
        echo '<input type="hidden" name="ProjectName" value="' . $projectName . '" />';
        echo '<input type="hidden" name="Description" value="' . $description . '" />';
        echo '<input type="hidden" name="FilePath" value="' . $filePath . '" />';
        echo '</form>';
    }
?>