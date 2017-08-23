<table class=table id=tab_audit>
<tr><td colspan=2><a href=# onclick="$('#tab_audit #call').toggleClass('hidden')">Звонки в график</a></td></tr>
<?
$res[0][]=users($_GET['id'],'cdate');
$res[1]=result('worktime',"user=$_GET[id]",'datetime',9);
// and 8!=".users($_GET['id'],'status')."
$res[2]=result('resultats',"(project='НД' or (project='КП' and paid>0)) and raion='".users($_GET['id'],'raion')."' and  user=$_GET[id]",'cdate',9);
$res[3]=result('kassa',"user=$_GET[id]",'cdate',9);
$res[4][]=users($_GET['id'],'mdate');
$txt=array('Дата трудоустройства','Звонок в график','Начисление за заявку','Выплата произведена','Дата увольнения');
$c=count($txt);
if(users($_GET['id'],'mdate')==0){$c=$c-1;}

for($i=0;$i<$c;$i++){
if($res[$i]){

foreach($res[$i] as $r){
if($r>0){$d=date('j.m.Y, H:i',$r);}
else{$d='Никогда';}
if($i==1){$cl=' id=call class=hidden';}else{$cl=null;}
$tr="<tr$cl><td>$txt[$i]</td><td>$d</td></tr>";
$ret[$r]=$tr;
}}}
if($ret){krsort($ret);
echo implode($ret);
}
?>
</table>