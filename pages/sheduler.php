<?
if(!$_GET['day']){$_GET['day']=date('Y-m-d');}

function countdays($p){
$w=result('worktime',"datetime>=".strtotime($_GET['day'].' 00:00')." and datetime<=".strtotime($_GET['day'].' 23:59'),'user',9);
foreach (array_unique($w) as $o){
$pst[]=users($o,'status');
}
$count=array_count_values($pst);
return $count[$p];
}



#ТАБЛИЦА ПО ДНЯМ
function days($u,$t=0)
{
$days=result('sheduler',"user=$t",'cdate',9);

for($i=1;$i<=date('t');$i++){
if(is_numeric($t)){$sel=null;
$w=strtotime("+$i days",strtotime('first day of this month 00:00'));
if(strpbrk($u,date('w',$w)+1) or ($days and in_array($w,$days))){
if(users($t,'cdate')>$w and result('worktime',"user=$t and datetime>=$w")){$sel='success';}
else{$sel='danger';}
if($w>time()){$sel='warning';}
elseif(users($t,'cdate')<$w){$sel='default';}
}

if($sel){$sel=" class=$sel";}

$th[]="<td$sel></td>";
}
else{
$th[]="<th>$i</th>";
}}
if($th){
return implode($th);
}
}


#ПОДСЧЕТ РАБОТНИКОВ ПО ДНЯМ
function count_days($p,$m=0){
for($i=1;$i<=date('t');$i++){
$w=strtotime("+$i days",strtotime('first day of this month 00:00'));
unset($c);
if(!$m){unset($s);
$res=result('users',"status=$p",'day',9);
if($res){
foreach($res as $t){
if(strpbrk($t,date('w',$w)+1)){$c[]=$t;
if(isset($_GET['bd'])){$s[]=$w;}}
}}
}
else{
$res=result('worktime',"datetime>=$w and datetime<".strtotime('tomorrow 00:00',$w),'user',9);
if($res){
foreach($res as $ps){
if(users($ps,'status')==$p){$c[]=$ps;}}
if($c){$c=array_unique($c);}
}}
if($c){$c=count($c);}

$th[]="<td>$c</td>";
}
if($th){
return implode($th);
}}


#ВЫВОДИТ ЧАСЫ
function hours($u,$t=0)
{
$wc=explode('-',worktime);
for($i=$wc[0];$i<=$wc[1];$i++){
if(!$t){
$wt=table('posts','id',users($u,'status'),'worktime');
if($u){$s=explode('-',$wt);}
$sel=null;
if($s and $i>=array_shift($s) and $i<array_pop($s)){
$sel=" class=warning";
}

$a=result('worktime',"user=$u and datetime>=".strtotime($_GET['day'].' 00:00')." and datetime<".strtotime('tomorrow 00:00',strtotime($_GET['day'].' 00:00')),'datetime',9);
if($a){$w=null;
foreach($a as $b){
if(date('G',$b)==$i){
$w.="<span class='label label-info'>".date('H:i',$b)."</span> ";
}
}

if($i>=date('G',min($a)) and $i<=date('G',max($a))){
$sel=" class=success";
}
}
$th[]="<td$sel>$w</td>";
}
else{
$th[]="<th>$i:00</th>";
}}
if($th){return implode($th);}
}

if(!$_GET['tab']){$_GET['tab']='hours';}

?>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li class="<?=tab('hours')?>"><a href="#hours"  data-toggle="tab">По часам</a></li>
    <li class="<?=tab('days')?>"><a href="#days"  data-toggle="tab">По дням</a></li>
  </ul>
  
<div class="tab-content">
<div class="tab-pane fade in<?=tab('hours')?>" id="hours">

<h3>По часам</h3>
<form action=sheduler class=form-inline>
Выберите день: 
<input name=day type=date class=form-control max='<?=date('Y-m-d')?>' value='<?=$_GET['day']?>'>
<label><input type=checkbox name=all <?if($_GET['all']){echo 'checked';}?> class=form-inline> Показывать неактивных</label>
<input type=submit value='Показать' class="btn btn-sm btn-primary">
</form>
<table class='table table-bordered table-hover' id=ishour>
<thead><th>Сотрудник</th>
<?=hours(0,1)?>
</thead>
<tbody>
<?
function for_hours($p){
if(user('status')>1){$dop[]='office='.user('office');}
else{$dop[]="status=$p";}
if($dop){$dop="where ".implode(' and ',$dop);}
$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `users` $dop");
while($fm = mysqli_fetch_array($f)){
if((isset($_GET['all']) and strpbrk($fm['day'],date('w',$_GET['day'])+1)) or (!isset($_GET['all']) and result('worktime',"user=$fm[id] and datetime>=".strtotime($_GET['day'].' 00:00')))){
$ret[]="<tr><td><a href=profile?edit=$fm[id]>$fm[name]</a></td>".hours($fm['id'])."</tr>";
}
}
if($ret){
return implode($ret);
}}
foreach(result('posts','id>3 and id<8','*',6) as $p){
$h=for_hours($p['id']);
if($h){
echo "<tr class=$p[color]><th colspan=".count($th).">$p[name] (".countdays($p['id']).")</th></tr>$h";
}}
?>
</tbody>
<tfoot><th>ИТОГО:</th><?
$wc=explode('-',worktime);
for($i=date('ymd',strtotime($_GET['day'])).$wc[0];$i<=date('ymd',strtotime($_GET['day'])).$wc[1];$i++){
$tf[]="<th>".intval(result('worktime',"DATE_FORMAT(FROM_UNIXTIME(datetime),'%y%m%d%k') = '$i'")).'</th>';
}
echo implode($tf)
?></tfoot>
</table></div>
<div class="tab-pane fade in<?=tab('days')?>" id="days">
<h3>По дням</h3>
<table class='table table-bordered table-hover'>
<thead><th>Сотрудник</th>
<?=days(0,'th')?>
</thead>
<tbody>
<?
function for_days($p,$t=0){
if(user('status')>1){$dop[]='office='.user('office');}
else{$dop[]="status=$p";}
if($dop){$dop="where ".implode(' and ',$dop);}
$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `users` $dop");
while($fm = mysqli_fetch_array($f)){
$ret[]="<tr><td>$fm[name]</td>".days($fm['day'],$fm['id'])."</tr>";
}
if($ret){
if($t){return count($ret);}
else{return implode($ret);}
}
}



foreach(result('posts','id>3 and id<8','*',6) as $p){
$h=for_days($p['id']);
$i=$p['id'];
if($h){
echo "<tr class=$p[color]><th colspan=".(date('t')+1).">$p[name]</th></tr>$h<tr><th>Итого по графику:</th>".count_days($i)."</tr><tr><th>Итого по факту:</th>".count_days($i,1)."</tr>";
}}
?>
</tbody>
</table></div>
</div>
<script>
$(document).ready(function(){

)});
</script>