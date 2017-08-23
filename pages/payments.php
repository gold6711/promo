<?
if(isset($_POST['summa']) and isset($_POST['id'])){
$id=intval($_POST['id']);
if(isset($_POST['nuser'])){$_POST['user']=$_POST['nuser'];}
sql("INSERT INTO `kassa` (`oid`,`ord`,`user`,`summa`,`cdate`,`comment`) VALUES ($id,".intval($_POST['ord']).",".intval($_POST['user']).",".intval($_POST['summa']).",".time().",'$_POST[comment]')");
sql("UPDATE `resultats` SET `paid`=1, `user`=".intval($_POST['user'])." WHERE id=$id");
echo "<div class='alert alert-success'>Выплата сотруднику <b>".users($_POST['user'],'name')."</b> в сумму <b>".$_POST['summa']."р</b> произведена</div>";
}
if(!$_GET['tab']){$_GET['tab']='bonus';}?>
<ul class="nav nav-tabs">
  <li class='<?=tab('bonus')?>'><a href="#bonus" data-toggle="tab">Бонусы</a></li>
  <li class='<?=tab('time')?>'><a href="#time" data-toggle="tab">Время</a></li>
  <li class='<?=tab('kassa')?>'><a href="#kassa" data-toggle="tab">Касса</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in<?=tab('bonus')?>" id="bonus">
<?include "bonus.php"?>
</div>
  <div class="tab-pane fade in<?=tab('time')?>" id="time">
              <table id=sortable class="table table-striped">
<thead><th>ID</th><th>Сотрудник</th><th>Должность</th><th>Дата начисления</th><th>Время</th><th>Сумма</th><th>Действия</th></thead>
<tbody>
<?

$users=result('worktime','work=0 order by datetime desc','user',9);
$users=array_unique($users);
foreach($users as $u){
$cls=result('worktime',"user=$u and work=0");
if(($cls % 2)==0){
$time=worktime($u);
if($time){
$p=users($u,'status');
$summa=table('price','post',$p,'hour');
if($summa){$summa=$time*$summa;}else{$summa=null;}
$i++;
if(user('status')<2 or (user('status')>1 and users($u,'office')==user('office'))){
echo "<tr><td>$i</td><td><a href='profile?edit=$u&tab=audit' target='_blank'>".users($u,'name')."</a></td>
<td><a href=kassa><span class=\"label label-".table('posts','id',$p,'color')."\">".table('posts','id',$p,'name')."</span></a></td><td>".date('j.m.Y, H:i',array_shift(result('worktime',"user=$u order by datetime desc",'datetime',9)))."</td>
<td>$time час.</td><td><form method=post action='payments?tab=time'>
<input type=number min=0 required max=9999 value='$summa' class='form-control input-sm' style=width:6em name=summa></td><td><input type=submit value='Выплатить' class='btn btn-sm btn-warning'></form></td></tr>";
}}
}}

?>
</tbody>
</table>

</div>
<div class="tab-pane fade in<?=tab('kassa')?>" id="kassa">
<?include 'kassa.php'?>
</div>