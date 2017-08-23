<?
if(user('status')>1){die();}
# ФОРМА ИЗМЕНЕНИЯ КОНФИГА
function settings($p,$o=''){
//устанавливаем путь к файлу
$c=file($p);//читаем в массив
//если не переданы данные, выводим форму
$id=basename($p,".php");
echo "<form method=post action=settings id=$id class='form-inline'>";
//рисуем таблицу
echo "<table class=table>";

//цикл с первой до предпоследней строки
for($i=1;$i<count($c)-1;$i++){
//делим по строкам
$c1=explode(";//",$c[$i]);
//делим по ячейкам
$c2=explode("',\"",$c1[0]);
//длина строки
$c3=strlen($c2[1]);
//вырежем переменную
$c2[0]=str_replace("define('",null,$c2[0]);
//если данные не переданы, выводим таблицу

if(!isset($_POST["{$id}2"])){$val=substr($c2[1],0,-2);}
else{
$val=$_POST["$id$i"];}


 if($val!='' and strlen($val)==1 and $val<2){
 $inp="Нет <input name=$id$i type=range min=0 max=1 style=width:4em step=1 value=$val class=\"form-control\"> Да";
 }
else{
$len=$c3;
if(strpos($val," ")){$len=35;}
if($len<3){$wh=15;}
else{$wh=$len;}
if($c3<60 or ($c3<100 and !strpos($val," "))){$inp="<input class=\"form-control\" type=text name=\"$id$i\" value=\"$val\" style=width:{$wh}em>";}
//если длинный текст, меняем поле ввода
else{$inp="<textarea name=\"$id$i\" rows=2 cols=30 class=\"form-control\" style=width:{$len}em>$val</textarea>";}
}

echo "<tr><td>$c1[1]</td><td$colb>
$inp</td></tr>";
if(isset($_POST["{$id}1"])){
//если переданы, формируем новый конфиг
$out[]="define('$c2[0]',\"{$_POST["$id$i"]}\");//$c1[1]";
if($_POST["$id$i"]!=substr($c2[1],0,-2)){echo '';}
}
}
if(!$o){$o=$p;}
if($_POST["{$id}1"]){
if(user('readonly')==0){
file_put_contents($o,"<?#НАСТРОЙКИ от ".date('d.m.Y, H:i:s')."\n".implode($out)."?>");}
else{echo '<div class="alert alert-danger">Вы не можете менять настройки. Обратитесь к администратору</div>';unset($_POST);}
}
echo "<tr><td>
<input type=reset value='По умолчанию' class='btn btn-default'>
</td><td> 
<input type=submit value='Применить' class='btn btn-primary'>
</form></td></tr>
</table>";

}

if($_POST){
echo "<div class=\"alert alert-success\">Настройки успешно сохранены!</div>";}
if(!$_GET['tab']){$_GET['tab']='settings';}
?>

<ul class="nav nav-tabs">
  <li class='<?=tab('settings')?>'><a href="#settings" data-toggle="tab">Основные</a></li>
  <li class='hidden-xs<?=tab('bd')?>'><a href="#bd" data-toggle="tab">БД</a></li>
  <li class='<?=tab('price')?>'><a href="#price" data-toggle="tab">Расценки</a></li>
  <li class='<?=tab('sources')?>'><a href="#sources" data-toggle="tab">Источники</a></li>
  <li class='<?=tab('posts')?>'><a href="#posts" data-toggle="tab">Должности</a></li>
  <li class='<?=tab('menu')?>'><a href="#menu" data-toggle="tab">Меню</a></li>
  <li class='<?=tab('offices')?>'><a href="#offices" data-toggle="tab">Офисы</a></li>
  <li class='<?=tab('medley')?>'><a href="#medley" data-toggle="tab">Разное</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in<?=tab('settings')?>" id="settings">
<?settings('config.php')?>
</div>
  <div class="tab-pane fade in<?=tab('bd')?>" id="bd">
<a class="btn btn-success" download href="jquery.php?dump" onclick="$(this).addClass('disabled');$(this).text('Файл дампа сохранен на Вашем компьютере');"><span class="glyphicon glyphicon-save"></span> Экспорт дампа SQL</a>
<?settings('config_bd.php')?>
</div>
  <div class="tab-pane fade in<?=tab('price')?>" id="price">
<?include "prices.php"?>
</div>

  <div class="tab-pane fade in<?=tab('sources')?>" id="sources">
<?include 'sources.php'?>
  </div>
  
  <div class="tab-pane fade in<?=tab('posts')?>" id="posts">
<?include 'posts.php'?>
  </div>
  <div class="tab-pane fade in<?=tab('menu')?>" id="menu">
  <?include "menu.php"?>
  </div>
  <div class="tab-pane fade in<?=tab('offices')?>" id="offices">
  <?include "offices.php"?>
  </div>
  <div class="tab-pane fade in<?=tab('medley')?>" id="medley">
<div class="row">
  <div class="col-md-6"><?include "reglament.php"?></div>
  <div class="col-md-6"><?include "regions.php"?></div>
  <div class="col-md-12">
  <p>
  <div class="panel panel-default">
  <div class="panel-heading">Планировщик заданий</div>
  <div class="panel-body">
  Для выполнения сервером заданий по CRM внесите в планировщик crontab (см. панель управления хостингом) следующие записи: <p>
  <a href='#' data-toggle="collapse" data-target="#cron">(Показать/Свернуть)</a>
  <div class="well collapse out" id="cron" style='max-height:180px;overflow-y:auto;padding-top:2px;padding-bottom:2px;'>
</div>
Время и периодичность выполнения заданий настройте в интерфейсе планировщика на сервере.<br>
За дополнительной информацией по настройке crontab обратитесь к администратору сервера.
  </div></div>
<script>
$(document).ready(function(){
var php="php -f <?=$dir_root?>/cron.php ";
var cron=[['Сбор данных с других CRM',php+'period=today'],['Назначение выплат',php+'paid=today'],['Экспорт геоточек',php+'export=1'],['Определение сотрудника для выплат',php+'init=1'],['Автоматический экспорт дампа','<?=$eval?>'],['Автозакрытие смены (сотрудник не позвонил)',php+'close=today']];
for(i=0;i<cron.length;i++){
$('#cron').append('<div class="row"><div class="col-md-6">'+cron[i][0]+'</div><div class="col-md-6"><tt>'+cron[i][1]+'</tt></div></div>');
}
});
</script>
  
  </div>
</div>

</div>
</div>