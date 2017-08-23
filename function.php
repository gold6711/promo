<?

#ИЗВЛЕЧЕНИЕ ДАННЫХ
function table($t_name,$c_name,$fr,$to){
$f = mysqli_query($GLOBALS['on'],"SELECT $c_name,$to FROM `$t_name` WHERE $c_name='$fr'");
while($fm = mysqli_fetch_array($f)){
return $fm[$to];
}
}


#МЕНЮ
function menu($p=0,$i=0){
if(user('status')>2){$dop="WHERE (`status`=0 OR `status`>=".user('status').") AND `parent`=$p";}
$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `menu` $dop ORDER BY `sequence`");
while($fm = mysqli_fetch_array($f)){
if(!$fm['link'] and $p==0){
$it=result('menu',"(`status`=0 OR `status`>=".user('status').") AND `parent`=$fm[id]");
if($it>1){$item[]="<h4>".$fm['name']."</h4>";}
$item[]=menu($fm['id']);
}
else{
if($_GET['act']==$fm['link']){$act=" class=active";}
else{$act=null;}
if($i){$ico="<span class='glyphicon glyphicon-$fm[icon]'></span>";}
else{$ico=null;}
$item[]="<li id='item_$fm[link]'{$act}> 
<a href='$fm[link]'>$ico$fm[name]</a></li>";
}
}
if($item){
return implode("\n".str_repeat("\t",5),$item)."\n";
}}





#ВЫЧ. ПЕРИОДОВ
function ut($a='',$b='cdate'){
if(!$a){$a=$_GET['ch'];}
$t=strtotime("today 00:00");
$n=strtotime("last Monday");
$nn=strtotime("Monday");
$d=86400;
switch($a){
case 'd':
$ret="`$b`>=$t AND `$b`<".strtotime("tomorrow 00:00");
break;
case 'y':
$ret="`$b`>=".strtotime("yesterday 00:00")." AND `$b`<$t";
break;
case 'pw':
$ret="`$b`>".strtotime('-1 week',$n)." AND `$b`<".strtotime('-1 week',$nn);
break;
case 'w':
$ret="`$b`>$n AND `$b`<$nn";
break;
case 'pp':
$ret="`$b`>".strtotime('first day of this month 00:00')." AND `$b`<".(strtotime('first day of this month 00:00')+($d*15));
break;
case 'p':
$ret="`$b`>".strtotime(date('Y-m-16').'00:00')." AND `$b`<".strtotime('first day of next month 00:00');
break;
case '30':
$ret="`$b`>".strtotime('previous month')." AND `$b`<".time();
}
if($_GET['day']){
$per=strtotime($_GET['day'].' 00:00');
$ret="`$b`>=$per AND `$b`<".(strtotime('tomorrow 00:00',$per));}
if(isset($_GET['ot']) and isset($_GET['po'])){
$ot=$_GET['ot']; $po=$_GET['po'];
//$ot=implode('-',array_reverse(explode('.',$_GET['ot'])));
//$po=implode('-',array_reverse(explode('.',$_GET['po'])));
$ret="`$b`>".strtotime($ot.' 00:00')." AND `$b`<".(strtotime($po.' 23:59')+1);

}
return $ret;
}



#РЕЗУЛЬТАТЫ
function result($bd,$us,$i="id",$c=0){
if($c==0){$co="COUNT(`$i`)";}else{$co=$i;}
if($us){$us=" WHERE $us";}else{$co="*";}
$f = mysqli_query($GLOBALS['on'],"SELECT $co FROM `$bd`$us");
if($c==0 and $f){return implode(mysqli_fetch_row($f));}
if($f){
while($fm = mysqli_fetch_array($f)){
if($c==6){$ret[]=$fm;}
else{$ret[]=$fm[$i];}
}
if(count($f)>0){
if($c==6 or $c==9){return $ret;}
else{return @array_sum($ret);}
}
}
}




#ВЫВОД ОПЦИЙ
function option($bd,$val='id',$nam='name',$s=''){
$f = mysqli_query($GLOBALS['on'],"SELECT $val,$nam FROM $bd");
while($fm = mysqli_fetch_array($f)){
if(!empty($fm[$nam])){
if($s and $s==$fm[$val]){$sel="selected";}
else{$sel=null;}
$ret[]="<option $sel value='$fm[$val]'>$fm[$nam]</option>";
}}
if(count($ret)>0){return implode($ret);}
}



