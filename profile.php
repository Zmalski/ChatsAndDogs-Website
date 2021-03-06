<?php
session_start();
if (!isset($_SESSION['login'])) {  // Check if logged in
	header("Location: badNavigation.html"); // If not logged in, redirect
	exit();
}
session_abort();
?>
<!DOCTYPE html>
<html>

<head>
	<title>My Profile</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/templateStyling.css">
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
</head>

<body>
	<?php
	include 'db_connection.php';
	include 'header.php';
	$username = $_SESSION["username"]; // Display their own profile by default
	$profileowner = true;
	if (isset($_GET["username"])) { // Check if user navigated through clicking a username
		if($username != $_GET["username"]){ // Display user that was navigated by
			$profileowner = false;
			$username = $_GET["username"];
		}
		
	}
	$conn = OpenCon();
	$query = 'SELECT first_name, last_name, age, sex, email, num_posts, COUNT(comment_id) as num_comments, num_pets, profile_image_path FROM users, comments WHERE username = "' . $username . '" AND author = "' . $username . '"';
	$result = $conn->query($query);
	if ($result->num_rows > 0) {
		// output data of each row
		while ($row = $result->fetch_assoc()) {
			$name = $row["first_name"] . " " . $row["last_name"];
			$age = $row["age"];
			if ($row["sex"] == "M")
				$sex = "Male";
			else
				$sex = "Female";
			$email = $row["email"];
			$num_posts = $row["num_posts"];
			$num_comments = $row["num_comments"];
			$num_pets = $row["num_pets"];
			$image_path = $row["profile_image_path"]; 
		}
	}
	?>
	<article class="main">
		<div id="info">
			<img id="ppicture" src="<?php echo $image_path; ?>" alt="Profile Picture">
			<br>
			<p class="attribute">
				<b>Name:</b> <?php echo $name; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Age:</b> <?php echo $age; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Sex:</b> <?php echo $sex; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Email Address:</b> <?php echo $email; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Number of Pets:</b> <?php echo $num_pets; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Number of Posts:</b> <?php echo $num_posts; ?>
			</p>
			<br>
			<p class="attribute">
				<b>Number of Comments:</b> <?php echo $num_comments; ?>
			</p>
		</div>
		<div id="editPane">
			<?php
			if ($profileowner == true)
				echo '<button type="button" id="edit" onclick="location.href = \'editProfile.php\';">
					Edit Profile
					</button>';
			?>

		</div>
		<div id="content">
			<h1 id="pHeading"><?php echo $username; ?></h1>
			<h2 class="cpHeading">My Comments</h2>
			<div class="comments">
				<ul class="commentLog">
					<?php
					$query = 'SELECT content FROM comments WHERE author = "' . $username . '"';
					$result = $conn->query($query);
					if ($result->num_rows > 0) {
						// output data of each row
						while ($row = $result->fetch_assoc()) {
							$content = substr($row["content"], 0, 36);
							if (strlen($row["content"]) > 33)
								$content = $content . "...";
							echo '<li class="commentItem">
							<a href="#"><p class="commentText">' . $content . '
							</p></a>
							</li>';
						}
					} else {
						echo '<div style="display:flex;justify-content:center;align-items:center;"><h2>Nothing to show!<h2></div>';
					}
					?>
				</ul>
			</div>
			<h2 class="cpHeading">My Posts</h2>
			<div class="posts">
				<ul class="postLog">
					<?php
					$query = 'SELECT title, avg_rating FROM posts WHERE author = "' . $username . '"';
					$result = $conn->query($query);
					CloseCon($conn);
					if ($result->num_rows > 0) {
						// output data of each row
						while ($row = $result->fetch_assoc()) {
							switch ($row["avg_rating"]) { // determine star image
								case 0:
									$path = "images/star0.png";
									break;
								case 1:
									$path = "images/star1.png";
									break;
								case 2:
									$path = "images/star2.png";
									break;
								case 3:
									$path = "images/star3.png";
									break;
								case 4:
									$path = "images/star4.png";
									break;
								case 5:
									$path = "images/star5.png";
									break;
								default:
									$path = "images/star5.png";
							}
							$title = substr($row["title"], 0, 36);
							if (strlen($row["title"]) > 33)
								$title = $title . "...";
							echo '<li class="postItem">
							<a href="#"><p class="postText">' . $title . '
							</p></a><img src="' . $path . '" class="postStars">
							</li>';
						}
					} else {
						echo '<div style="display:flex;justify-content:center;align-items:center;"><h2>Nothing to show!<h2></div>'; // TODO: Make this look nicer
					}
					?>
				</ul>
			</div>
		</div>
	</article>

</body>

</html>