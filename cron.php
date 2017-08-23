<?
include_once('total.php');



if($_GET['mp3']){include "call.php";
unset($res);
$res=result('records',"mp3!='' and datetime>=".strtotime('-'.$_GET['mp3'].' days'),'mp3',9);
if($res){
foreach($res as $mp3){
$locsip="records/".basename($mp3);
if(!is_file($locsip) or (is_file($locsip) and is_file($mp3) and md5_file($mp3)!=md5($locsip) and strlen(file_get_contents($mp3))>1024)){copy($mp3,$locsip);}
}
}
}


#СБОР ДАННЫХ С ДРУГИХ CRM
if($_GET['period']){
if($_GET['period']=='today'){$_GET['period']=date('Y-m-d');}
$time=time();
//for($a=29;$a>0;$a--){$_GET['period']=date('Y-m-d',strtotime("-$a days"));$time=strtotime("-$a days")



#ЦИКЛ ПО ПРОЕКТАМ
foreach($arr as $k => $v){

$md5=md5(($k.$v).date('hi'));
$json=file_get_contents("http://$k/jquery.php?period=$_GET[period]&token=$md5");

$json=json_decode($json);
for($i=0;$i<count($json->ord);$i++){
$ord=$json->ord;
$rai=$json->raion;
$str=$json->street;
$src=$json->source;
$time=$json->cdate;
if(table('resultats','ord',$ord[$i],'project')!=$v){
sql("INSERT INTO `resultats` (`ord`,`project`,`cdate`,`raion`,`street`,`source`) VALUES (".$ord[$i].",'$v',$time,'$rai[$i]','$str[$i]',".intval($src[$i]).")");
}}
}
//}

}


#РЕГИСТРАЦИЯ ОПЛАЧЕННЫХ ПО КП ЗАЯВОК
if($_GET['paid']){
$json=file_get_contents("http://".domenkp."/jquery.php?paid=$_GET[paid]&token=1");
$json=json_decode($json);
$ord=$json->ord;
if($ord){
foreach($ord as $o){$w[]="ord=$o";}
if($w){
sql("UPDATE `resultats` SET `paid`=2 WHERE (".implode(' or ',$w).") and `project`='КП' and `paid`=0");
}
}

die;
}


#ГЕНЕРАЦИЯ ГЕОТОЧЕК
if(isset($_GET['export'])){
set_time_limit(90);
function geocoder($q){
	$ctx = stream_context_create(array(
    'http' => array('method'=>"GET",
        'timeout' => 4
        )
    )
);

$url='https://geocode-maps.yandex.ru/1.x/?geocode='.urlencode("Россия, Московская область, Москва, $q").'&results=1&format=json&bbox=36.6,54.6~38.6,56.6&rspn=1';
$json=json_decode(file_get_contents($url, 0, $ctx));
return $json->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
}
$f = mysqli_query($on,"SELECT * FROM resultats");
while($fm = mysqli_fetch_array($f)){
$found=geocoder($fm['street']);
if($found){$coords[]='{"type":"Point","coordinates":['.str_replace(' ',',',$found).'],"id":'.$fm['id'].',"ord":'.$fm['ord'].',"user":'.$fm['user'].',"project":"'.$fm['project'].'","cdate":'.$fm['cdate'].'}';}
}

$gj="files/points.geojson";
$crd='['.implode(',',$coords).']';
if($coords and md5($crd)!=md5_file($gj)){
file_put_contents($gj,$crd);
print_r($coords);
}

die;}


#ОПРЕДЕЛЕНИЕ ЮЗЕРА
if($_GET['init']){

function selectuser($rai,$id){
$s=array_shift(result('resultats',"id=$id",'*',6));
if($s['project']=='КП'){
if($s['source']==5){$sts='status=6';}
else{$sts='status=7';}	
}	
else{$sts="status>3 and status!=8";}

$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `users` WHERE `claster`='$rai' and $sts ORDER BY `cdate` DESC");
while($fm = mysqli_fetch_array($f)){
if(worktime($fm['id'])){
$u[]=$fm['id'];
}}
if(!$u){$u=0;}
else{$u=array_shift($u);}
return $u;
}

include "pages/claster.php";


foreach($points as $key => $point) {
	$rai=$pointLocation->pointInPolygon($point, $polygon);
	if($rai){
		$k=$order[$key];
$pts[$k]=$rai;
	sql("UPDATE `resultats` SET `user`=".selectuser($rai,$key).", `claster`='$rai' WHERE id=$key and paid!=1");	
}}
print_r($pts);
die;
}

#АВТОУВОЛЬНЕНИЕ
$f = mysqli_query($on,"SELECT status,cdate,id FROM `users` where `status`>3 and `status`!=8 and `cdate`<".strtotime("-".unactiv." days"));
while($fm = mysqli_fetch_array($f)){
$res=result('worktime',"user=$fm[id] and datetime>".strtotime("-".unactiv." days"));
if(!$res){sql("UPDATE `users` SET `status`=8,`mdate`=".time()." where id=$fm[id]");}}
unset($res);


#АВТОЗАКРЫТИЕ СМЕНЫ
if($_GET['close']){
$us=result('worktime',"datetime>=".strtotime($_GET['close'].' 00:00')." and datetime<=".strtotime($_GET['close'].' 23:59'),'user',9);
foreach($us as $u){
$itg[]=$u;
}
foreach(array_count_values($itg) as $k => $v){
if($v==1){sql("INSERT INTO `worktime` (`user`,`datetime`) VALUES ($k,".strtotime($_GET['close'].' 22:00').")");}
}
}
?>