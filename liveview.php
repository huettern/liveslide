<?php
  include 'config.php';
  $conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);

  // Check if a specific view is requested
  if (array_key_exists('id', $_GET)) {
    if (array_key_exists('autoupdate', $_GET)) {
      $stmt = $conn->prepare("SELECT * FROM views WHERE id=?");
      $stmt->bind_param('i', $_GET['id']);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      if($row['update'] == 1) {
        // get content
        $stmt = $conn->prepare("SELECT currslide FROM views WHERE id=?");
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $currslide = $row['currslide'];
        $stmt = $conn->prepare("SELECT content FROM slides WHERE id=?");
        $stmt->bind_param('i', $currslide);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $content = $row['content'];
        // delete update flag
        $stmt = $conn->prepare("UPDATE views SET `update`=0 WHERE id=?");
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        // return new content
        echo $content;
        exit();
      }
      else {
        exit();
      }
    }

    $stmt = $conn->prepare("SELECT currslide FROM views WHERE id=?");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currslide = $row['currslide'];

    // get content
    $stmt = $conn->prepare("SELECT content FROM slides WHERE id=?");
    $stmt->bind_param('i', $currslide);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $content = $row['content'];

    $page = $_SERVER['PHP_SELF'];
    $sec = "3";

    echo ('<html>');
    include 'viewhead.php';
    echo ('<body>');
    echo $content;
    include 'viewscript.php';
    echo ('</body></html>');

    exit();

    $conn->close();
  }

  include 'header.php';

  // Create connection
  $conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);
  $sql = "SELECT id, name, currslide FROM views";
  $results = $conn->query($sql);
  $conn->close();
?>
<body>


<div class="container">
  <h1>Live View</h1>
  <p>Choose the view</p> 
  <div class="list-group">
  <?php
    while($row = $results->fetch_assoc())
    {
      echo ('<a href="liveview.php?id=');
      echo $row['id'];
      echo ('" class="list-group-item">');
      echo $row['name'];
      echo ('<span class="badge">Current slide: ');
      echo $row['currslide'];
      echo (' </span></a>');
    }
  ?>
  </div>
</div>

</body>
</html>