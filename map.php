<?
include_once('total.php');
$title="Маршрутная карта №$_GET[q]. ".users($_GET['q'],'name').' ('.table('posts','id',users($_GET['q'],'status'),'short').'); Участок: '.users($_GET['q'],'claster');
?><!DOCTYPE html>
<html>
<head>
<script src="assets/jquery-3.1.1.min.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
 <meta charset="utf-8">
    <script>
ymaps.ready(function() {
var lcenter=localStorage.getItem("center<?=$_GET['q']?>");
var lzoom=localStorage.getItem("zoom<?=$_GET['q']?>");

    var map,
	raion="<?=users($_GET['q'],'claster')?>";
	if(!lcenter){center=[37.5, 55.7];}
	else{center=lcenter.split();}
	if(!lzoom){zoom=14;}else{zoom=lzoom;}
    map = new ymaps.Map('map', {
        center: center,
        zoom: zoom,
		preciseZoom: true, 
        controls: []
    });

    var url = "files/regions.geojson";

var points=[[[35,57],[41,57],[41,54],[35,54],[35,57]]];					

	 $.getJSON(url, function(data) {
	 $.each(data.features, function(ix) {
                if(data.features[ix].properties.name==raion){
				points.push(data.features[ix].geometry.coordinates[0]);
				}
            })
    var p = new ymaps.GeoObject({
        geometry: {
            type: "Polygon",
            coordinates: points,
            fillRule: "evenOdd"
        },
    }, {
		<?if(isset($_GET['more'])){echo "fill: false,";}else{echo "fillColor: '#ffFFff',opacity: 0.7,";}?>
        strokeWidth: 5
        
    });						pm=[];pd=[];
					$.each(points[1],function(i){
					pm.push(points[1][i][0]);
					pd.push(points[1][i][1]);
					});
					
				function average(arr) {
    var
        x, correctFactor = 0,
        sum = 0
    ;
    for (x = 0; x < arr.length; x++) {
        arr[x] = +arr[x];
        if (!isNaN(arr[x])) {
            sum += arr[x];
        } else {
            correctFactor++;
        }
    }
    return (sum / (arr.length - correctFactor));
}

if(pm.length>0 && pd.length>0){

map.setCenter([average(pm),average(pd)]);
map.geoObjects.add(p);
if(location.href.search('print')>=0){setTimeout("window.print()",2000);}
	}
else{
 ymaps.geocode(raion, {results: 1}).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0),
                coords = firstGeoObject.geometry.getCoordinates(),
                bounds = firstGeoObject.properties.get('boundedBy');

            map.setBounds(bounds, {checkZoomRange: true});
if(location.href.search('print')>=0){setTimeout("window.print()",2000);}});
	}
	});

	$('#map').click(function(){
localStorage.setItem("center<?=$_GET['q']?>", map.getCenter());
localStorage.setItem("zoom<?=$_GET['q']?>", map.getZoom());
});


$('.ymaps-2-1-47-copyright').removeClass();
});

    </script>
<title><?=$title?></title>
<style>
<?if($_GET['rotate']){echo '@page{size:landscape;}#map{height:18cm;width:29cm;}';}
else{echo '#map{height:29cm;width:21cm;}';}?>
#map,html{padding:0;margin:0;}
body,main{font-family:Calibri;}
main{margin-top:2cm;font-size:14px;text-align:justify;height:29cm;width:21cm;}
h2,h3{text-align:center;}
h2{font-size:18px;}
h3{font-size:16px;}
@media print{button{display:none;}}
</style>
</head>
<body>
<h2><?=$title?></h2>
<button onclick="location.href='<?=$_SERVER['REQUEST_URI']?>&rotate=<?=abs($_GET['rotate']-1)?>'">Поворот</button> <button onclick=window.print()>Печать</button>
<div id="map"></div>
<?
$file="files/reglament".users($_GET['q'],'status').".html";
if(is_file($file) and !$_GET['rotate']){echo '<main>'.file_get_contents($file).'</main>';}
?>
</body>
</html>