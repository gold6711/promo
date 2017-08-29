<?php
$connect = mysqli_connect("localhost","root","","1a");
if(isset($_POST["content_txt"]) && strlen($_POST["content_txt"])>0)
{
    $contentToSave = filter_var($_POST["content_txt"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

    if(mysqli_query($connect, "INSERT INTO supply_mat(given_mat) VALUES('".$contentToSave."')"))
    {
//        $my_id = mysqli_insert_id();
//        echo '<li id="item_'.$my_id.'">';
//        echo '<div class="del_wrapper"><a href="#" class="del_button" id="del-'.$my_id.'">';
//        echo '</a></div>';
//        echo $contentToSave.'</li>';
        echo $contentToSave;

//    }else{
//        header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
//        exit();
    }
}