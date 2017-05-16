<?php
// Create connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 


// check if tables exist
$sql = "SELECT 1 FROM slides LIMIT 1";

if($conn->query($sql) == TRUE)
{
 
}
else
{
 echo("table slides not existing<br>");

 $sql = "CREATE TABLE slides (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `name` TEXT,
 `group` INT,
 `content` TEXT,
 `sort` float DEFAULT NULL,
 )";

 if ($conn->query($sql) === TRUE) {
  echo "Table slides created successfully<br>";
} else {
  echo "Error creating table slides:" . $conn->error;
  echo "<br>";
}
}

$sql = "SELECT 1 FROM views LIMIT 1";

if($conn->query($sql) == TRUE)
{
 
}
else
{
 echo("table views not existing<br>");
 $sql = "CREATE TABLE views (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `name` TEXT,
 `currslide` INT NOT NULL,
 `update` tinyint(1) DEFAULT '0'
 )";

 if ($conn->query($sql) === TRUE) {
  echo "Table views created successfully<br>";
} else {
  echo "Error creating table views:" . $conn->error;
  echo "<br>";
}
}

$sql = "SELECT 1 FROM groups LIMIT 1";

if($conn->query($sql) == TRUE)
{
 
}
else
{
 echo("table groups not existing<br>");
 $sql = "CREATE TABLE groups (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `name` TEXT,
 `parent` INT
 )";

 if ($conn->query($sql) === TRUE) {
  echo "Table groups created successfully<br>";
} else {
  echo "Error creating table groups:" . $conn->error;
  echo "<br>";
}
}

$currpage = $_SERVER['SCRIPT_NAME'];
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="lib/codemirror.css">
  <link rel="stylesheet" href="css/main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="node_modules/sortablejs/Sortable.min.js"></script>
  <script src="lib/codemirror.js"></script>
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">LiveSlide</a>
      </div>
      <ul class="nav navbar-nav">

        <?php 
        if (strpos($currpage, 'index') !== false) 
        {
          echo '<li class="active"><a href="index.php">Control</a></li>';
        }
        else
        {
          echo '<li class=""><a href="index.php">Control</a></li>';
        }
        if (strpos($currpage, 'editor') !== false) 
        {
          echo '<li class="active"><a href="editor.php">Slide Editor</a></li>';
        }
        else
        {
          echo '<li><a href="editor.php">Slide Editor</a></li>';
        }
        if (strpos($currpage, 'liveview') !== false) 
        {
          echo '<li class="active"><a href="liveview.php">Live View</a></li>';
        }
        else
        {
          echo '<li><a href="liveview.php">Live View</a></li>';
        }
        ?>
        
        
      </ul>
    </div>
  </nav>
</head>