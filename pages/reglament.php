<?if(!$_GET['status']){?>
<h3>Написать / редактировать регламент</h3>
<form action='settings' class=form-inline>
Должность: 
<input type=hidden name=tab value=reglament>
<select required name=status class=form-control>
<option disabled selected value=''>Выбрать&hellip;</option>
<?=option('posts where id!=8 order by id desc','id','name')?></select>
<input type=submit class="btn btn-primary" value=Продолжить>
</form>
<h3>Загруженные регламенты</h3>
<?foreach(glob("files/reglament*.html") as $r){
$p=str_replace('reglament',null,basename($r,'.html'));
$file[]="<a class='label label-".table('posts','id',$p,'color')."' href=\"javascript:window.open('jquery.php?reglament=$p','','width=630,height=740')\">".table('posts','id',$p,'name')."</a>";
}
if($file){echo implode(' ',$file);}
?>
<?}else{?>
<h3>Редактировать регламент</h3>
<?if(isset($_POST['reglament'])){file_put_contents("files/reglament".$_GET['status'].".html",$_POST['reglament']);}?>
<form action="settings?tab=medley&status=<?=$_GET['status']?>" method=post>
<textarea name=reglament class=form-control rows=20><?
$file="files/reglament$_GET[status].html";
if(is_file($file)){echo file_get_contents($file);}?></textarea>
<input type=button onclick="window.open('','reglament','width=630,height=740').document.write('<title>Регламент</title><body style=font-family:calibri;text-align:justify;font-size:14px;>'+$('[name=reglament]').val())" class="btn btn-info" value=Просмотр>
<input type=reset class="btn btn-danger" value=Отменить>
<input type=submit class="btn btn-primary" value=Сохранить>
</form>
<?}?>