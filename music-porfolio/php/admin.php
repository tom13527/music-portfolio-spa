<?php
  require_once('authorize.php');
  require_once('header.php');
?>
  <div class="h2color">
    <hr>
    <h2>Album of the Week - Post Administration</h2>
    <hr>
  </div>

<?php
  require_once('connectvars.php');

  // Connect to the database 
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Retrieve the album data from MySQL
  $query = "SELECT * FROM song";
  $data = mysqli_query($dbc, $query)
      or die("Error in query");

  // Loop through the array of album data, formatting it as HTML 
  echo '<table class="table table-striped">';
  echo '<tr><th>Song</th><th>Project</th><th>';
  while ($row = mysqli_fetch_array($data)) 
  { 
    // Display the album data
    echo '<tr><td><strong>' . $row['Name'] . '</strong></td><td><strong>' 
        . $row['ProjectName']; 
    
    echo '<td><a href="removeSong.php?SongId=' . $row['SongId'] . '&amp;Name=' 
        . $row['Name'] . '&amp;ProjectName=' . $row['ProjectName'] . '&amp;Description=' 
        . $row['Description'] . '&amp;FilePath=' . $row['FilePath'] 
        . '">Remove</a>';
    echo '</td><td><a href="updateSong.php?SongId=' . $row['SongId'] . '&amp;Name=' 
    . $row['Name'] . '&amp;ProjectName=' . $row['ProjectName'] . '&amp;Description=' 
    . $row['Description'] . '&amp;FilePath=' . $row['FilePath'] 
    . '">Update</a>';
    
    echo '</td></tr>';
  }
  echo '</table>';
  echo '<p><a href="../index.html">&lt;&lt; Back to website</a></p>';
  mysqli_close($dbc);
?>

</body> 
</html>