<?
if(!$_GET['ch']){$_GET['ch']='y';}
if(!$_GET['tab']){$_GET['tab']='users';
//sql("TRUNCATE `sheduler`");
//file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/sheduler?bd');
}
$result=result('worktime',ut('','datetime'),'user',9);
if($result){
$c=array_unique($result);
}

$res[0]=count($c);
$shed=result('sheduler',ut(),'count',9);
if($shed){$res[1]=array_sum($shed);}
$res[2]=count(result('users',ut(),'cdate',9));
$res[3]=count(result('resultats',ut()." and user>0",'id',9));
$res[4]=result('kassa',ut(),'summa',9);
if($res[4]){$res[5]=array_sum($res[4]);}
else{$res[5]=0;}

$res[6]=count(result('resultats',ut(),'id',9));

function project($arr,$t=''){
foreach($arr as $p){
$w[]="<td>".count(result('resultats',ut()." and `project`='$p'$t",'id',9))."</td>";
}
return implode($w);
}
$res[7]=project($arr);
$res[8]=project($arr,' and user>0');


function vacancy($u){
foreach(result('vacancy','id>0','id',9) as $s){
$v[]="<td>".result('users',ut().' and status='.$u.' and source='.$s)."</td>";
}
return implode($v);
}

foreach(array_unique(result('users','status>3','status',9)) as $s){
$v[]="<tr><td>".table('posts','id',$s,'name')."</td>".vacancy($s)."</tr>";
}
$res[9]=implode($v);
include "tabs.php";

?>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="<?=tab('users')?>"><a href="#users"  data-toggle="tab">Сотрудники</a></li>
    <li class="<?=tab('orders')?>"><a href="#orders"  data-toggle="tab">Заявки</a></li>
	
    <li class="<?=tab('vacancy')?>"><a href="#vacancy"  data-toggle="tab">Вакансии</a></li>
  </ul>
  
<div class="tab-content">
<div class="tab-pane fade in<?=tab('users')?>" id="users">
<h3>Статистика по сотрудникам</h3>
<table class=table>
<thead><tr><th>Критерий</th><th>Кол-во</th></tr></thead>
<tbody>
<tr><td>Вышли по факту</td><td><?=$res[0]?></td></tr>
<tr><td>Запланировано по графику</td><td><?=$res[1]?></td></tr>
<tr><td>Cоздано сотрудников</td><td><?=$res[2]?></td></tr>
<tr><td>Начислено сотрудникам</td><td><?=$res[3]?></td></tr>
<tr><td>Выплачено сотрудникам</td><td><?=$res[5].'р ('.count($res[4]).'шт)'?></td></tr>
</tbody>
</table>
</div>
<div class="tab-pane fade in<?=tab('orders')?>" id="orders">
<h3>Статистика по заявкам</h3>
<table class=table>
<thead><th>Критерий</th>
<?foreach($arr as $p){echo "<th>Проект $p</th>";}?>
<th>Итого</th></thead>
<tr><td>Всего добавлено</td><?=$res[7]?><td><?=$res[6]?></td></tr>
<tr><td>Распределено</td><?=$res[8]?><td><?=$res[3]?></td></tr>
<tr><td>Назначено выплат</td><td><?=result('resultats',ut()." and project='КП' and paid=2 and user>0")?></td><td><?=result('resultats',ut()." and project='НД' and user>0")?></td><td><?=result('resultats',ut()." and (project='НД' or (project='КП' and paid=2)) and user>0")?></td></tr>
</table>
</div>

<div class="tab-pane fade in<?=tab('vacancy')?>" id="vacancy">
<h3>Статистика по источникам вакансий</h3>
<table class=table>
<thead><th>Должность</th>
<?foreach(result('vacancy','id>0','name',9) as $s){echo "<th>$s</th>";}?>
</thead>
<?=$res[9]?>
</table>
</div>
</div>