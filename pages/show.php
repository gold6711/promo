<?php
include_once "auth.php"; 

if(isset($_POST["content_txt"]) && strlen($_POST["content_txt"])>0)
{
    $contentToSave = filter_var($_POST["content_txt"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

    if(mysql_query("INSERT INTO supply_mat(given_mat) VALUES('".$contentToSave."')"))
    {
        
        $my_id = mysql_insert_id(); 
        echo '<li id="item_'.$my_id.'">';
        echo '<div class="del_wrapper"><a href="#" class="del_button" id="del-'.$my_id.'">';        
        echo '</a></div>';
        echo $contentToSave.'</li>';       

    }else{        
        header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
        exit();
    }
}


