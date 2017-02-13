<?php 
require_once "user/user.php";
$usr=new User();
$usr->checkId();
$name=$usr->getName();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/note.css">
    
    <title>notes</title>
    </head>
    <body>
    <header>
        
    <div class="user">
        <img src="css/img/user.png" alt='U-'>
        <div class="name_user"><?php
            echo $name;
            ?></div>
        <div class="exit" title="sign out">X</div>
    </div>  
        
    <div id="create_note">
        <div class="header_note" contenteditable="true" data-placeholder="Enter a title"></div>
        <div  class="text_note"  contenteditable="true" data-placeholder="Text note..."></div>
        
        <ul role="toolbar" class="toolbar">
            <li class="button_ok">Ok</li>
        </ul>
        </div>
    
     </header>  
    <hr>
   <div id="background"></div>
   <div id="space_for_notes" >
      
       <!-- <div class="example_note">
          <div class="example header_note" contenteditable="true" data-placeholder="Enter a title"></div>
            <div class="example text_note" contenteditable="true" data-placeholder="Text..."></div>
            
         <ul class="example note_toolbar" role="toolbar">
            <li role="button" class="button_save" >save</li>
            <li role="button" class="button_color">color
                <div class="wrp_color">
             <div class="choice_color">
                 <div role="button" class="color white" data-color="rgb(255, 255, 255)"></div>
                 <div role="button" class="color grey" data-color="rgb(157, 156, 156)"></div>
                 <div role="button" class="color red"  data-color="rgb(227, 49, 49)"></div>
                 <div role="button" class="color blue" data-color="rgb(62, 68, 227)"></div>
                 <div role="button" class="color orange" data-color="rgb(222, 121, 20)" ></div>
                 <div role="button" class="color yellow" data-color="rgb(229, 237, 42)"></div>
                 <div role="button" class="color green" data-color="rgb(43, 212, 69)"></div>
                 <div role="button" class="color purple" data-color="(141, 43, 212)"></div>
                </div>
                    </div>
                </li>
            <li role="button" class="buton_picture">image</li>
            <li role="button" class="button_delete">delete</li>
            <li role="button" class="button_ok">ok</li>
            </ul>
            </div>
        -->

        </div>
        
        <script src="js/note.js"></script>
      
    </body>
</html>