#ТЕКУЩИЙ ЮЗЕР
function user($i){

$up = mysqli_query($GLOBALS['on'],"SELECT * FROM `users`");
if($up){
while($um = mysqli_fetch_array($up)){
$t=md5($um['login'].$um['pass'].$_SERVER['HTTP_USER_AGENT'].date('W'));
if($t==$_SESSION['token'])
{return $um[$i];break;}
}
}

return 0;
}


#ДАННЫЕ ЮЗЕРА
function users($i,$f){
return table('users','id',$i,$f);
}



#SQL-ЗАПРОСЫ
function sql($q){
if(user('readonly')==0){mysqli_query($GLOBALS['on'],$q);}
else{echo '<div class="alert alert-danger">Вы не можете вносить изменения. Обратитесь к администратору</div>';}}


#ПРАВИЛЬНЫЙ НОМЕР
function truenum($n,$p=7){
if($n){
$n=preg_replace('/[^\d\n]+/', '',$n);
if(strlen($n)==10 and substr($n,0,1)==9){
$n=$p.$n;
}
if(strlen($n)==11){
$n=str_replace(8,$p,substr($n,0,1)).substr($n,1,10);
}
return $n;
}}


# АКТИВНАЯ ВКЛАДКА
function tab($n){
if($_GET['tab']==$n){return ' active';}
}


#РЕД. ПОЛЕЙ
function edit($bd,$g,$h=''){
if(isset($_GET['edit'])){
$val=table($bd,'id',$_GET['edit'],$g);
if($h==1){$ret=$val;}
if($h==''){$ret="value='$val'";}
else{
if(!is_numeric($h) and $val){
$e=explode(', ',$val);
for($i=0;$i<count($e);$i++){
$ret[]="<input type=$h value='$e[$i]'>";
}
if($ret){$ret=implode('<br>',$ret);}
}}}
else{if($h and !is_numeric($h)){$ret="<input type=$h>";}}
return $ret;
}


#ЗАРАБОТОК
function wages($u,$p=0){
$s=users($u,'status');
$z=table('price','post',$s,'summa');

$kassa=result('kassa',"user=$u",'summa',9);
if($kassa){$kassa=array_sum($kassa);}
return $kassa;
}




#РАБОЧЕЕ ВРЕМЯ
function workpay($p,$u){
$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `worktime` WHERE user=$u and `work`=0 and DATE_FORMAT(FROM_UNIXTIME(datetime),'%Y-%m-%d') = '$p' ORDER BY datetime");
while($fm = mysqli_fetch_array($f)){
$sek[]=$fm['datetime'];
}
if($sek){return $sek;}
}

function shortdate($u){
$f = mysqli_query($GLOBALS['on'],"SELECT *, DATE_FORMAT( FROM_UNIXTIME( DATETIME ) ,  '%Y-%m-%d' ) AS shortDate FROM worktime where `user`=$u and `work`=0 and ".users($u,'status')."!=8");
while($fm = mysqli_fetch_array($f)){$w[]=$fm['shortDate'];}
if($w){return array_unique($w);}
}


function worktime($u,$m=0){
$sd=shortdate($u);
if($sd){
foreach($sd as $p){
$w=workpay($p,$u);
$s=$w[1]-$w[0];
if($m==1){$sek[]=$s;}
else{$sek[]=round(abs($s)/60/60,1);}}
if($sek){return array_sum($sek);}
}}





#ОТПРАВКА SMS
function sms($to,$txt){
	require_once 'sms.ru.php';
	$smsru = new SMSRU(sms);
	$data = new stdClass();
	$data->to = $to;
	$data->text = $txt;
	$sms = $smsru->send_one($data);
	}




#ШАБЛОНЫ SMS
function forsms($text,$arr){
	$i=0;
	foreach(explode('%',$text) as $r){
		if($i & 1){$ret[]=$arr[$r];}
		else{$ret[]=$r;}
		$i++;
	}
	return implode($ret);
}

?>