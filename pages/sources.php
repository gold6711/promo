<div class=row>
<div class='col-md-6'>
<h3>Редактировать источники</h3>
<form method=post class=form-inline action='settings?tab=sources'>
<table class=table>
<thead><th>ID</th><th>Источник</th></thead>
<?
if($_POST['name_source']){
if(is_array($_POST['name_source'])){
foreach(result('sources','id>0','id',9) as $id){
sql("UPDATE `sources` SET `name`='".$_POST['name_source'][$id]."' where id=$id");
}
}
else{
sql("INSERT INTO `sources` (`name`) VALUES ('$_POST[name_source]')");
}
}
$f = mysqli_query($on,"SELECT * FROM `sources`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
echo "<tr><td>$i</td>
<td><input class=form-control name=name_source[$i] value='$fm[name]'></td>
</tr>";
}
?>
<tfoot><th colspan=2><input type=submit value='Изменить' class='btn btn-info'></th></tfoot>
</table>
</form>

<h3>Добавить источник</h3>
<form method=post class=form-inline action='settings?tab=sources'>
<table class=table>
<tr>
<td>Название: <input required class=form-control name=name_source></td>
<td><input type=submit class="btn btn-primary" value=Добавить></td>
</tr>
</table>
</form>
</div>

<div class='col-md-6'>
<h3>Редактировать источники вакансий</h3>
<form method=post class=form-inline action='settings?tab=sources'>
<table class=table>
<thead><th>ID</th><th>Источник</th></thead>
<?
if($_POST['name_vacancy']){
if(is_array($_POST['name_vacancy'])){
foreach(result('vacancy','id>0','id',9) as $id){
sql("UPDATE `vacancy` SET `name`='".$_POST['name_vacancy'][$id]."' where id=$id");
}
}
else{
sql("INSERT INTO `vacancy` (`name`) VALUES ('$_POST[name_vacancy]')");
}
}
$f = mysqli_query($on,"SELECT * FROM `vacancy`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
echo "<tr><td>$i</td>
<td><input class=form-control name=name_vacancy[$i] value='$fm[name]'></td>
</tr>";
}
?>
<tfoot><th colspan=2><input type=submit value='Изменить' class='btn btn-info'></th></tfoot>
</table>
</form>
<h3>Добавить источник вакансии</h3>
<form method=post class=form-inline action='settings?tab=sources'>
<table class=table>
<tr>
<td>Название: <input required class=form-control name=name_vacancy></td>
<td><input type=submit class="btn btn-primary" value=Добавить></td>
</tr>
</table>
</form>
</div>
</div>
