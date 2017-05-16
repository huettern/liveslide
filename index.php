<?php
include 'config.php';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);

// Check if slide changed
foreach($_POST as $key=>$value)
{
	if (strpos($key, 'goSlide') !== false) 
	{
		preg_match_all('!\d+!', $key, $matches);
		$id = implode(' ', $matches[0]);
		$stmt = $conn->prepare("UPDATE views SET currslide=?, `update`=1 WHERE id=?");
		$stmt->bind_param('ii', $value, $id);
		$stmt->execute();
	}
}

// Check if new View created
if (array_key_exists('newViewName', $_POST)) {
	$stmt = $conn->prepare("INSERT INTO views (name) VALUES (?)");
	$stmt->bind_param('s', $_POST['newViewName']);
	$stmt->execute();
	header("Location: " . $_SERVER['REQUEST_URI']);
	exit();
}


$stmt = $conn->prepare("SELECT * FROM `slides` ORDER BY `group`, `sort`");
$stmt->execute();
$slideresults = $stmt->get_result();
while($row = $slideresults->fetch_array()) {
	$slideids[] = $row;
}

// get view details
$sql = "SELECT id, name, currslide FROM views";
$viewsresults = $conn->query($sql);



$conn->close();

include 'header.php';
?>

<body>


	<div class="container">

		<div class="row">
			<h1>LiveSlide Control Panel</h1>
		</div>

		<div class="row">
			<h3> Create new View </h3>
			<form action="index.php" method="post">
				<div class="col-lg-6">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Name</span>
						<input type="text" class="form-control" placeholder="View Name" name="newViewName">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">Go!</button>
						</span>
					</div><!-- /input-group -->
				</div><!-- /.col-lg-6 -->
			</form>
		</div><!-- /.row -->

		<div class="row">
			<h3> Slide Control </h3>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Current Slide</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($row = $viewsresults->fetch_assoc())
					{
						echo ('<tr>');
					// id
						echo ('<th scope="row" class="col-md-1">');
						echo $row['id'];
						echo ('</th>');
					// name
						echo ('<td class="col-md-4">');
						echo $row['name'];
						echo ('</td>');
					// current slide
						echo ('<td class="col-md-1">');
						echo ('<form action="index.php" method="post">');
						echo ('<div class="input-group">');
						echo ('<span class="input-group-btn"><button class="btn btn-default">-</button></span>');
						echo ('<input type="text" class="form-control" value="');
						echo ($row['currslide']);
						echo ('" name="goSlide');
						echo ($row['id']);
						echo ('">');
						echo ('<span class="input-group-btn"><button class="btn btn-default">+</button></span>');
						echo ('<span class="input-group-btn"><button class="btn btn-danger" type="submit">GO!</button></span>');
						echo ('</form>');
						echo ('</div>');
						echo ('</td>');

						echo ('</tr>');
					}
					?>
				</tbody>
			</table>

		</div><!-- /.row -->
	</div>

	<script type="text/javascript">

	</script>

</body>
</html>