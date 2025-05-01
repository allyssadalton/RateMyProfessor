<?php
	include 'db.php';
?>
<html>
<head>
<link rel="stylesheet" href="stylesheet.css">
</head>

<body>

<div class="Menu">
<?php
    if (isset($_SESSION['Email'])){
        //logged in
        $email = $_SESSION['Email'];
        //echo "Hello, ", $email;
        if ($email == "admin@uindy.edu"){ //menu only admin can use
            echo "<p>";
            echo "<a href='addFaculty.php'>Add a Faculty Member or Department</a>\n";
        }
        else{echo "<p>";}
        
            echo "<a href='professorReviews.php'>Review a Faculty Member</a>\n";
            echo " | ";
            echo "<a href='browseReviews.php'>View Reviews</a>\n";
            echo "</p>";
        
        //logs out and sends you back to login page
        echo "<form action='login.php'> \n"; //https://www.scaler.com/topics/how-to-redirect-from-one-page-to-another-in-html-on-button-click/
        echo "<input type='submit' name='Logout' value='Logout'> \n";
        echo "</form> \n";

        if (isset($_REQUEST['Logout'])){
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }

?> 
</div>
</body>
</html>