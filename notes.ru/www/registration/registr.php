<?php
    include"../user/user.php";
    $usr=new User();
    $usr->registr('submit');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/reg.css">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>
    </head>
    <body>
        <form name="registration" method="post" action="registr.php">
            <fieldset>
                <legend>Registration</legend>
                <label for="name">Name*:</label>   
                <input id="name" type="text" name="userName" autofocus required > 
                <label for="password">Password*:</label>
                <input id="password" name="password" type="password" required> 
                <label for="password_2">Enter the password again*:</label>
                <input id="password_2" name="password_2" type="password" required>
                <label for="email"> E-mail:</label>
                <input id ="email" name="email" type="email" >
                <button type="submit" name="submit" >Register</button>
            </fieldset>
        </form>
        <div class="errors">
            <?php
                User::getErrors();
            ?> 
        </div>
    </body>
</html>
