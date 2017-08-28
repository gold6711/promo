<?
foreach(glob("config*.php") as $cfg){include $cfg;}
include "function.php";
$on=@mysqli_connect('127.0.0.1', bdlogin, bdpass,bdname);
mysqli_set_charset($on,cody);//кодировка БД
$GLOBALS['on']=$on;
//session_start(); //  выдает ошибку повт открыт сессии

#АВТОРИЗАЦИЯ 

if(isset($_POST['login']) and isset($_POST['pass'])){
$_POST['login']=mysqli_real_escape_string($on,strip_tags($_POST['login']));
$pass=result('users','status<4 and login="'.$_POST['login'].'"','pass',4);

if(md5($_POST['pass'])==$pass){
$token=$_POST['login'].md5($_POST['pass']).$_SERVER['HTTP_USER_AGENT'].date('W');
$_SESSION['token']=md5($token);
//mysqli_query($on,"UPDATE `users` SET `cdate`=".time()." WHERE `id`=".table('users','pass',md5($_POST['pass']),'id'));
$ip=$_SERVER["HTTP_X_REAL_IP"];
if(!$ip){$ip=$_SERVER["REMOTE_ADDR"];}

if(is_file('files/bad.txt')){
unlink('files/bad.txt');}
header("Location: ./");
}
else{file_put_contents('files/bad.txt',1);}
}



#ВЫХОД
if((!user('id') and basename($_SERVER["SCRIPT_NAME"])!="logon.php" and !isset($_GET['period'])) or $_GET['act']=="exit"){
if($_GET['act']=='exit' and isset($_SESSION['token'])){unset($_SESSION['token']);session_destroy();}
if(basename($_SERVER["SCRIPT_NAME"])!="logon.php"){
//header('Location: http://'.$_SERVER['SERVER_NAME'].'/logon.php');
}}


$arr=array(domenkp=>'КП',domennd=>'НД');
$days=array('','Пн','Вт','Ср','Чт','Пт','Сб','Вс');

$dir_root=$_SERVER["CONTEXT_DOCUMENT_ROOT"];
$userbd='root';
if(!$dir_root){$dir_root=$_SERVER["DOCUMENT_ROOT"];$userbd=bdlogin.' --password='.bdpass;}
$eval="mysqldump --compact --user=$userbd ".bdname." | gzip > $dir_root/files/".bdname.".sql";

?>