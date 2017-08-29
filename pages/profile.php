<?
	if($_GET['edit']){
		$_GET['edit']=intval($_GET['edit']);
		if($_GET['delete']){sql("DELETE FROM `comments` WHERE `datetime`=".intval($_GET['delete'])." AND `user`=".$_GET['edit']);}
		$pass="placeholder='[Скрыт]'";
		$oid=$id=$_GET['edit'];
		$edit="?edit=$id";
	}
	else{$id=user('id');$oid=array_pop(result('users','id>0','id',9))+1;}
	
	if(!result('users',"id=$id")){
	echo "<div class=\"alert alert-danger\">Профиль #$id не найден!</div>";}
	else{
		
		if($_POST and !isset($_POST['summa'])){
			foreach($_POST as $k => $v){
				if($k!='edit'){
					switch($k){
						case 'mpass':if($v){$k='pass';$v=md5($v);}else{$k=null;}break;
						case 'mlogin':$k='login';break;
						case 'phone_l':$v=truenum($v);break;
						case 'phone_p':$v=truenum($v);break;
						case 'day':$v=implode($v);break;
						case 'days':$k=null;$vx=explode(',',$v);
						case 'comment':$k=$v=null;
						foreach($vx as $d){$ds[]=strtotime("$d 00:00");}
						if($ds and $id){
							$xsql="DELETE FROM `sheduler` WHERE `user`=$id";
							$gds[]=$xsql;
							sql($xsql);
						}
						if($ds){sql("UPDATE `users` SET day=0 WHERE `user`=$id");
							//$u=(array_pop(result('users','id>0','id',9))+1);//result('users','cdate>'.(time()-2),'id',9);
							
							foreach($ds as $d){
								$xsql="INSERT INTO `sheduler` (`cdate`,`user`) VALUES ($d,$oid)";
								$gds[]=$xsql;
								sql($xsql);
							}
						}
						
						break;
					}
					if($k){
						$sql[]="`$k`='$v'";
						$kk[]="`$k`";
						$vv[]="'$v'";
					}}}
					
					if(isset($_POST['edit'])){
						if($_POST['status']==8){$d=",`mdate`=".time();}
						else{$d=null;}
						$sql="UPDATE `users` SET ".implode(",",$sql)."$d WHERE `id`=$id";
						echo "<div class=\"alert alert-success\">Профиль успешно изменён!</div>";
					}
					else{
						$kk[]='cdate';$vv[]=time();
						$sql="INSERT INTO `users` (".implode(",",$kk).") VALUES (".implode(",",$vv).")";
						echo "<div class=\"alert alert-success\">Сотрудник успешно создан!</div>
						<script>window.open('map.php?q=".(array_pop(result('users','id>0','id',9))+1)."&print').focus()</script>";
					}
					sql($sql);
					echo "<script>setTimeout(\"location.href='users'\",1500);</script>";
					if(isset($_POST['comment']) and !empty($_POST['comment'])){
						sql("INSERT INTO `comments` (`datetime`,`text`,`user`,`autor`) VALUES (".time().",'$_POST[comment]',$id,".user('id').")");
					}
					
		}
		if(isset($_POST['summa']) and isset($_POST['user'])){
			sql("INSERT INTO `kassa` (`ord`,`user`,`summa`,`cdate`,`comment`) VALUES (".intval($_POST['ord']).",".intval($_POST['user']).",".intval($_POST['summa']).",".time().",'$_POST[comment]')");
		}
		
		if($_FILES['passport']){$i=0;
			foreach($_FILES['passport']['tmp_name'] as $fn){
				$fw="./uploads/passport$i-$id.jpg";
				move_uploaded_file($fn,$fw);
				$i++;
			}
		}
		if(!$_GET['tab']){$_GET['tab']='view';}
	?>
	<ul class="nav nav-tabs">
		<li class='<?=tab('view')?>'><a href="#view" data-toggle="tab">Просмотр</a></li>
		<li class='<?=tab('edit')?>'><a href="#edit" data-toggle="tab">Редактирование</a></li>
		<li class='<?=tab('audit')?>'><a href="#audit" data-toggle="tab">История</a></li>
		<li class='<?=tab('payment')?>'><a href="#payment" data-toggle="tab">Выплата</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in<?=tab('view')?>" id="view">
			
			<table class=table>
				<tr><td>Фамилия, Имя</td><td><?=users($id,'name')?></td></tr>
				<tr><td>Должность</td><td><?=table('posts','id',users($id,'status'),'name')?></td></tr>
				<tr><td>Дата оформления</td><td><?=date('j.m.Y, H:i',users($id,'cdate'))?></td></tr>
				<tr><td>Телефон личный</td><td><?=users($id,'phone_l')?></td></tr>
				<tr><td>Телефон прикрепленный</td><td><?=users($id,'phone_p')?></td></tr>
				<?if(users($id,'status')<4){?>
					<tr><td>Логин</td><td><?=users($id,'login')?></td></tr>
					<?}else{?>


<!-- ***********************************************  ввод на странице и запись  базу     ****************************************** -->

					<tr><td>Выдать материалы</td><td>
						<form>
							<div class="form_style" style="height: 20px;"><input name="content_txt" id="contentNum" type="number" min="0" max="999" required style="width: 55px;" placeholder=" 0 ">
							<button class='btn btn-primary btn-sm' id="FormSubmit">Выдать</button>
<!--						<input type="submit" class='btn btn-primary btn-sm' value="Выдать"></div>-->
						</form>
					</td></tr>


<!-- ***********************************************  ввод на странице и запись  базу     ***************************************** -->


					<tr><td>Кол-во выданных материалов</td><td><?=users($id,'quantity')?></td></tr>
					<tr><td>Рабочие дни</td><td><?
						$wdw=result('sheduler',"user=$id",'cdate',9);
						if($wdw){
							foreach($wdw as $w){
								$wds[]=date('j.m.Y',$w);
							}
							$wds[]=";";
							echo implode(', ',array_unique($wds));
						}
						foreach($days as $k => $v){
							if(strpbrk(edit('users','day',1),$k)){$wd[]=$v;}
						}
						if($wd){echo implode(', ',$wd);}
					?></td></tr>
					<tr><td>Офис</td><td><?=table('offices','id',users($id,'office'),'name')?></td></tr>
					<tr><td>Источник вакансии</td><td><?=table('vacancy','id',users($id,'source'),'name')?></td></tr>
					<tr><td>Участок</td><td><?=users($id,'claster')?></td></tr>
					<tr><td>Отработанное время</td><td><?=worktime($id)?></td></tr>
					<tr><td>Начислений</td><td><?=intval(result('resultats',"user=$id and (`project`='НД' or (`project`='КП' and `paid`>0))"))?></td></tr>
					<tr><td>Выплат</td><td><?=result('kassa',"user=$id",'summa',2).'р. ('.result('kassa',"user=$id").' шт.)'?></td></tr>
					<?$comments=result('comments',"user=$id",'*',6);
						if($comments){?>
						<tr><td>Комментарии</td><td><?
							foreach($comments as $c){
								$cm[]="<b>".users($c['autor'],'name')."</b>: $c[text], <i>".date('j.m.Y, H:i',$c['datetime'])."</i> <a title='Удалить' href='profile?edit=$id&delete=$c[datetime]'><span class='glyphicon glyphicon-trash'></span></a>";
							}
						echo implode('<br>',$cm)?></td></tr><?}?>
						<tr><td>Маршрутная карта</td><td><a href='map.php?q=<?=$id?>' target='_blank' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-map-marker'></span> Просмотр</a> <a target='_blank' href='map.php?q=<?=$id?>&print' class='btn btn-info btn-sm'><span class='glyphicon glyphicon-print'></span> Печать</a></td></tr>
				<?}?>
			</table>
		</div>
		
		<div class="tab-pane fade in<?=tab('edit')?>" id="edit">
			<?$_GET['edit']=$id;include "add_user.php"?>
		</div>
		<div class="tab-pane fade in<?=tab('audit')?>" id="audit">
			<?$_GET['id']=$id;include "audit.php"?>
		</div>
		<div class="tab-pane fade in<?=tab('payment')?>" id="payment">
			<form method=post action='profile?tab=audit' class=form-inline>
				<table class=table>
					<tr><td>Сумма:</td><td><input type=number required name=summa class=form-control min=0 max=9999></td></tr>
					<tr><td>Прикрепить заявку:</td><td><input type=number name=ord class=form-control min=0 max=9999></td></tr>
				<td>Комментарий:</td><td><textarea class=form-control name=comment></textarea></td></tr>
				<tr><td colspan=2><input type=hidden name=user value=<?=$id?>>
				<input type=submit value='Выплатить' class="btn btn-primary"></td></tr>
			</table>
		</form>
	</div>
</div>

<?}?>

<script>
	$(document).ready(function() {
		// Добавляем новую запись, когда произошел клик по кнопке
		$("#FormSubmit").click(function () {

			if($("#contentNum").val()==="")
			{
				alert("Введите текст!");
				return false;
			}
			var myData = '12';//"content_num="+ $("#contentNum").val();

			$.ajax({
				type: "POST",
				url: "response.php",
				dataType:"text",
				data: myData,
				success:function(){
					//$("#responds").append(data);
					$("#contentText").val(''); //очищаем текстовое поле после успешной вставки
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError); //выводим ошибку
				}
			});
		});
	});
</script>
