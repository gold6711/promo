<?
$posts=array_unique(result('users',"status>3 and status<8 and raion!=''",'status',9));
if(!$_GET['status']){$_GET['status']=$posts[0];
//max(array_count_values($posts));
}

?>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
    <script type="text/javascript">
ymaps.ready(function() {
    var map, 
        center = [37.6, 55.7],
        zoom = 11;
		
			
    map = new ymaps.Map('map', {
        center: center,
        zoom: zoom,
        controls: []
    });
	
    var url = "http://nominatim.openstreetmap.org/search";
	var raions=[];var users=[];
	<?
	$raions=result('users',"status=$_GET[status] and raion!=''",'raion',9);
	foreach(array_unique($raions) as $d){
	$d=trim($d);
	if(result('kladr',"district='$d'")){echo "raions.push('район $d москва');";}
	else{echo "raions.push('$d');";}
	echo "users.push('[\"$d\", \"".implode(';',result('users',"raion='$d' and status=$_GET[status]",'name',9))."\"]');";
	}
	?>
	localStorage.setItem('users',"var users=["+users.join(',')+"]");
	
	for(i=0;i<raions.length;i++){
	raions[i]=raions[i].replace('Черемушки','Черёмушки');
	raions[i]=raions[i].replace('Бирюлево','Бирюлёво');

    $.getJSON(url, {q: raions[i], format: "json", polygon_geojson: 1})
        .then(function (data) {
            $.each(data, function(ix, place) {
                if ("relation" == place.osm_type) {
				r=place.display_name.split(',')[0];
                    var p = new ymaps.Polygon(place.geojson.coordinates,{hintContent: r, balloonContent: user(r)},{ fill: false,
                 strokeWidth: 3});
	
                    map.geoObjects.add(p);
                }
            });
        }, function (err) {
            console.log(err);
        });
		}
function user(r){
eval(localStorage.getItem('users'));

var usr=[];
usr.push("<b>Закрепленные сотрудники</b>");
for(i=0;i<users.length;i++){
if(r.search(users[i][0])>=0){
itg=users[i][1].split(';');
usr.push(users[i][1].replace(/;/g,'<br>'));
}
}
usr.push("<i>всего: "+itg.length+"</i>");
return usr.join('<br>');
}
	
});if(location.href.search('print')>=0){setTimeout("window.print()",1000);}
    </script>
<h4>Выбрать карту для группы: <?
foreach($posts as $p){
echo "<a href='map?status=$p' class='label label-".table('posts','id',$p,'color')."'>".table('posts','id',$p,'name')."</a> ";
}?>
</h4>
<h3>Карта для группы <?=table('posts','id',$_GET['status'],'name')?></h3>
<div id="map" style="width:100%;height:1200px;"></div>
<!--
<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?sid=qj1_yS70rQsrXWlUXwIXGJE_0PGrH4iC&amp;width=1008&amp;height=423&amp;lang=ru_RU&amp;sourceType=constructor&amp;scroll=true"></script>
-->