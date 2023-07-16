<?php session_start();
include("home.php");
include("jobpost.php");
include("register.php");
include("login.php");
include("portfolio.php");
?>

<!DOCTYPE HTML>
<html>
<head>
	
	<title>Welcome to PBuild</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="main1.css" />
</head>
<style>
	.logocontainer{
		text-align: left;
		color: #333;

            margin-left: 20px;
	}
	.rating {
    display: inline-block;
}

.rating input {
    display: grid;
}

.rating label {
    font-size: 30px;
    color: #ddd;
    cursor: pointer;
}

.rating label i.fa:before {
    content: '\f005';
}

.rating input:checked ~ label {
    color: #ffcc00;
}

.cabinet{
	display: block;
      margin: 0 auto;
      text-align: center;
      margin-bottom: 50px;
      width: 300px;
}
.rating label:hover,
.rating label:hover ~ label {
    color: #ffcc00;
}

</style>
<body class="is-preload">
<section id="banner">
					<div class="content">
						<h2>Welcome to PBUILD</h2>
						<p></p>
						<a href="#main" class="button scrolly">Start Now</a>
					</div>
				</section>
	<?php
	
		if (isset($_POST['register'])) {
			header('Location: register.php');
			exit();
		}
	?>

	<nav id="nav">
		<ul class="container">
			<li>
				<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    			// User is logged in, show the logged-in content
    				echo "Welcome, " . $_SESSION['fname'] . "!";
					echo '<a href="logout.php">Logout</a>';
    			// Add other content you want to show to authenticated users
				} else {
    			// User is not logged in, show the login button
   				 echo '<a href="login.php">Login</a>';
				echo '<a href="register.php">Register</a>';
				}
				?>
        	</li>
			<li><a href="index.php">Home</a></li>
			<li><a href="jobpost.php">Post a job</a></li>
			<li><a href="portfolio.php">Worker's Portfolio</a></li>
		</ul>
	</nav>

	<article id="top" class="wrapper style1">
		<div class="container">
			<div class="row gtr-200">
				<div class="col-12">
					<header>
						<h2>Welcome to <strong>Pbuild</strong>.</h2>
					</header>
					<p>Looking for a <strong>Skilled Worker?</strong>, we offer high-quality, technology-driven solutions to homeowners, real estate developers, property managers, and individuals in need of construction services.</p>
				</div>
			</div>
		</div>
	</article>

	<article id="work" class="wrapper style2">
		<div class="container">
			<header>
				<h2>Our Services</h2>
				<p></p>
			</header>
			<div class="row aln-center">
				<div class="col-4 col-6-medium col-12-small">
					<section class="box style1">
						<i style="color: rgb(0, 0, 0);"></i>
						<i class="icon featured fa-check-circle"></i>
						<h3>Worker Onboarding</h3>
						<p>Skilled workers will be verified and boarded onto the platform, ensuring they meet the necessary qualifications and possess the required expertise.</p>
					</section>
				</div>
				<div class="col-4 col-6-medium col-12-small">
					<section class="box style1">
						<span class="icon solid featured fa fa-comment"></span>
						<h3>Job Posting</h3>
						<p>Customers can post their construction projects, including details such as project type, location, timeline, and budget</p>
					</section>
				</div>
				<div class="col-4 col-6-medium col-12-small">
					<section class="box style1">
						<span class="icon featured fa fa-user"></span>
						<h3>Ratings and Reviews</h3>
						<p>Customers can rate and provide feedback on the workers' performance,
							helping build a reputation system and ensuring accountability.</p>
					</section>
				</div>
			</div>
			<footer>
				<p></p>
				<center><a href="#portfolio" class="button medium scrolly">Get Started</a></center>
			</footer>
		</div>
	</article>

	<!-- Display jobs posted -->
	<article id="jobs" class="wrapper style3">
		<div class="container">
			<header>
				<h2>Jobs Posted</h2>
			</header>
			<?php
				// Database connection parameters
				$hostname = "localhost";
				$userdb = "root";
				$passdb = "";
				$dbname = "pbuild";

				// Create a connection to the database
				$conn = new mysqli($hostname, $userdb, $passdb, $dbname);

				// Check if the connection was successful
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// Retrieve jobs from the database
				$sql = "SELECT * FROM jobs";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						echo "<div class='job-post'>";
						echo "<p>Job Title: " . $row['title'] . "</p>";
						echo "<p>Description: " . $row['description'] . "</p>";
						echo "<img src = image/cabinet.jpg class = 'cabinet'>";
						echo "<button class='rate-button' onclick='rateJob(" . $row['id'] . ")'>Rate</button>";
						echo "<hr>";
						echo "</div>";
					}
				} else {
					echo "No jobs posted.";
				}
				

				// Close the database connection
				$conn->close();
			?>
		</div>
	</article>

	<!-- Job posting form -->
	<?php
		// Handle form submission
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Retrieve form data
			$title = $_POST['title'];
			$description = $_POST['description'];

			// Database connection parameters
			$hostname = "localhost";
			$userdb = "root";
			$passdb = "";
			$dbname = "pbuild";

			// Create a connection to the database
			$conn = new mysqli($hostname, $userdb, $passdb, $dbname);

			// Check if the connection was successful
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			// Prepare the SQL statement to insert the job into the database
			$stmt = $conn->prepare("INSERT INTO jobs (title, description) VALUES (?, ?)");
			$stmt->bind_param("ss", $title, $description);

			// Execute the SQL statement
			if ($stmt->execute()) {
				echo '<script>alert("Job Posted!");</script>';
			} else {
				echo '<script>alert("Error");</script>';  $conn->error;
			}

			// Close the database connection!
			$stmt->close();
			$conn->close();
		}
	?>

<article id="jobpost" class="wrapper style4">
    <div class="container medium">
        <header>
            <h2>Post a Job</h2>
        </header>
        <form method="post" action="">
            <div class="row gtr-uniform">
                <div class="col-6 col-12-xsmall">
                    <input type="text" name="title" id="title" placeholder="Job Title" required>
                </div>
                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Job Description" rows="4" required></textarea>
                </div>
                <div class="col-12">
                    <label for="rating">Job Rating:</label><br>
                    <div class="rating">
                        <input type="radio" id="rating5" name="rating" value="5">
                        <label for="rating5"><i class="fa fa-star"></i></label>
                        <input type="radio" id="rating4" name="rating" value="4">
                        <label for="rating4"><i class="fa fa-star"></i></label>
                        <input type="radio" id="rating3" name="rating" value="3">
                        <label for="rating3"><i class="fa fa-star"></i></label>
                        <input type="radio" id="rating2" name="rating" value="2">
                        <label for="rating2"><i class="fa fa-star"></i></label>
                        <input type="radio" id="rating1" name="rating" value="1">
                        <label for="rating1"><i class="fa fa-star"></i></label>
                    </div>
                </div>
                <div class="col-12">
                    <ul class="actions">
                        <li><input type="submit" value="Post Job" class="primary" /></li>
                        <li><input type="reset" value="Reset" class="primary" onclick="clearForm();" /></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</article>

<script>
function rateJob(jobId) {
    
    alert("Rate job with ID: " + jobId);
    
}
</script>


</body>
</html>
