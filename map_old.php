<?include_once('total.php');
$title="Маршрутная карта №$_GET[q]. ".users($_GET['q'],'name').' ('.table('posts','id',users($_GET['q'],'status'),'short').'); Район: '.users($_GET['q'],'raion');
?><!DOCTYPE html>
<html>
<head>
<script src="assets/jquery-3.1.1.min.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&coordorder=longlat" type="text/javascript"></script>
 <meta charset="utf-8">
    <script type="text/javascript">
ymaps.ready(function() {
    var map,
	raion="<?$raion=str_replace('Черемушки','Черёмушки',str_replace('Бирюлево','Бирюлёво',users($_GET['q'],'raion')));echo $raion
	?>";
	regionName = 
	<?if(!table('kladr','district',$raion,'id')){
	echo "'$raion'";
	}else{$r='район ';
	if(isset($_GET['more'])){$b=$r;}
	else{$a=$r;}
	echo "'$a'+raion+' {$b}Москва'";}?>,
        center = [37.5, 55.7],
        zoom = 12;
		
			
    map = new ymaps.Map('map', {
        center: center,
        zoom: zoom,
        controls: []
    });



    var url = "http://nominatim.openstreetmap.org/search";//raion.split(' ').reverse().join(' ')
	
    $.getJSON(url, {q: regionName, format: "json", polygon_geojson: 1})
        .then(function (data) {
            $.each(data, function(ix, place) {
                if ("relation" == place.osm_type) {
				    // Создаем многоугольник, используя вспомогательный класс Polygon.
var points=[[[35,57],[41,57],[41,54],[35,54],[35,57]],place.geojson.coordinates[0]];

// Создаем многоугольник, используя класс GeoObject.
    var p = new ymaps.GeoObject({
        // Описываем геометрию геообъекта.
        geometry: {
            // Тип геометрии - "Многоугольник".
            type: "Polygon",
            // Указываем координаты вершин многоугольника.
            coordinates: points,
            // Задаем правило заливки внутренних контуров по алгоритму "nonZero".
            fillRule: "evenOdd"
        },
        // Описываем свойства геообъекта.
        
    }, {
        // Описываем опции геообъекта.
        // Цвет заливки.
		<?if(isset($_GET['more'])){echo "fill: false,";}else{echo "fillColor: '#ffFFff',opacity: 0.7,";}?>
        strokeWidth: 5
        
    });

                    map.geoObjects.add(p);
								
	
                }
            });
        }, function (err) {
            console.log(err);
        });
		
		
   ymaps.geocode(regionName, {
        results: 1
    }).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0),
                coords = firstGeoObject.geometry.getCoordinates(),
                bounds = firstGeoObject.properties.get('boundedBy');

            map.setBounds(bounds, {
                checkZoomRange: true
            });
			

			if(location.href.search('print')>=0){setTimeout("window.print()",1700);}});
});
    </script>
<title><?=$title?></title>
<style>
#map,html{height:29cm;width:21cm;padding:0;margin:0;}
body,main{font-family:Calibri;}
main{margin-top:2cm;font-size:14px;text-align:justify;}
h2,h3{text-align:center;}
h2{font-size:18px;}
h3{font-size:16px;}
</style>
</head>
<body>
<h2><?=$title?></h2>
<div id="map"></div>
<main>
<?
$file="files/reglament".users($_GET['q'],'status').".html";
if(is_file($file)){echo file_get_contents($file);}?>
</main>
</body>
</html>