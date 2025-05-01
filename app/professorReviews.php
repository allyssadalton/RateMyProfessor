<?php
session_start();
	include "db.php";
    include "menu.php";
	if (!isset($_SESSION['Email'])) {
		echo "<div class='errormessage'>";
            echo "You do not have access to this page. You will be directed in a few seconds... \n";
            echo "<script> \n
                window.onload = function(){ \n
                    setTimeout(function(){ \n
                        window.location.href = 'login.php'; \n
                    }, 2000); \n
                }; \n
            </script> \n";
            echo "</div>";
		exit;
	}
?>
<html>
<head>
	<title>Browse Reviews</title>
	<link rel="stylesheet" href="stylesheet.css">
</head>

<body>
<?php

	if (isset($_SESSION['Email'])){
		$email = $_SESSION['Email'];
	}
	if (!isset($_REQUEST['id'])) {
		header("Location: browseReviews.php");
		exit;
	}
	
	$professorID = $_REQUEST['id'];
	
	
	$sql = "SELECT * FROM Professors WHERE ProfessorID = ?;";
	$statement = $pdo -> prepare($sql);
	$statement -> bindValue(1, $professorID);
	try{$statement -> execute();}
	//catch(Exception $e){echo "Error: ", $e -> getMessage();}
	catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
	$professor = $statement -> fetch(PDO::FETCH_ASSOC);
	
	if (!$professor) {
		echo "Professor not found.";
		exit;
	}
	echo "<div class='leaveReviewInfo'>";
		echo "<h1>", $professor['Name'], "</h1>";
		echo "<h3>Department: ", $professor['Department'], "</h3>";
		echo "<h3>Average Rating: ", $professor['Rating'], "</h3>";
	echo "</div>";
	echo "<hr>";


	if (isset($_REQUEST['Ratings']) && isset($_REQUEST['StudentComment']) && isset($_SESSION['Email'])) {
		$rating = $_REQUEST['Ratings'];
		$comment = $_REQUEST['StudentComment'];
		$createdBy = $_SESSION['Email'];

		$sql = "INSERT INTO RatingsandComments (Ratings, StudentComment, Professor, Department, CreatedBy) VALUES (?, ?, ?, ?, ?);";
		$statement = $pdo -> prepare($sql);
		try{$statement -> execute([$rating, $comment, $professor['Name'], $professor['Department'], $createdBy]);} //ChatGPT showed how to do this
		//catch(Exception $e){echo "Error: ", $e -> getMessage();}
		catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
		
		$sql = "SELECT AVG(Ratings) AS avgRating FROM RatingsandComments WHERE Professor = ? AND Department = ?;";
		$statement = $pdo -> prepare($sql);
		try{
			$statement -> execute([$professor['Name'], $professor['Department']]);
			$result = $statement -> fetch(PDO::FETCH_ASSOC);
			$newAverageRating = round($result['avgRating'], 2); 
		} 
		catch (Exception $e) {echo "OOPS! Something is wonky. Try again later.";}

		// Update the professor's rating in the Professors table
		$sql = "UPDATE Professors SET Rating = ? WHERE ProfessorID = ?;";
		$statement = $pdo -> prepare($sql);
		try {$statement -> execute([$newAverageRating, $professorID]);} 
		catch (Exception $e) {echo "OOPS! Something is wonky. Try again later.";}
			
		
		}
	// Fetch comments
	$sql = "SELECT * FROM RatingsandComments WHERE Professor = ? AND Department = ?;";
	$statement = $pdo -> prepare($sql);
	try{$statement -> execute([$professor['Name'], $professor['Department']]);}
	//catch(Exception $e){echo "Error: ", $e -> getMessage();}
	catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
	$reviews = $statement -> fetchAll(PDO::FETCH_ASSOC);
		
	echo "<div class='reviewsContainer'>";
		echo "<div class='showReviews'>";
			echo "<h2>Reviews</h2>";

			if ($reviews){
				foreach ($reviews as $review){
					echo "<div class='review'>";
					echo "<p><strong>Rating: ", $review['Ratings'], "</strong></p>";
					echo "<p>", $review['StudentComment'], "</p>";
					echo "</div>";
				}
			} 
			else{echo "<p>No reviews yet.<br> Be the first to leave a review for this professor!</p>";}
		echo "</div>";
	?>
	<div class='leaveAReview'>
		<h3>Leave Review</h3>
		<form action="professorReviews.php">
				<input type="hidden" name="id" value="<?php echo $professorID; ?>"> <!-- Chatgpt did this line for me -->
				<label for="rating">Rating (1-5): </label>
				<select name="Ratings">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select><br>
				<br>
				<label for="comment">Leave a Comment</label><br>
				<textarea name="StudentComment"></textarea><br><br>
				<button type="submit">Submit Review</button>
			</form>
	</div>
</div>
</body>
</html>