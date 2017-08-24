$(document).ready(function() {
    // Добавляем новую запись, когда произошел клик по кнопке
    $("#FormSubmit").click(function (e) {

        e.preventDefault();

        if($("#contentText").val()==="") //simple validation
        {
            alert("Введите текст!");
            return false;
        }

        var myData = "content_txt="+ $("#contentText").val(); //post variables

        jQuery.ajax({
            type: "POST", // HTTP метод  POST или GET
            url: "response.php", //url-адрес, по которому будет отправлен запрос
            dataType:"text", // Тип данных,  которые пришлет сервер в ответ на запрос ,например, HTML, json
            data:myData, //данные, которые будут отправлены на сервер (post переменные)
            success:function(response){
            $("#responds").append(response);
            $("#contentText").val(''); //очищаем текстовое поле после успешной вставки
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //выводим ошибку
            }
        });
    });


});