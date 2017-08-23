<?
include_once('total.php');

//вывод улиц в списке
if($_GET['street']){
if(isset($_GET['raion'])){
$where="`name`='$_GET[street]'";
$col='district';
}
else{$where="`name` LIKE ('$_GET[street]%')";$col='name';}
$where="WHERE $where";
}
elseif(isset($_GET['raions'])){$col='district';}
if($col){
$kladr = mysqli_query($on,"SELECT DISTINCT `$col` FROM `kladr` $where ORDER BY `$col` LIMIT 120");
if($kladr){
while($fm = mysqli_fetch_array($kladr)){
if($fm[$col]){
$ul[]="<option>$fm[$col]</option>";
}}
if($ul){
if(!isset($_GET['raion'])){
if(!isset($_SESSION['street'])){$_SESSION['street']=time();}
else{if($_SESSION['street']<(time()-3)){unset($_SESSION['street']);}
else{die;}}
}
die(implode($ul));
}
}}


if(user('id') and isset($_GET['dump'])){
$fn="./files/".bdname.".sql.gz";
if(is_file($fn)){
header("Content-Disposition: attachment; filename=\"".bdname.".sql.gz\"");
readfile($fn);
}
die;
}


if($_GET['uc']){
$uc=users($_GET['uc'],'claster');
die("<a href='payments?select=claster&val=$uc'>$uc</a>");
}



//РЕГЛАМЕНТ
if($_GET['reglament'] and user('status')<3){
die('<title>Регламент должности '.table('posts','id',$_GET['reglament'],'name').'</title><meta charset=utf-8><body style=font-family:calibri>'.file_get_contents("files/reglament".$_GET['reglament'].".html").'</body>');
}



if(user('id') and $_GET['p']){
include "pages/$_GET[p].php";die;
}



if(isset($_POST['areaNames'])){
$json=json_decode($_POST['areaNames']);
file_put_contents('files/works.json',$_POST['areaNames']);
}

?>