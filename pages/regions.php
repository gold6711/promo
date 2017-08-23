<?
$geojson="./files/regions.geojson";
if(is_uploaded_file($_FILES['geo']['tmp_name'])){
move_uploaded_file($_FILES['geo']['tmp_name'],$geojson);
echo "<div class='alert alert-success'>Участки карт успешно изменены</div>";
}
?>
<h3>Изменить участки карт</h3>
Для изменения участков нужно экспортировать файл в формате GEOJSON с конструктора карт.
<form method="post" enctype="multipart/form-data" action="settings?tab=medley" class='form-inline'>
Загрузить *.geojson:
<input type="file" name="geo" required accept="application/geojson+json" class="form-control">
<input type=submit class="btn btn-primary" value=Загрузить>
</form>
Дата последнего изменения: <span class="label label-info"><?if(is_file($geojson)){echo date('j.m.Y, H:i:s',filemtime($geojson));}?></span>