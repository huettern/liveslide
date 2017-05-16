<?php
include 'config.php';
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);

	// Check if new slide created
if (array_key_exists('newSlideName', $_POST)) {
	$stmt = $conn->prepare("SELECT id FROM groups WHERE name=(?)");
	$stmt->bind_param('s', $_POST['grpName']);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_array(MYSQLI_NUM);
	$stmt = $conn->prepare("INSERT INTO slides (`name`, `group`) VALUES (?, ?)");
	$stmt->bind_param('si', $_POST['newSlideName'], $row[0]);
	$stmt->execute();
	header("Location: " . $_SERVER['REQUEST_URI']);
	exit();
}
	// Check if new slide created
if (array_key_exists('newGroupName', $_POST)) {
	$stmt = $conn->prepare("INSERT INTO groups (name) VALUES (?)");
	$stmt->bind_param('s', $_POST['newGroupName']);
	$stmt->execute();
	header("Location: " . $_SERVER['REQUEST_URI']);
	exit();
}
// if order changed
if (array_key_exists('neworder', $_POST) && array_key_exists('group', $_POST)) {
	$sortctr = 1.0;
	$neworder = $_POST['neworder'];
	for ($ctr = 0; $ctr < count($neworder); $ctr++)
	{
		// select entry by id
		$stmt = $conn->prepare("UPDATE `slides` SET `sort`=? WHERE id=?");
		$stmt->bind_param('ii', $sortctr, $neworder[$ctr]);
		$stmt->execute();	
		$sortctr = $sortctr + 1.0;
	}
	header("Location: " . $_SERVER['REQUEST_URI']);
	exit();
}
// if delete
if (array_key_exists('delete', $_POST)) {
	$delid = $_POST['delete'];
	// select entry by id
	$stmt = $conn->prepare("DELETE FROM `slides` WHERE id=?");
	$stmt->bind_param('i', $delid);
	$stmt->execute();
	$page = 'editor.php';
	header('Location: '.$page);
	exit();
}
// check if requested id exists, else exit
if (array_key_exists('id', $_GET)) {
	$conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);
	$stmt = $conn->prepare("SELECT id, name, `group`, content FROM slides WHERE id=?");
	$stmt->bind_param('s', $_GET['id']);
	$stmt->execute();
	$slideresults = $stmt->get_result();
	$sliderow = $slideresults->fetch_assoc();
	if($sliderow == null) {
		$page = 'editor.php';
		header('Location: '.$page);
		exit();
	}
}
// Get list of existing slides
$sql = "SELECT id, name, content FROM slides";
$slideresults = $conn->query($sql);

	// Check if content changed
$contentupdated = false;
if (array_key_exists('content', $_POST)) {
	$stmt = $conn->prepare("UPDATE slides SET content=? WHERE id=?");
	$stmt->bind_param('si', $_POST['content'], $_GET['id']);
	$stmt->execute();
	$contentupdated = true;
}

