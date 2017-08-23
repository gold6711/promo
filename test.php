<style>input{width:300px;height:50px;}</style>

<?
if(isset($_POST['phone'])){
//$csv=file_get_contents('test.csv');
file_put_contents('test.csv',"$csv\n$_POST[fio];$_POST[phone];$_POST[address];$_POST[time];");
}
?>
Заказ такси
<form method=post>
ФИО: <input name=fio><br>
Телефон: <input required name=phone><br>
Адрес: <input name=address><br>
Время: <input type=time name=time><br>
<input type=submit>
</form>
