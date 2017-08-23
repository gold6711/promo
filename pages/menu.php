<?
if(isset($_POST['name_menu'])){
if(is_array($_POST['name_menu'])){
foreach(result('menu','id>0','id',9) as $id){
sql("UPDATE `menu` SET `status`='".$_POST['status_menu'][$id]."', `name`='".$_POST['name_menu'][$id]."', `link`='".$_POST['link'][$id]."', `sequence`='".$_POST['sequence'][$id]."', `icon`='".$_POST['icon'][$id]."' where id=$id");
}
}
else{
sql("INSERT INTO `menu` (`status`,`name`,`link`,`sequence`,`icon`) VALUES(".intval($_POST['status_menu']).",'$_POST[name_menu]','$_POST[link]',".intval($_POST['sequence']).",'$_POST[icon]')");
}
}

?>
<h3>Редактировать меню</h3>
<form class=form-inline method=post action='settings?tab=menu'>
<table class=table>
<thead><th>№ п/п</th><th>Виден</th><th>Название</th><th>Ссылка</th><th>Иконка</th></thead>
<tbody>
<?
$f = mysqli_query($GLOBALS['on'],"SELECT * FROM `menu` ORDER BY `sequence`");
while($fm = mysqli_fetch_array($f)){
$i=$fm['id'];
$sts=option('posts where id<4','id','name',$fm['status']);
echo "<tr><td>
<input type=number min=0 max=99 class=form-control name=sequence[$i] value=$fm[sequence] style=width:4.5em></td><td><select name=status_menu[$i] class=form-control>
<option value=0>Всем</option>
$sts</select></td><td><input class=form-control name=name_menu[$i] value='$fm[name]'></td>
<td><input class=form-control name=link[$i] required value='$fm[link]'></td>
<td>
<input type=hidden id=icon$i name=icon[$i]><button id=b$i type='button' class='btn btn btn-default' data-toggle='modal' data-target='#myModal' data-whatever=$i><span class='glyphicon glyphicon-$fm[icon]'></span></button></td></tr>";
}
?>
</tbody>
<tfoot><th colspan=5><input type=submit class='btn btn btn-info' value='Изменить'></th></tfoot>
</table>
</form>


<h3>Добавить пункт меню</h3>
<form class=form-inline method=post action='settings?tab=menu'>
<table class=table>
<thead><th>После</th><th>Виден</th><th>Название</th><th>Ссылка</th><th>Иконка</th></thead>
<tr><td><select name=sequence class=form-control>
<?=option('menu','id','name')?></select></td>
<td><select name=status_menu class=form-control>
<option value=0>Всем</option>
<?=option('posts where id<4','id','name')?></select></td>
<td><input class=form-control name=name_menu required></td>
<td><input class=form-control name=link required pattern='[a-zA-Z0-9-]{2,}'></td>
<td><input type=hidden id=icon0 name=icon><button id=b0 type='button' class='btn btn btn-default' data-toggle='modal' data-target='#myModal' data-whatever=0>Выбрать</button></td>
<td><input type=submit class='btn btn btn-primary' value='Добавить'></td>
</tr>
</table>
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Выбор иконки для пункта меню</h4>
      </div>
      <div class="modal-body">
		<div id=glyph style='font-size:25px;overflow-y:auto;max-height:500px'></div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){$.getScript('../assets/icons.js');});
$("button[data-target='#myModal']").click(function (event) {
  var modal = $(this);
  var recipient = modal.data('whatever');
var itogo=glyph.length;
for(i=0;i<itogo;i++){
$('#glyph').append('<button class="btn btn btn-info" data-whatever="'+glyph[i]+'" data-dismiss="modal"><span class="glyphicon glyphicon-'+glyph[i]+'"></span></button>');
}
$('#glyph button').each(function(a){
$(this).attr('onclick',"$('#icon"+recipient+"').val('"+$(this).attr('data-whatever')+"')");
$(this).attr('onmousedown',"$('#b"+recipient+"').html('"+$(this).html()+"')");
})

$('#glyph button').attr('style','margin:4px');
})
</script>