<?php
require_once $_SERVER['DOCUMENT_ROOT']."/user/user.php";
$usr=new User();
$usr->enter('submit');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/enter.css">
<title>Enter</title>
</head>
<body>
    
    <form name="Enter" method="post" action="enter.php">
        <fieldset>
            <legend>Enter</legend>
            <label for="name">Name:</label>   
            <input id="name" type="text" name="userName"  autofocus> 
            <label for="password">Password:</label>
            <input id="password" name="password" type="password"> 
            <button type="submit" name="submit">Enter</button>
        </fieldset>
    </form>
    <div class="error">
        <?php
            User::getErrors();
         ?>
    </div>
    <a href="http://notes.ru/registration/registr.php">Registration</a>
</body>
</html>
