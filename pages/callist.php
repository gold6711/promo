<div class="table-responsive">
<?
function duration($m){
if($m){
$locrec='./records/'.basename($m);
if(is_file($locrec)){
$fs=filesize($locrec);
$dur=floor($fs/32000*8);}
else{$dur='?';}
return $dur;}
}



$bd=array('clients'=>'card?id','users'=>'profile?edit','calls'=>'call?id');
if(!$_GET['day']){$_GET['day']=date('Y-m-d');}
$tip=array('','Исходящие','Входящие');
$dop[]='`datetime`>='.strtotime($_GET['day']).' and `datetime`<'.(strtotime($_GET['day'])+86400);

if($_GET['select']){$dop[]="`".$_GET['select']."`=$_GET[val]";}
if(user('status')>1){$dop[]="source=".user('office');}
if($dop){$dop='WHERE '.implode(' AND ',$dop);}
$f = mysqli_query($on,"SELECT * FROM `records` $dop");
while($fm = mysqli_fetch_array($f)){
$b=$fm['base']; $locrec=$player=null;
$locrec='./records/'.basename($fm['mp3']);

if(!is_file($locrec)){$locrec=$fm['mp3'];}

if($locrec){
$player="<audio controls><source src='$locrec' type='audio/mpeg'></audio>";}
$t=$fm['type'];
$dur=duration($fm['mp3']);
if($dur){$dur=$dur.' сек';}
$ord=null;
if($fm['ord']){$ord="<a href='$bd[$b]=$fm[ord]'>$fm[ord]</a>";}
$tr[]="<tr><td>$ord</td>
<td><a href='callist?select=source&val=$fm[source]'>".table('offices','id',$fm['source'],'name')."</a></td>
<td>$tip[$t]</td>
<td>$fm[phone_from], <a href='callist?select=phone_from&val=$fm[phone_from]'>".@users(table('users','phone',$fm['phone_from'],'id'),'name')."</a></td>
<td>$fm[phone_to], ".@table($fm['base'],'id',$fm['ord'],'name')."</td>
<td><span class=hidden>$fm[datetime]</span>
<a href='callist?day=".date('Y-m-d',$fm['datetime'])."'>".date('j.m.Y',$fm['datetime'])."</a>, ".date('H:i',$fm['datetime'])."</td>
<td>$dur</td>
<td id=mp$fm[id]>$player</td></tr>";
}

?>
<form action=callist class=form-inline>
Выберите день: <input name=day style=width:11em class='form-control inline' type=date max='<?=date('Y-m-d')?>' value='<?=$_GET['day']?>' onchange=this.form.submit()></form>
Показано записей: <b><?=count($tr)?></b>
<div class="btn-group">
  <a href=callist class="btn btn-default">Все</a>
  <?for($i=1;$i<count($tip);$i++){echo "<a href='callist?select=type&val=$i&day=$_GET[day]' class='btn btn-default'>$tip[$i]</a>";}?>
</div>
<table class="table" id=sortable>
<thead><th>ID аб.</th><th>Офис</th><th>Тип</th><th>Пользователь</th><th>Абонент</th><th>Дата, время</th><th>Длительн.</th><th>Запись разговора</th></thead>
<tbody>
<?if($tr){echo implode($tr);}?>
</tbody>
</table>
</div>
