<?php
    require_once("authorize.php");
    require_once('connectvars.php');
    require_once('header.php');

    // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (isset($_POST['submit'])) 
    {
        // Grab the profile data from the POST
        $songId = mysqli_real_escape_string($dbc, trim($_POST['SongId']));
        $name = mysqli_real_escape_string($dbc, trim($_POST['Name']));
        $projectName = mysqli_real_escape_string($dbc, trim($_POST['ProjectName']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['Description']));
        $filePath = mysqli_real_escape_string($dbc, trim($_POST['FilePath']));
        $error = false;

        // Update the profile data in the database
        if (!$error) 
        {
            if (!empty($name) && !empty($projectName) && !empty($description) && !empty($filePath)) 
            {
                $query = "UPDATE song SET Name = '$name', ProjectName = '$projectName', Description = '$description', " .
                    " FilePath = '$filePath' WHERE SongId = '$songId'";
            }
            mysqli_query($dbc, $query);

            // Confirm success with the user
            echo '<p>The Song has been updated</p>';
            echo '<p><a href="./admin.php">&lt;&lt; Back to admin page</a></p>';

            mysqli_close($dbc);
            exit();
            }
            else 
            {
            echo '<p class="error">You did not enter all of the song information.</p>';
            }
    }
    else 
    {
        // Grab the song Id from the url
        $songId = $_GET['SongId'];

        // Grab the profile data from the database using the song id
        $query = "SELECT SongId, Name, ProjectName, Description, FilePath FROM song WHERE  SongId = '$songId'";
        $data = mysqli_query($dbc, $query)
            or die('Error in query');
        $row = mysqli_fetch_array($data);

        // fill in the table information
        if ($row != NULL) 
        {
            $songId = $row['SongId'];
            $name = $row['Name'];
            $projectName = $row['ProjectName'];
            $description = $row['Description'];
            $filePath = $row['FilePath'];
        }
        else 
        {
            echo '<p class="error">There was a problem accessing the song.</p>';
        }
    }
    mysqli_close($dbc);

?>

    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <input type="hidden" id="SongId" name="SongId" value="<?php if (!empty($songId)) echo $songId; ?>" /><br />
            <h3>Song Name:</h3>
            <input type="text" id="Name" class="form-control" name="Name" value="<?php if (!empty($name)) echo $name; ?>" />
            <h3>Project Name:</h3>
            <input type="text" id="ProjectName" class="form-control" name="ProjectName" value="<?php if (!empty($projectName)) echo $projectName; ?>" />
            <h3>Description:</h3>
            <textarea type="textarea" class="form-control" name="Description" rows="5"><?php if (!empty($description)) echo $description; ?></textarea>
            <h3>File Path:</h3>
            <input type="text" id="FilePath" class="form-control" name="FilePath" value="<?php if (!empty($filePath)) echo $filePath; ?>" />
            <br>
            <input class="btn btn-success" type="submit" value="Add" name="submit" />
            <input class="btn" type="reset" value="Clear">
        </div>
        </fieldset>
    </form>
</body> 
</html>
