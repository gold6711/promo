<?php include "auth.php"; ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; Charset=UTF-8">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body>

<!-- ********************  ввод на странице и запись  базу  ************** -->


<div class="form_style">
<textarea name="content_txt" id="contentText" cols="45" rows="5"></textarea>
<button id="FormSubmit">Add record</button>
</div>

<script>
$(document).ready(function() {    
    $("#FormSubmit").click(function () {    

        if($("#contentText").val()==="") 
        {
            alert("Введите текст!");
            return false;
        }

        var myData = "content_txt="+ $("#contentText").val(); //post variables
		
        $.ajax({
            type: "POST", 
            url: "show.php", 
            dataType:"text", 
            data:"content_txt="+ $("#contentText").val(), 
            success:function(){           
            $("#contentText").val(''); 
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); 
            }
        });
    });
});
</script>

</body>
</html>