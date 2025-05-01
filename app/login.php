<?php
	session_start(); // allows for cookies
	include 'db.php';
	//include 'menu.php';
?>
<html>
<head>
	<title>UIndy Rate My Professor</title>
	<link rel="stylesheet" href="stylesheet.css">
</head>

<body>


<h1>UIndy Rate My Professor</h1>


	
<div class="signlogContainer">
	<div class="SignupSide">
		<h2>SIGNUP</h2>
		<form>
			<label>Email </label><input type="text" name="SignupEmail"><br><br>
			<label>Password </label><input type="password" name="SignupPassword"><br><br>
			<input type="submit" value="Signup">
		</form>
	
<?php
 //new user
	//echo "<div class='signupPHP'>";
	if(isset($_REQUEST['SignupEmail']) && isset($_REQUEST['SignupPassword'])&& !empty($_REQUEST['SignupEmail'])&& !empty($_REQUEST['SignupPassword'])){
		$sql = "INSERT INTO SiteUser(Email, Password) VALUES(?, ?); \n";
		$statement = $pdo -> prepare($sql);
		$statement -> bindValue(1, $_REQUEST['SignupEmail']);
		$statement -> bindValue(2, hash('sha256', $_REQUEST['SignupPassword'])); //hashes password
		try{$ret = $statement -> execute();
		}
		catch(Exception $e){
			echo "Error: ", $e -> getMessage();
		}
		$row = $statement -> rowCount();
		if($row){
			echo "User Created. \n";
			$_SESSION['Email'] = $_REQUEST['SignupEmail']; //double check it should be $_SESSION['SignupEmail']
			header("Location: browseReviews.php"); //hehe ChatGPT did this for me
			exit();
		}
		else{
			//echo "<div class=''>";
			echo "Unable to Create User. \n";
			//echo "</div>";
		}
	}
	echo '</div>';
	//$_SESSION['v'] = 1; these two lines just demonstrates cookies more
	//echo $_SESSION['v']; 
?>
<div class="LoginSide">
	<h2>LOGIN</h2>
	<form>
		<label>Email </label><input type="text" name="LoginEmail"><br><br>
		<label>Password </label><input type="password" name="LoginPassword"><br><br>
		<input type="submit" value="Login">
	</form>
<?php
	//existing user
	if(isset($_REQUEST['LoginEmail']) && isset($_REQUEST['LoginPassword']) && !empty($_REQUEST['LoginEmail'])&& !empty($_REQUEST['LoginPassword'])){
		$sql = "SELECT * FROM SiteUser WHERE Email = ? AND Password = ?; \n";
		$statement = $pdo -> prepare($sql);
		$statement -> bindValue(1, $_REQUEST['LoginEmail']);
		$statement -> bindValue(2, hash('sha256', $_REQUEST['LoginPassword']));
		try{$ret = $statement -> execute();}
		catch(Exception $e){
			echo "Error: ", $e -> getMessage();
		}
		$row = $statement -> fetch(PDO::FETCH_ASSOC);
		if($row){
			echo "Correct login.\n";
			$_SESSION['Email'] = $_REQUEST['LoginEmail']; //double check it should be $_SESSION['LoginEmail']
			header("Location: browseReviews.php"); //hehe ChatGPT did this for me
			exit();
		}
		else{
			echo "Incorrect email or password. Try again.\n";
		}
	}
	echo '</div>';
?>

</div>



</body>
</html>