<?php
    session_start();
	include "db.php";
    include "menu.php";
    if (isset($_SESSION['Email'])){
		//logged in
		$email = $_SESSION['Email'];
        // will send the user back to the browse reviews page if they arent admin
        //i got this code from https://community.hubspot.com/t5/Blog-Website-Page-Publishing/How-to-add-a-time-delay-on-thank-you-page/m-p/375693#M4044
        if ($email != "admin@uindy.edu"){
            echo "You do not have access to this page. You will be directed in a few seconds... \n";
            echo "<script> \n
                window.onload = function(){ \n
                    setTimeout(function(){ \n
                        window.location.href = 'browseReviews.php'; \n
                    }, 2000); \n
                }; \n
            </script> \n";

        }
	}
?>
<html>
<head>
	<title>Add Faculty</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <h1>Add a Faculty Member</h1>
<div class="addFacultyMember">
<form>
    <label for='nameTitle'></label> 
    <select id='nameTitle' name='nameTitle' required>
        <option value=''>Title</option>
        <option value='Dr.'>Dr.</option>
        <option value='Prof.'>Prof.</option>
        <option value='Mr.'>Mr.</option>
        <option value='Mrs.'>Mrs.</option>
        <option value='Miss'>Miss</option>
</select>
    <label for='FN'></label><input type="text" id='FN' placeholder="First Initial" maxlength="1" name="FN" required/>
    <label for='LN'></label><input type="text" id='LN' placeholder="Professor Last Name" name="LN" required/>



<?php 
    $sql = "SELECT Department FROM Professors GROUP BY Department;";
    $statement = $pdo -> prepare($sql);
    try{$ret = $statement -> execute();}
    catch(Exception $e){echo "OOPS! Something is wonky. Try again later.";}
    $rows = $statement -> fetchAll(PDO::FETCH_ASSOC);
    if ($rows){
            echo "<label for='ProfessorDepartment'></label>"; 
            echo "<select id='ProfessorDepartment' name='ProfessorDepartment'>";
                foreach ($rows as $row) {echo "<option value='", $row['Department'], "'>", $row['Department'], "</option>";}
            echo "</select>";
            echo "<div class='button-container'>"; // Add this wrapper
            echo "<button type='submit' name='submit'>Add Professor</button>";
            echo "</div>";
        echo "</form>";
    }

    //if (isset($_REQUEST(['nameTitle'])) && isset($_REQUEST(['FN'])) && isset($_REQUEST(['LN']))){}
    if (!empty($_REQUEST(['nameTitle'])) && !empty($_REQUEST(['FN'])) && !empty($_REQUEST(['LN'])) && !empty($_REQUEST(['ProfessorDepartment']))){
        $title = $_REQUEST(['nameTitle']);
        $first = $_REQUEST(['FN']);
        $last = $_REQUEST(['LN']);
        $department = $_REQUEST(['ProfessorDepartment']);
        $professorName = $title . ' ' . $firstName . '. ' . $lastName; //Deepseek did this for me.
        $sql = "SELECT COUNT (*) FROM Professors WHERE Name = $professorName AND Department = $department;";
        $statement = $pdo -> prepare($sql);
        try{$ret = $statement -> execute();}
        catch(Exception $e){echo "OOPS! Something is wacky. Try again later.";}
        $count = $statement -> fetch(PDO::FETCH_ASSOC);
        
        if ($count != 0){
            echo "<div class='errormessage'>";
            echo "This professor already exists.";
            echo "</div>";
        }
        
        else{ 
            $sql = "INSERT INTO Professors(Name, Department, Rating) VALUES($professorName, $department, 0);";
            $statement = $pdo -> prepare($sql);
            try{$ret = $statement -> execute();}
            catch(Exception $e){echo "OOPS! Something is wacky. Try again later.";}
            echo "Professor added.";
        }
    }
?>
</div>
</body>
</html>
