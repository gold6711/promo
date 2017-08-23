<?

$arr=array_flip($arr);
if($_GET['select'] and $_GET['val']){$dop[]="`$_GET[select]`='$_GET[val]'";}
else{

if(!isset($_GET['ch'])){$_GET['ch'];}
if($_GET['ch']){
if(isset($_GET['wait'])){$dop[]="`project`='КП' AND `paid`=0 AND cdate<".strtotime('today 00:01');}
else{$dop[]=ut();
$dop[]="(`project`='НД' OR (`project`='КП' AND `paid`=2))";}}
else{
if(isset($_GET['lock'])){$dop[]="`user`>0";}
else{$dop[]='user='.intval($_GET['user']);}}
}

if($dop){$dop='where '.implode(' and ',$dop);}

if(isset($_GET['all'])){unset($dop);}

$f = mysqli_query($on,"SELECT * FROM `resultats` $dop order by id desc");
while($fm = mysqli_fetch_array($f)){
if($fm['user']){
$summa=table('price','post',users($fm['user'],'status'),'summa');}else{$summa=null;}

if((isset($_GET['all']) or (!isset($_GET['all']) and users($fm['user'],'status')!=8)) and 
(($_GET['paid']!=1 and !result('kassa',"oid=$fm[id]")) or ($_GET['paid']==1 and result('kassa',"oid=$fm[id]")))){

if(result('kassa',"oid=$fm[id]")){
$form=table('kassa','oid',$fm['id'],'summa');
$forms="<span class='label label-success'>Оплачено</span> <span class='label label-info'>".date('j.m.y H:i',table('kassa','oid',$fm['id'],'cdate'))."</span>";}
else{$form="<button type=button id=lock class='btn btn-xs btn-info'>Закрепить</button> <button disabled data-toggle=modal data-target='#myModal' data-whatever='$fm[id]-$fm[user]-$fm[ord]-$summa' class='btn btn-warning btn-xs'>Выплатить</button>";}

if($fm['user']){
//$user="<a href=payments?user=$fm[user]>".users($fm['user'],'name')."</a>";
$p=users($fm['user'],'status');
$post="<a href=payments?post=$p><span class=\"label label-".table('posts','id',$p,'color')."\">".table('posts','id',$p,'name')."</span></a>";
}
else{$post=null;}
$user="<select id=$fm[user] oid=$fm[id]></select>";
if($fm['claster']){$raion="<a href='payments?select=claster&val=$fm[claster]'>$fm[claster]</a>";}
else{$raion=null;}
if($fm['project']){$project="<a href='payments?select=project&val=$fm[project]'>$fm[project]</a>";}
else{$project=null;}
$p=$fm['project'];
if((user('status')<2) or 
	(user('status')>1 and ($fm['user'] and users($fm['user'],'office')==user('office')))){
$tr[]="<tr id=$fm[id]>
<td><a href='http://$arr[$p]/card?id=$fm[ord]' target=_blank>$fm[ord]</a></td>
<td>$project</td>
<td>$user</td>
<td>$post</td>
<td>".date('j.m.Y, H:i',$fm['cdate'])."</td>
<td>$raion</td>
<td>$form</td>
</tr>";
}
}}
$nostatus=true;
include "tabs.php"
?>
<div class="table-responsive">
            <table id=sortable class="table table-striped">
<thead><th>ID заявки</th><th>Проект</th><th width=200>Сотрудник</th><th>Должность</th><th>Дата начисления</th><th>Участок</th><th width=165>Действия</th></thead>
<tbody>
<?if($tr){echo implode($tr);}?>
</tbody>
</table></div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
<form method=post action='payments?tab=kassa' class=form-inline>
<input type=hidden name=id>
<input type=hidden name=ord>
<table class='table table-condensed'>
<tr><td>Сотрудник:</td><td><select name=user class='form-control'><?=option('users where status!=8 and status>3 order by status,name')?></select></td></tr>
<tr><td>Комментарий:</td><td><textarea class=form-control name=comment></textarea></td></tr>
<tr><td>Сумма:</td><td><input name=summa style=width:6em required class='form-control input-sm' type=number min=0 max=9999></td></tr>
<tr><td colspan=2><input type=submit value='Выплатить' class='btn btn-sm btn-warning'>
</td></tr></table></form>

      </div>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
localStorage.removeItem('user');
var sel=$('tbody tr td:nth-child(3) select');
sel.addClass('form-control input-sm');
sel.html('<option value=0 disabled selected>Не выбран</option>'+$('.modal-body [name=user]').html());
sel.change(function(){
$('tbody #'+$(this).attr('oid')+' td:nth-child(6)').load('jquery.php?uc='+$(this).val());
$('tbody #'+$(this).attr('oid')+' button:first-child').val($(this).attr('oid')+'-'+$(this).val());
$('tbody #'+$(this).attr('oid')+' td:last-child button').removeAttr('disabled');
localStorage.setItem('user',$(this).val());
});

$('table tr #lock').click(function(){$.get('jquery.php?lock='+$(this).val());});


$('[data-target="#myModal"]').click(function(){
var w=($(this).attr('data-whatever')).split('-');
$('.modal-body tr:first-child td:nth-child(2)').html('<input type=hidden name=nuser value='+localStorage.getItem('user')+'>'+$('tbody #'+w[0]+' select option:selected').text());
$('.modal-title').text('Добавить выплату по заявке #'+w[2]);
a=['id','user','ord','summa'];
for(i=0;i<a.length;i++){$('.modal-body [name='+a[i]+']').val(w[i]);}

});

function selectuser(id){
$('tbody tr td:nth-child(3) #'+id+' option').each(function(){
if($(this).val()==id){$(this).attr('selected','selected');}
});
}
sel.each(function(){if($(this).attr('id')>0){selectuser($(this).attr('id'));}});

});

</script>
