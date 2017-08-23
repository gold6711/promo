<h3>Редактировать расценки</h3>
<form method=post class=form-inline action='settings?tab=price'>
<table class=table>
<?$th="<th>Должность</th><th>Стоимость заявки</th><th>Стоимость часа</th><th>Источник</th>";?>
<thead><th>ID</th><?=$th?></thead>
<?
if($_POST['post'] and $_POST['source']){
if(is_array($_POST['post']) and is_array($_POST['source'])){
foreach(result('price','id>0','id',9) as $id){
sql("UPDATE `price` SET `post`=".intval($_POST['post'][$id]).", `source`=".intval($_POST['source'][$id]).", `summa`=".intval($_POST['summa'][$id]).", `hour`=".intval($_POST['hour'][$id])." where id=$id");
}
}
else{
sql("INSERT INTO `price` (`post`,`summa`,`hour`,`source`) VALUES (".intval($_POST['post']).",".intval($_POST['summa']).",".intval($_POST['hour']).",".intval($_POST['source']).")");
}
}
$f = mysqli_query($on,"SELECT * FROM `price`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
echo "<tr><td>$i</td>
<td><select name=post[$i] class=form-control>".option('posts','id','name',$fm['post'])."</select></td>
<td><input class=form-control name=summa[$i] min=0 max=999 type=number value=$fm[summa]></td>
<td><input class=form-control name=hour[$i] min=0 max=999 type=number value=$fm[hour]></td>
<td><select name=source[$i] class=form-control>".option('sources','id','name',$fm['source'])."</select></td></tr>";
}
?>
<tfoot><th colspan=5><input type=submit value='Изменить' class='btn btn-info'></th></tfoot>
</table>
</form>

<h3>Добавить расценку</h3>
<form method=post class=form-inline action='settings?tab=price'>
<table class=table>
<thead><?=$th?></thead>
<tr>
<td><select required name=post class=form-control>
<option disabled selected value=''>Выбрать&hellip;</option>
<?=option('posts')?></select></td>
<td><input required class=form-control name=summa min=0 max=999 type=number></td>
<td><input required class=form-control name=hour min=0 max=999 type=number></td>
<td><select required name=source class=form-control>
<option disabled selected value=''>Выбрать&hellip;</option>
<?=option('sources')?></select></td>
<td><input type=submit class="btn btn-primary" value=Добавить></td>
</tr>
</table>
</form>