include 'header.php';
?>
<body>


	<div class="container">
		<div class="row">
			<h1>Editor</h1>
		</div>

		<div class="row">
			<div class="col-md-5">
				<h3> Choose slide </h3>
				<div class="border-0 pre-scrollable">
					<div class="list-group list-group-root well">
						<?php
						// Get list of existing groups
						$sql = "SELECT id, name, parent FROM groups";
						$groupresults = $conn->query($sql);
						while($row = $groupresults->fetch_assoc())
						{
							echo '<a href="#item-grp-', $row['id'], '" class="list-group-item" data-toggle="collapse">';
							echo '<i class="glyphicon glyphicon-chevron-right"></i>', $row['name'], '</a>';
							echo '<div class="list-group collapse" id="item-grp-', $row['id'], '">';

							$val = $row['id'];
							$sql = "SELECT `id`, `name`, `content` FROM `slides` WHERE `group`='$val' ORDER BY `sort` ASC";
							$result = $conn->query($sql);
							while($subrow = $result->fetch_assoc())
							{
								echo '<a href="?id=', $subrow['id'], '" class="list-group-item" id=itm-grp-element-', $subrow['id'],'>', $subrow['name'];
								echo '<span class="badge">id: ', $subrow['id'], '</span></a>';
							}
							echo '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<h3> Create new Group</h3>
				<form method="post">
					<div class="form-group ">
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon1">Name</span>
							<input type="text" class="form-control" placeholder="Group Name" name="newGroupName">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">Go!</button>
							</span>
						</div><!-- /input-group -->
					</div><!-- /.col-lg-6 -->
				</form>
			</div>
			<div class="col-md-4">
				<h3> Create new Slide </h3>
				<form method="post">
					<div class="form-group ">
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon1">Name</span>
							<input type="text" class="form-control" placeholder="Slide Name" name="newSlideName">
							<span class="input-group-addon" style="width:0px; padding-left:0px; padding-right:0px; border:none;"></span>
							<select class="form-control" name="grpName">
								<?php
								// Get list of existing groups
								$sql = "SELECT id, name, parent FROM groups";
								$groupresults = $conn->query($sql);
								while($row = $groupresults->fetch_assoc())
								{
									echo ('<option>');
									echo $row['name'];
									echo ('</option>');
								}
								?>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">Go!</button>
							</span>
						</div><!-- /input-group -->
					</div><!-- /.col-lg-6 -->
				</form>
			</div>
		</div>

		<div class="row">
			<?php
			if (array_key_exists('id', $_GET)) {
				$conn = new mysqli($DB_HOST, $DB_USER, $DB_PWD, $DB_NAME);
				$stmt = $conn->prepare("SELECT id, name, `group`, content FROM slides WHERE id=?");
				$stmt->bind_param('s', $_GET['id']);
				$stmt->execute();
				$slideresults = $stmt->get_result();
				$sliderow = $slideresults->fetch_assoc();
				$stmt = $conn->prepare("SELECT name FROM `groups` WHERE id=?");
				$stmt->bind_param('s', $sliderow['group']);
				$stmt->execute();
				$groupresults = $stmt->get_result();
				$grouprow = $groupresults->fetch_assoc();
				$conn->close();
				echo ('<h3> Edit: ');
				echo $sliderow['id'], ' (', $grouprow['name'],'): ', $sliderow['name'];
				echo ('.html</h3>');

				echo '<form method="post">';
				echo '<div class="form-group">';
				echo '<textarea class="form-control" id="codeEdit" type="text" rows="20" name="content">', $sliderow['content'], '</textarea>';
				echo '</div>';
				echo '<button class="btn btn-default" type="submit">Save</button>';
				if($contentupdated == true) {
					echo "Saved on " . date("h:i:sa");
				}
				echo '</form>';
				echo '<button class="btn btn-danger" type="submit" onclick="deleteClick()" id="btDel">Delete</button>';
			}
			?>

			

		</div>
	</div>
	<script type="text/javascript">
		function getElementsStartsWithId( id ) {
			var children = document.body.getElementsByTagName('*');
			var elements = [], child;
			for (var i = 0, length = children.length; i < length; i++) {
				child = children[i];
				if (child.id.substr(0, id.length) == id)
					elements.push(child);
			}
			return elements;
		}

		var lists = getElementsStartsWithId("item-grp-");
		var sortables = [];
		var options = {
			group: 'share',
			animation: 100
		};
		events = [
		'onSort'
		].forEach(function (name) {
			options[name] = function (evt) {
				console.log({
					'event': name,
					'this': this,
					'item': evt.item,
					'from': evt.from,
					'to': evt.to
				});
				var lis = evt.to.getElementsByTagName("a");
				var grp = parseInt(this.el.id.substr(9));
				var neworder = [];
				for (var i = 0; i < lis.length; i++) {
					    neworder.push(parseInt((lis[i].id.substr(16))));
					}
					$.ajax({
						type: "POST",
						data: {neworder:neworder, group:grp},
						url: "editor.php",
						success: function(msg){
							console.log("success");
						}
					});
				};
			});
		function createSortList(item, index) {
			sortables.push(Sortable.create(item, options));
		}
		lists.forEach(createSortList);

		$(function() {

			$('.list-group-item').on('click', function() {
				$('.glyphicon', this)
				.toggleClass('glyphicon-chevron-right')
				.toggleClass('glyphicon-chevron-down');
			});

		});
		function findGetParameter(parameterName) {
			var result = null,
			tmp = [];
			location.search
			.substr(1)
			.split("&")
			.forEach(function (item) {
				tmp = item.split("=");
				if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
			});
			return result;
		}
		function deleteClick() {
			if(document.getElementById("btDel").innerHTML.localeCompare("Really?") == 0) {
				var deleteId = findGetParameter('id');
				$.ajax({
					type: "POST",
					data: {delete:deleteId},
					url: "editor.php",
					success: function(msg){
						console.log("success");
						location.reload();
					}
				});
			}
			else {
				document.getElementById("btDel").innerHTML = "Really?";	
			}
		}
		var editor = CodeMirror.fromTextArea(codeEdit, {
			lineNumbers: true,
			mode:  "htmlmixed"
		});
	</script>
	<?php $conn->close(); ?>
</body>
</html>