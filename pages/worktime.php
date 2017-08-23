<?
if(isset($_POST['id'])){
$_POST['id']=intval($_POST['id']);
sql("UPDATE `worktime` SET `work`=".abs($_POST['work']-1)." WHERE `id`=$_POST[id]");}
if(isset($_POST['user']) and isset($_POST['datetime'])){
sql("INSERT INTO `worktime` (`user`,`datetime`) VALUES (".intval($_POST['user']).",".strtotime($_POST['datetime']).")");
}
if(!isset($_GET['ch'])){$_GET['ch']='y';}

if($_GET['user']){$dop[]="`user`=".intval($_GET['user']);}
else{
if($_GET['ch'] or $_GET['ot']){$dop[]=ut($_GET['ch'],'datetime');}
}
if($dop){$dop="WHERE ".implode(' and ',$dop);}
$f = mysqli_query($on,"SELECT * FROM `worktime` $dop ORDER BY `user`,`datetime`");
while($fm = mysqli_fetch_array($f)){
if(users($fm['user'],'status')!=8){
$wr=$fm['work'];
if($wr==1){$b='У';$c='success';}
else{$b='Не у';$c='danger';}
$tr[$wr][]="<tr><td>$fm[id]</td>
<td><a href='worktime?user=$fm[user]'>".users($fm['user'],'name')."</a></td>
<td><a href='worktime?day=".date('Y-m-d',$fm['datetime'])."'>".date('j.m.Y, H:i:s',$fm['datetime'])."</a></td><td>
<form class=form-inline action=worktime method=post>
<input type=hidden name=id value=$fm[id]>
<input type=hidden name=work value=$fm[work]>
<input type=submit value='{$b}читывать' class='btn btn-$c btn-xs'></form></td></tr>";
}
}
include "tabs.php";
if(!$_GET['tab']){$_GET['tab']='work';}

function worktable($tr){if($tr){$tr=implode($tr);}
if(!$_POST){?>
<div class='alert alert-warning'>В данной таблице отображены все звонки сотрудников на телефон учета рабочего времени. <br>
Звонки, произведенные ошибочно можно отметить как не участвующие в подсчете рабочего времени и заработной платы (актуально для 3 и более звонков в день от одного сотрудника, когда смена за этот день не закрыта). Cмена автоматически закрывается в указанное в планировщике заданий время.<br>
Все звонки, вне зависимости от этой метки, будут отображены в графике и карточке сотрудника.</div>
<?}else{?>
<div class='alert alert-success'>Запись успешно добавлена в БД!</div><?}?>
<table class=table>
<thead><th>ID</th><th>Фамилия, Имя</th><th>Дата, время звонка</th><th>Действия</th></thead>
<tbody>
<?=$tr?>
</tbody>
</table>
<?}?>


  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="<?=tab('work')?>"><a href="#work"  data-toggle="tab">В работе</a></li>
    <li class="<?=tab('nowork')?>"><a href="#nowork"  data-toggle="tab">Не учтены</a></li>
	
    <li class="<?=tab('newtime')?>"><a href="#newtime"  data-toggle="tab">Добавить время</a></li>
  </ul>
  
<div class="tab-content">
<div class="tab-pane fade in<?=tab('work')?>" id="work">
<?worktable($tr[0])?>
</div>
<div class="tab-pane fade in<?=tab('nowork')?>" id="nowork">
<?worktable($tr[1])?>
</div>
<div class="tab-pane fade in<?=tab('newtime')?>" id="newtime">
<div class='alert alert-warning'>В этой форме можно вручную добавить время старта или окончания работы сотрудника</div>
<form class=form-inline method=post action=worktime>
Cотрудник:
<select name=user required class=form-control>
<option disabled selected value=''>Выбрать&hellip;</option>
<?=option('users where status>3 and status!=8 order by name')?></select>
<input required type=datetime-local class=form-control name=datetime style='width:14em'>
<input type=submit value='Добавить время' class='btn btn-primary'>
</form>
</div>
</div>