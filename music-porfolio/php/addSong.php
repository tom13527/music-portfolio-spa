<?php
  require_once("header.php");
  require_once("authorize.php");
  require_once("connectvars.php")
?>
  <div class="h2color">
    <hr>
    <div class="row">
      <h2>Add Song to Portfolio</h2>
    </div>
    <hr>
  </div>

<?php
    if (isset($_POST['submit'])) 
    {
        // Connect to the database
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
            or die("error in connection to db");

        // Grab the score data from the POST
        $name = mysqli_real_escape_string($dbc, trim($_POST['Name']));
        $projectName = mysqli_real_escape_string($dbc, trim($_POST['ProjectName']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['Description']));
        $filePath = mysqli_real_escape_string($dbc, trim($_POST['FilePath']));

        if (!empty($name) && !empty($projectName)
            && !empty($description) && !empty($filePath)) 
        {
            // Write the data to the database
            $query = "INSERT INTO song (Name, ProjectName, Description, FilePath) VALUES ('$name', '$projectName',
                '$description', '$filePath')";  
            
            mysqli_query($dbc, $query)
                or die("error in query");
        
            // Confirm success with the user
            echo '<p>The new song is added!</p>';
            echo '<p><strong>Name:</strong> ' . $name . '<br />';
            echo '<p><strong>Project:</strong> ' . $projectName . '<br />';
            echo '<p><strong>Description:</strong> ' . $description . '<br />';
            echo '<p><strong>File Path:</strong> ' . $filePath . '<br />';
            echo '<p><a href="./admin.php">&lt;&lt; Back to admin page</a></p>';
        
            // Clear the score data to clear the form
            $name = "";
            $projectName = "";
            $description = "";
            $filePath = "";
        
            mysqli_close($dbc);
        }
        else 
        {
        echo '<p class="error">You forget some stuff!</p>';
        }
    }
?>

  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <h3>Song Name:</h3>
      <input type="text" id="Name" class="form-control" name="Name" value="<?php if (!empty($songName)) echo $songName; ?>" />
      <h3>Project Name:</h3>
      <input type="text" id="ProjectName" class="form-control" name="ProjectName" value="<?php if (!empty($projectName)) echo $projectName; ?>" />
      <h3>Description:</h3>
      <textarea type="textarea" class="form-control" name="Description" rows="5"></textarea>
      <h3>File Path:</h3>
      <input type="text" id="FilePath" class="form-control" name="FilePath" value="<?php if (!empty($filePath)) echo $filePath; ?>" />
      <br>
      <input class="btn btn-success" type="submit" value="Add" name="submit" />
      <input class="btn" type="reset" value="Clear">
    </div>
  </form>
</body> 
</html>
