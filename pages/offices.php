<h3>Редактировать офисы</h3>
<form method=post class=form-inline action='settings?tab=offices'>
<table class=table>
<thead><th>ID</th><th>Название</th><th>Короткое название</th><th>Телефон</th><th>Адрес</th></thead>
<?
if($_POST['name_office']){
if(is_array($_POST['name_office'])){
foreach(result('offices','id>0','id',9) as $id){
sql("UPDATE `offices` SET `name`='".$_POST['name_office'][$id]."',	`short`='".$_POST['short_office'][$id]."',	`phone`='".$_POST['phone_office'][$id]."',	`address`='".$_POST['address_office'][$id]."' where id=$id");
}
}
else{
sql("INSERT INTO `offices` (`name`,`short`,`phone`,`address`) VALUES ('$_POST[name_office]','$_POST[short_office]','$_POST[phone_office]','$_POST[address_office]')");
}
}
$f = mysqli_query($on,"SELECT * FROM `offices`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
echo "<tr><td>$i</td>
<td><input class=form-control name=name_office[$i] value='$fm[name]'></td>
<td><input class=form-control name=short_office[$i] value='$fm[short]'></td>
<td><input class=form-control name=phone_office[$i] value='$fm[phone]'></td>
<td><input class=form-control name=address_office[$i] value='$fm[address]'></td>
</tr>";
}
?>
<tfoot><th colspan=5><input type=submit value='Изменить' class='btn btn-info'></th></tfoot>
</table>
</form>


<h3>Добавить офис</h3>
<form method=post class=form-inline action='settings?tab=offices'>
<table class=table>
<tr>
<td><input placeholder='Название' required class=form-control name=name_office></td>
<td><input placeholder='Сокращение' required class=form-control name=short_office></td>
<td><input placeholder='Телефон' type=tel required class=form-control name=phone_office></td>
<td><input placeholder='Адрес' required class=form-control name=address_office></td>
<td><input type=submit class="btn btn-primary" value=Добавить></td>
</tr>
</table>
</form>