<h3>Редактировать должности</h3>
<form method=post class=form-inline action='settings?tab=posts'>
<table class=table>
<?$th="<th>Должность</th><th>Цвет</th><th>Рабочее время</th><th>Короткое название</th>";?>
<thead><th>ID</th><?=$th?></thead>
<?
if($_POST['name_post']){
if(is_array($_POST['name_post'])){
foreach(result('posts','id>0','id',9) as $id){
sql("UPDATE `posts` SET `name`='".$_POST['name_post'][$id]."', `color`='".$_POST['color'][$id]."', `worktime`='".$_POST['worktime'][$id]."', `short`='".$_POST['short'][$id]."' where id=$id");
}
}
else{
sql("INSERT INTO `posts` (`name`,`color`,`worktime`,`short`) VALUES ('$_POST[name_post]','$_POST[color]','$_POST[worktime]','$_POST[short]')");
}
}

function color($s){
$arr=result('posts',"id>0",'color',9);
sort($arr);
foreach(array_unique($arr) as $o){
if($s==$o){$sel='selected';}else{$sel=null;}
$ret[]="<option style=color:white class='label-$o' $sel>$o</option>";
}
if($ret){return implode($ret);}
}


$f = mysqli_query($on,"SELECT * FROM `posts`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
echo "<tr><td>$i</td>
<td><input class=form-control value='$fm[name]' name=name_post[$i]></td>
<td>
<span class='label label-$fm[color]' style='border-radius:50%;' id=label$i>&nbsp;&nbsp;</span>
<select name=color[$i] class=form-control style=width:7em onchange=\"\$('#label$i').removeAttr('class').addClass('label label-'+this.value)\">".color($fm['color'])."</select></td>
<td><input class=form-control value='$fm[worktime]' name=worktime[$i]></td>
<td><input class=form-control value='$fm[short]' name=short[$i]></td></tr>";
}

?>
<tfoot><th colspan=5><input type=submit value='Изменить' class='btn btn-info'></th></tfoot>
</table>
</form>

<h3>Добавить должность</h3>
<form method=post class=form-inline action='settings?tab=posts'>
<table class=table>
<thead><?=$th?></thead>
<tr>
<td><input class=form-control name=name_post></td>
<td><select name=color class=form-control style=width:7em><?=color()?></select></td>
<td><input class=form-control name=worktime></td>
<td><input class=form-control name=short></td>
<td><input type=submit class="btn btn-primary" value=Добавить></td>
</tr>
</table>
</form>