<?

$posts=array_unique(result('users',"status>3 and status<8 and claster!=''",'status',9));
if(!$_GET['status']){$_GET['status']=$posts[0];
}

?>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
    <script type="text/javascript">
ymaps.ready(function() {
    var map;
    map = new ymaps.Map('map', {
        center: [37.6, 55.7],
        zoom: 11,
        controls: []
    });

var users=[];var coords=[];
	<?
	$raions=result('users',"status=$_GET[status] and claster!=''",'claster',9);
	foreach(array_unique($raions) as $d){
	$d=trim($d);
	echo "users.push('[\"$d\", \"".implode(';',result('users',"claster='$d' and status=$_GET[status]",'name',9))."\",".coord($d)."]');";
	}
	?>
	localStorage.setItem('users',"var users=["+users.join(',')+"]");  
	

<?
function coord($d){
$jsf=file_get_contents("files/regions.geojson");
$json=json_decode($jsf);
foreach($json->features as $j){
$n=$j->properties->name;
if($n==$d)
{
$exn[]="'$n'";
$exp[]=$j->geometry->coordinates[0];
}
}
if($exp[0]){
foreach($exp[0] as $k => $v){
$rr[]='['.implode(',',$v).']';
}
$rr=implode(',',$rr);
}
return '['.$rr.']';
}
?>
eval(localStorage.getItem('users'));
var myCollection = new ymaps.GeoObjectCollection();

function usr(w,m){
var itg=[];
for(a=0;a<users.length;a++){
if(users[a][0]==w){
it=users[a][1].split(';');
itg.push(it.join('<br>'));
}}
if(m==1){return it.length;}
else{return itg;}
}
for(i=0;i<users.length;i++){

x="<b>Закрепленные сотрудники</b><br>"+usr(users[i][0],0)+"<br><i>всего: "+usr(users[i][0],1)+"</i>";
myCollection.add(new ymaps.Polygon([users[i][2]],{hintContent: users[i][0], balloonContent: x},{ fill: false,
                 strokeWidth: 3}));	
}
map.geoObjects.add(myCollection);

});if(location.href.search('print')>=0){setTimeout("window.print()",1000);}

    </script>
<h4>Выбрать карту для группы: <?
foreach($posts as $p){
echo "<a href='map?status=$p' class='label label-".table('posts','id',$p,'color')."'>".table('posts','id',$p,'name')."</a> ";
}?>
</h4>
<h3>Карта для группы <?=table('posts','id',$_GET['status'],'name')?></h3>
<div id="map" style="width:100%;height:1200px;"></div>
