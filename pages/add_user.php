<?
if(user('status')>1){$office=user('office'); $wh="where id>".user('status');}
elseif($_GET['office']){$office=$_GET['office'];}
	if($_GET['edit']){$post=edit('users','status',1);
	}
	else{
		if($_GET['status']){$post=$_GET['status'];}
		else{
		$wh="where id!=8";
		if(user('status')>1){$wh.=" and id>".user('status');}
		}
	}
	
	$posts="<select required name=status class=form-control>".option('posts '.$wh.' order by id desc','id','name',$post)."</select>";
	if(user('status')<2){
	$office="Офис: <select required name=office class=form-control><option value=0 disabled selected>Выбрать&hellip;</option>".option('offices'.$dp,'id','name')."</select>";}
	
	if($id!=user('id') and !$_GET['edit'] and !$_GET['status']){
		echo "<h3>Добавить сотрудника</h3>
		<form action=add_user class=form-inline>
		$office
		Должность: $posts
		<input type=submit class='btn btn-primary' value=Продолжить>
	</form>";}
	
	if($id==user('id') or $_GET['edit'] or $_GET['status']){
	?>
	<h3><?if($_GET['edit']){echo 'Редактировать'; $post=users($id,'status');}else{echo 'Добавить';$post=$_GET['status'];} echo ' сотрудника. Должность: '.table('posts','id',$post,'name');?></h3>
	<form class=form-inline method=post action="profile<?=$edit?>" enctype="multipart/form-data">
		<table class=table>
			<tr><td>Фамилия, Имя</td><td><input required class=form-control name=name <?=edit('users','name')?>></td></tr>
			
			<tr><td>Контактный номер телефона</td><td><input required class=form-control name=phone_l type=tel <?=edit('users','phone_l')?>></td></tr>
			<?if($post<4){?>
				<tr><td>Логин</td><td><input class=form-control name=mlogin <?=edit('users','login')?>></td></tr>
				<tr><td>Пароль</td><td><input class=form-control name=mpass type=password <?=$pass?>></td></tr>
				<?}else{?>
				<tr><td>Прикрепленный номер телефона</td><td><input class=form-control name=phone_p type=tel <?=edit('users','phone_p')?>></td></tr>
				<tr><td>Источник вакансии</td><td><select name=source class=form-control>
				<option value='' disabled selected>Выбрать...</option><?=option('vacancy','id','name',edit('users','source',1))?></select></td></tr>
				<?if($post==5){?>
					<tr><td>Прикрепленная улица</td><td><input class="form-control" name=street <?=edit('users','street')?> list=street style=width:15em><datalist id=street style='font-size:medium'></datalist></td></tr>
					<?}else{?><tr><td>Закрепленный участок</td><td><input class="form-control" name=claster <?=edit('users','claster')?> list=claster style=width:15em><datalist id=claster style='font-size:medium'></datalist>
						<select class=form-control name=claster></select>
						<br><b id=bus></b>
						<iframe src="jquery.php?p=map&status=<?=$post?>" class=hidden></iframe>
						<label><input type=checkbox id=busy value=1> Показывать только не занятые</label>
					</td></tr>
					
				<?}?>
				
				<tr><td>Кол-во выданных материалов</td><td><input class=form-control name=quantity step=100 max=9999 min=0 type=number <?=edit('users','quantity')?>></td></tr>
				<tr><td>Рабочие дни</td><td>
					<?
						foreach($days as $k => $v){
							if($v){
								if(strpbrk(edit('users','day',1),$k)){$sel='checked';}
								else{$sel=null;}
								//echo "<label><input name=day[] $sel type=checkbox value=$k> $v</label> &nbsp;";
							}}?>
							<iframe src='assets/calendar' width=230 height=220 frameborder=no scrolling=no></iframe>
							<input name=days type=hidden <?if($_GET['edit']){
								$wday=result('sheduler',"user=$_GET[edit]",'cdate',9);
								if($wday){
									foreach($wday as $d){
										$wds[]=date('Y-m-d',$d);
									}
									if($wds){echo "value='".implode(',',$wds)."'";}
								}}?>>
				</td></tr>
				
				<tr><td>Комментарий</td><td><textarea name=comment class=form-control></textarea>
				</td></tr>
				
			<?}?>
			
			<?if($_GET['edit']){?>
				<tr><td>Должность</td><td>
					<input type=hidden name=edit value='<?=$_GET['edit']?>'>
				<?=$posts?></td></tr><?}
				else{
					echo "<input type=hidden name=office value='$office'>
					<input type=hidden name=status value='$post'>";
				}?>
				<tr><td colspan=2>
				<input type=submit value='Сохранить' class="btn btn-primary"></td></tr>
		</table>
	</form>
	<?if($post>3){
		$busy=result('users',"status=$post and claster!=''",'claster',9);
		if($wds){$predays="localStorage.setItem('predays','".implode(",",$wds)."');";}
	?>
	<script>localStorage.removeItem("days");localStorage.removeItem("predays");
		<?=$predays?>
		$('[type=submit]').mouseover(function(){$('[name=days]').val(localStorage.getItem('days'));});
		localStorage.removeItem('users<?=$post?>');
		function busy(item){
			var getusers=localStorage.getItem('users<?=$post?>');
			if(getusers){eval(localStorage.getItem('users<?=$post?>'));
				var bus<?=$post?>=[];
				for(i=0;i<users.length;i++){
					if(users[i][0]==item){
						us=users[i][1].split(';');
						for(a=0;a<us.length;a++){bus<?=$post?>.push(us[a]);}
					}}
					if(bus<?=$post?>.length){
						$('#bus').css('color','red').text(bus<?=$post?>.length+' чел. уже работают по району '+item+': '+bus<?=$post?>.join(', '));
					}}
		}
		
		if(!localStorage.getItem('busy<?=$post?>')){localStorage.setItem('busy<?=$post?>', "var busy<?=$post?>=[''];");}
		$('#busy').click(function(){
			if($('#busy:checked').val()==1){
				<?if($busy){$busy=implode("','",array_unique($busy));}?>
			var busy="<?=$busy?>";}
			else{var busy='';}
			localStorage.setItem('busy<?=$post?>', "var busy<?=$post?>=['"+busy+"'];");
			lists();
		});
		
		function lists(){
			$.getJSON('files/regions.geojson', function(data) {
				
				var items = []; eval(localStorage.getItem('busy<?=$post?>'));
				$.each(data.features, function(key, val) {
					var name=val.properties.name;
					if(!name){name=val.properties.description;}
					if(busy<?=$post?>.indexOf(name)<0){
						items.push('<option>' + name + '</option>');
					}
				});
				
				items.sort();
				$('select[name=claster]').html(items.join());
				$('select[name=claster]').prepend("<option value='' disabled selected>Выбрать&hellip;</option>");
				$('select[name=claster]').change(function(){$('input[name=claster]').val($(this).val());});
				$('input[name=claster]').focus(function(){
					if($('#claster option').length==0){
					$('#claster').html(items.join());}
				})
			});
		}
		$('input[name=claster]').change(function(){busy($(this).val());
			selclast($(this).val());
		});
		if(localStorage.getItem('busy<?=$post?>').length>15){$('#busy').attr('checked','checked');
			$('#bus').empty();
		}
		<?if($_GET['edit']){echo "setTimeout(\"selclast('".edit('users','claster',1)."')\",1500);";}?>
		function selclast(cv){
			$('select[name=claster] option').each(function(){
				if($(this).val()==cv){$(this).attr('selected','selected');}
			});}
			
			lists();
			<?if($post==5){?>
				$('input[name=street]').keyup(function(){
				$('#street').load('jquery.php?street='+$('input[name=street]').val());}
				);
			<?}?>
	</script>
	<?}}?>	