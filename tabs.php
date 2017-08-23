<?
if(!$_GET['ot'] and !$_GET['po']){$_GET['ot']=date('Y-m-d',strtotime('last Monday'));$_GET['po']=date('Y-m-d',strtotime('this Sunday'));}
if($_GET['tab']){$tab='?tab='.$_GET['tab'].'&';}
else{$tab='?';}
?><div id=tabs>
		<div class="btn-group">
  <a href="<?=$_GET['act'].$tab?>ch" class="btn btn-default">Все</a>
  <a href="<?=$_GET['act'].$tab?>ch=y" class="btn btn-default">Вчера</a>
  <a href="<?=$_GET['act'].$tab?>ch=d" class="btn btn-default">Cегодня</a>
  <a href="<?=$_GET['act'].$tab?>ch=w" class="btn btn-default">Неделя</a>
</div> <div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Период <span class="caret"></span></button>
  <div class="dropdown-menu" role="menu" style=padding:5px>За период: 
<form id=form_period method=get class=inline action=<?=$_GET['act'].$tab?>>
<li>с <input type=date required name=ot class='form-control' style=width:11em value='<?=$_GET['ot']?>'>
<li>по <input type=date required name=po class='form-control' style=width:11em value='<?=$_GET['po']?>'>
<li><input type=submit class="btn btn-primary" value='Показать'>
</form>
  </div>
</div>

<p>Показано записей: <b><?=count($tr)?></b>
<?if($nostatus){?>
		<div class="btn-group btn-group">
<?if(user('status')<2){?>
  <a href="<?=$_GET['act'].$tab?>&ch=<?=$_GET['ch']?>&all" class="btn btn-default">Все</a>
  <a href="<?=$_GET['act'].$tab?>ch=<?=$_GET['ch']?>" class="btn btn-default">Закрепленные</a>
  <a href="<?=$_GET['act'].$tab?>user=0&ch=<?=$_GET['ch']?>" class="btn btn-default">Не распределенные</a>
<?}?>
  <a href="<?=$_GET['act'].$tab?>wait" class="btn btn-warning"><span class="glyphicon glyphicon-time"></span> В ожидании</a>
  <a href="<?=$_GET['act'].$tab?>paid=no&ch" class="btn btn-danger"> Не выплачены</a>
  <a href="<?=$_GET['act'].$tab?>paid=1&ch=<?=$_GET['ch']?>" class="btn btn-success">Оплаченные</a>
</div>
		</p>
		<big><?if($_GET['search']){echo "Вы искали: <b>$_GET[search]</b>; ";}?>
		Показано записей: <b><?=count($tr)?></b></big>
<?}?></div>