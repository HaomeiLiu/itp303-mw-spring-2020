<?php

require 'config/config.php';

// DB Connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ( $mysqli->connect_errno ) {
	echo $mysqli->connect_error;
	exit();
}

$mysqli->set_charset('utf8');

$sql = "SELECT tracks.name AS track, genres.name AS genre, media_types.name AS media_type, track_id
				FROM tracks
				LEFT JOIN genres
					ON genres.genre_id = tracks.genre_id
				LEFT JOIN media_types
					ON media_types.media_type_id = tracks.media_type_id
				WHERE 1 = 1";

if ( isset($_GET['track_name']) && !empty($_GET['track_name']) ) {
	$sql = $sql . " AND tracks.name LIKE '%" . $_GET['track_name'] . "%'";
}

if ( isset($_GET['genre']) && !empty($_GET['genre']) ) {
	$sql = $sql . " AND tracks.genre_id = " . $_GET['genre'];
}

if ( isset($_GET['media_type']) && !empty($_GET['media_type']) ) {
	$sql = $sql . " AND tracks.media_type_id = " . $_GET['media_type'];
}

$sql = $sql . ";";

$results = $mysqli->query($sql);

if ( $results == false ) {
	echo $mysqli->error;
	exit();
}

// Close DB Connection.
$mysqli->close();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Song Search Results</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<style>
		th, td {
			vertical-align: middle !important;
		}
	</style>
</head>
<body>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">Home</a></li>
		<li class="breadcrumb-item"><a href="search_form.php">Search</a></li>
		<li class="breadcrumb-item active">Results</li>
	</ol>
	<div class="container-fluid">
		<div class="row">
			<h1 class="col-12 mt-4">Song Search Results</h1>
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->
	<div class="container-fluid">
		<div class="row mb-4 mt-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row">
			<div class="col-12">

				Showing <?php echo $results->num_rows; ?> result(s).

			</div> <!-- .col -->
			<div class="col-12">
				<table class="table table-hover table-responsive mt-4">
					<thead>
						<tr>
							<th></th>
							<th>Track</th>
							<th>Genre</th>
							<th>Media Type</th>
						</tr>
					</thead>
					<tbody>

<?php while ( $row = $results->fetch_assoc() ) : ?>
	<tr>
		<td>
			<a href="delete.php" class="btn btn-outline-danger delete-btn">
				Delete
			</a>
		</td>
		<td>
			<a href="details.php?track_id=<?php echo $row['track_id']; ?>">
				<?php echo $row['track']; ?>
			</a>
		</td>
		<td><?php echo $row['genre']; ?></td>
		<td><?php echo $row['media_type']; ?></td>
	</tr>
<?php endwhile; ?>

					</tbody>
				</table>
			</div> <!-- .col -->
		</div> <!-- .row -->
		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="search_form.php" role="button" class="btn btn-primary">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
	</div> <!-- .container-fluid -->

	<script>
		// Some JS to pop up a message before user commits to deleting a track.
		let deleteButtons = document.querySelectorAll(".delete-btn");

		for( let i = 0; i < deleteButtons.length; i++ ) {
			deleteButtons[i].onclick = function() {
				// Show a popup here
			}
		}
	</script>
</body>
</html>