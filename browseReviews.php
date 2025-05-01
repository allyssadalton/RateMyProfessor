<?php
	session_start();
	include "db.php";
	if (!isset($_SESSION['Email'])) {
		echo "<div class='errormessage'>";
            echo "You do not have access to this page. You will be directed in a few seconds... \n";
            echo "<script> \n
                window.onload = function(){ \n
                    setTimeout(function(){ \n
                        window.location.href = 'login.php'; \n
                    }, 3000); \n
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
    include "menu.php";
	include "searchBar.html";
?>
<h1>Browse Professor and Department Reviews</h1>
<?php
	
?>
<div class="browseReviewsPage">
<div class="ShowsProfessors">
<?php
	if (empty($_REQUEST['SearchRatings'])){
		$sql = "SELECT * FROM Professors; \n";
		$statement = $pdo -> prepare($sql);
		try{$ret = $statement -> execute();}
		//catch(Exception $e){echo "Error: ", $e -> getMessage();}
		catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
		$rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
		if ($rows) {
			// Display all professors
			foreach ($rows as $row) { //ChatGPT gave me this
				echo "<div class='professor'>";
				echo "<h2><a href='professorReviews.php?id=", $row['ProfessorID'], "'>", $row['Name'], "</a></h2>";
				echo "<h3>Department: ", $row['Department'], "</h3>";
				echo "<p>Rating: ", $row['Rating'], "</p>";
				echo "</div>";
			}
		} 
	}
?>

<?php
	if (isset($_REQUEST['SearchRatings']) && isset($_REQUEST['SearchType'])){
		$searchTerm = '%' . $_REQUEST['SearchRatings'] . '%'; //Deepseek helped me here
        $searchType = $_REQUEST['SearchType'];
		if ($searchType == "searchProfessors"){$sql = "SELECT * FROM Professors WHERE Name LIKE ?; \n";}
		else if ($searchType == "searchDepartments"){$sql = "SELECT * FROM Professors WHERE Department LIKE ?;\n";}
			$statement = $pdo -> prepare($sql);
			//$statement -> bindValue(1, $_REQUEST['searchTerm']);
			$statement -> bindValue(1, $searchTerm);
			try{$ret = $statement -> execute();}
			//catch(Exception $e){echo "Error: ", $e -> getMessage();}
			catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
			$rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
			if ($rows) {
				// Display all professors returned
				foreach ($rows as $row) { //ChatGPT gave me this
					echo "<div class='professor'>";
					echo "<h2><a href='professorReviews.php?id=", $row['ProfessorID'], "'>", $row['Name'], "</a></h2>";
					echo "<h3>Department: ", $row['Department'], "</h3>";
					echo "<p>Rating: ", $row['Rating'], "</p>";
					echo "</div>";
				}
			} 
			else{
				if ($searchType == "searchProfessors"){
					echo "<div class='errormessage'>";
					echo "<p>No professors were found matching your search.</p> \n";
					echo "</div>";
				}
			
				else if ($searchType == "searchDepartments"){
					echo "<div class='errormessage'>";
					echo "<p>No departments were found matching your search.</p> \n";
					echo "</div>";
				}
			}
		}
?>
</div>
<div class="ShowsDepartments">
<?php 
	//FIX THIS SQL STATEMENT
	//$sql = "SELECT Department, COUNT(*) AS num_ratings, AVG(Rating) AS avg_rating FROM Professors GROUP BY Department";
	$sql = "SELECT p.Department, COUNT(r.Ratings) AS num_ratings, AVG(r.Ratings) AS avg_rating 
        FROM Professors p 
        LEFT JOIN RatingsandComments r ON p.Name = r.Professor 
        GROUP BY p.Department";
	$statement = $pdo -> prepare($sql);
	try{$ret = $statement -> execute();}
	catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
	$rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
	if ($rows) {
	// Display all professors returned
		foreach ($rows as $row) { //ChatGPT gave me this
			echo "<div class='departmentRatings'>";
			echo "<h3>Department: ", $row['Department'], "</h3>";
			echo "<p>Number of Ratings: ", $row['num_ratings'], "</p>";
			echo "<p>Average Rating: ", round($row['avg_rating'], 2), "</p>";
			echo "</div>";
		}
	} 

?>
</div>
</div>
</body>
</html>