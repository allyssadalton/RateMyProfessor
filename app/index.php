<?php
	session_start(); // allows for cookies
?>
<html>
<head>
	<title>UIndy Rate My Professor</title>
</head>

<body>

<h1>It works!</h1>
<?php
	//$_SESSION['v'] = 1; these two lines just demonstrates cookies more
	//echo $_SESSION['v']; 
	header("Location: login.php");
	

?>

</body>
</html>