<?php
include_once('zadarma.php');

if (isset($_GET['zd_echo'])) exit($_GET['zd_echo']); 

include_once('total.php');


$zd = new \Zadarma_API\Client(KEY, SECRET);


if($_GET['mp3']){
	$res=result('records',"datetime>=".strtotime('-'.$_GET['mp3'].' day')." and mp3='' and call_id!='' limit 99",'call_id',9);
if($res){
foreach($res as $cid){
$ao=$zd->call('/v1/pbx/record/request/', array('lifetime'=>'5184000','pbx_call_id'=>$cid));
$ao = json_decode($ao);
if ($ao->status == 'success') {
$mp3=array_pop($ao->links);
if($mp3){
sql("UPDATE `records` SET `mp3`='$mp3',`isrec`=1 WHERE `call_id`='$cid'");
}}
}
}}


if($_POST['event']=='NOTIFY_END'){
$events=array('','NOTIFY_OUT_START','NOTIFY_START');
$type=intval(array_search($_POST['event'],$events));
if($_POST['called_did']=='external_line'){
$_POST['caller_id']=intval($_POST['caller_id']);
$user=table('users','phone_l',$_POST['caller_id'],'id');
if($user and !result('worktime',"user=$user and datetime>".(time()-3600))){
sql("INSERT INTO `worktime` (user,datetime) VALUES ($user,".strtotime($_POST['call_start']).")");
}}
else{
$source=intval(table('offices','phone',intval($_POST['called_did']),'id'));
if($source){
$bd='interviews';
$to=intval($_POST['caller_id']);
$ord=intval(table($bd,'to',$to,'id'));
$sql="INSERT INTO `records` (`datetime`,`ord`,`type`,`source`,`phone_from`,`phone_to`,`base`,`call_id`) VALUES (".time().",$ord,$type,$source,'$from','$to','$bd','$_POST[pbx_call_id]')";
sql($sql);
if(!$ord and intval($_POST['duration'])>30 and $_POST['disposition']=='answered' and result('records',"mp3!='' and phone_to='$to'")<2)
{sms(truenum($_POST['caller_id']),forsms(sms_address,array_shift(result('offices',"id=$source",'address,phone',6))));}
}
}}

 ?>