<?
if($_GET['select'] and $_GET['val']){$dop[]="`$_GET[select]`='$_GET[val]'";}
elseif(!$_GET['search']){$dop[]="`status`!=8";}
if(user('status')>1){$dop[]="`office`=".user('office');}
if($_GET['search']){
	if(is_numeric($_GET['search'])){$dop[]="(`id` LIKE '%$_GET[search]%' or `phone_l`='$_GET[search]')";}
	else{$dop[]="`name` LIKE '%$_GET[search]%'";}}
if($dop){$dop="where ".implode(' and ',$dop);}

$f = mysqli_query($on,"SELECT * FROM `users` $dop order by cdate desc");
while($fm = mysqli_fetch_array($f)){
	if($fm['claster']){$map="<a href='map.php?q=$fm[id]' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-map-marker'></span></a> <a href='map.php?q=$fm[id]&print' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-print'></span></a>";}
	else{$map=null;}
	if(result('worktime',"user=$fm[id] and datetime>".strtotime('today 00:00'))==1){$wt="<span class=\"label label-success\">Работает с ".date('H:i',array_shift(result('worktime',"user=$fm[id] and datetime>".strtotime('today 00:00'),'datetime',9)))."</span>";}
	else{$wt=worktime($fm['id']);if($wt){$wt.=' час.';}}
	if(user('status')<2){$office="<td><a href=users?select=office&val=$fm[office]>".table('offices','id',$fm['office'],'short')."</a></td>";}
	$tr[]="<tr>
		<td><a href=# data-toggle=modal data-target='#myModal' data-whatever=$fm[id]>$fm[id]</a></td><td>$fm[name]</td>
		<td><a href='users?select=status&val=$fm[status]'><span class=\"label label-".table('posts','id',$fm['status'],'color')."\">".table('posts','id',$fm['status'],'name')."</span></a></td>
		$office
		<td>".date('j.m.Y',$fm['cdate'])."</td>
		<td><a href='users?select=claster&val=$fm[claster]'>$fm[claster]</a></td>
		<td>$fm[quantity]</td><td>$wt</td>
		<td><button data-toggle=modal data-target='#myModal' data-tab=1 data-whatever=$fm[id] class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-pencil'></span></button> $map</td>
		</tr>";
}
?>
<button class="btn btn-success" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus"></span> Добавить сотрудника</button>

<a href="users?select=status&val=8" class="btn btn-danger"><span class="glyphicon glyphicon-fire"></span> Уволенные</a>
<a href="worktime" class="btn btn-warning"><span class="glyphicon glyphicon-time"></span> Рабочее время</a>
<table class=table>
<thead><th>ID</th><th>Фамилия, Имя</th><th>Должность</th><?if(user('status')<2){echo '<th>Офис</th>';}?><th>Дата оформления</th><th>Участок</th><th>Кол-во</th><th>Отраб. время</th><th>Действия</th></thead>
<tbody>
<?if($tr){echo implode($tr);}?>
</tbody>
</table>


<script>
var titles=['Редактировать профиль','Маршрутная карта','Печать маршрутной карты'];
var sel='.table tbody tr td:nth-child(8)';
for(i=0;i<titles.length;i++){
	$(sel+' button').attr('title',titles[0]).attr('data-tab','&tab=edit');
	$(sel+' a:nth-child('+(i+1)+')').attr('title',titles[i]).attr('target','_blank');
}
$(document).ready(function(){
	$('[data-target="#myModal"]').click(function(){
		var whatever=$(this).attr('data-whatever');
		if(whatever){tb='';
			if($(this).attr('data-tab')){tb='&tab=edit #edit';}
			pg='profile&edit='+whatever+tb;
			$('.modal-title').text('Профиль сотрудника #'+whatever);
		}
		else{pg="add_user form";
			$('.modal-title').text('Добавить сотрудника');}
		$('.modal-body').load('jquery.php?p='+pg);
		history.pushState(null,null,pg.replace('&','?'));
	})});
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel"></h4>
</div>
<div class="modal-body">

</div>
</div>
</div>
</div>