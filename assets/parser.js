var myMap;
 var result;
 var point;
 var demo;
 var areas;
 var points = [];
var addManyPoints;
var getareas;
 // ждем загрузку API и готовности DOM.
 ymaps.ready(init);

 function init() {
     // Создание экземпляра карты и его привязка к контейнеру с
     // заданным id ("map").
	 function drawmap(){
     myMap = new ymaps.Map('map', {
         center: [37.5, 55.6], // Москва
         zoom: 10
     });

	 
     // добавляет (рисует) все участки на карте
     function drawAreas(json) {
         json['features'].forEach(function(el) {
		 
		  el['properties'] = {
            hintContent: el['properties'].name
        };
		 
             el['options'] = {
                 fill: false,
                 strokeWidth: 3
             };
			 
         });
		 
         areas = ymaps.geoQuery(json)
             .addToMap(myMap)
             .applyBoundsToMap(myMap, {
                 checkZoomRange: true
             });
     }

     // возвращает данные всех участков
     function getAreas() {
         var all = ymaps.geoQuery(myMap.geoObjects);
         var polygons = all.search('geometry.type == "Polygon"');
         var list = [];
         polygons.each(function(el) {
             list.push({
                 name: el.properties._data.name
             });
         });
         
         return list;
     }

     // функция определения участка для точки(точек) 
     function searchArea(data) {
         var object;
        
         var area = {
             name: ''
         };
         var all = ymaps.geoQuery(myMap.geoObjects);
         var polygons = all.search('geometry.type == "Polygon"');
         var result = polygons.searchContaining(data);
         if (result.getLength() > 0) {
             area.name = result.get(0).properties._data.name;
         }
         
return area.name;
         //return data.properties._data.name;
         // }
     }

     function getPost() {
         var newPoint = newPointCoord(pointsJSON[1].coordinates[0], pointsJSON[1].coordinates[1], pointsJSON[1].coordinates[0] + ', ' + pointsJSON[1].coordinates[1]);
         var areaName = searchArea(newPoint);

         // вот он пост запрос на сервер с именем Участка
        
     }

	 getareas=function (points){
	 var p=points.length;
	 var areaNames=[];
	 var area;
	 for(i=0;i<p;i++){
	if(points[i].user>0){
	 var newPoint = newPointCoord(points[i].coordinates[0], points[i].coordinates[1], points[i].coordinates[0] + ', ' + points[i].coordinates[1],points[i].ord+'-'+points[i].project);
	 area=searchArea(newPoint);
	 if(area!=''){
      areaNames.push({area: area,point: points[i].id});
	 }}}
	 
	   $.ajax({
             type: 'POST',
             url: 'jquery.php',
             data: {areaNames: JSON.stringify(areaNames)},
             success: function() {
                
             },
             dataType: 'json'
         });
	 }
	 
     function setFocusByArea(name) {
         var all = ymaps.geoQuery(myMap.geoObjects);
         var polygon = all.search('geometry.type == "Polygon"').search(`properties.name == "${name}"`);
         polygon.applyBoundsToMap(myMap, {
             checkZoomRange: true
         });
     }

     addManyPoints=function(points) {
         points.forEach(function(point) {
			 if(point.user>0){
             newPointCoord(point.coordinates[0], point.coordinates[1], point.coordinates[0] + ', ' + point.coordinates[1],point.ord+'-'+point.project)}
         });
     }

     // функция добавления новой точки на карту с помощью адреса (geocode)
     function newPointAddress(address) {
         var myGeocoder = ymaps.geocode(address, {
             results: 1
         });
         myGeocoder.then(
             function(res) {
                 var firstGeoObject = res.geoObjects.get(0);
                 //myMap.geoObjects.add(firstGeoObject);
				 console.log(res.geoObjects.get(0).properties.get('metaDataProperty'));
                 searchArea(firstGeoObject);
             },
             function(err) {
                 // обработка ошибки
             }
         );
     };

     // функция добавления новой точки на карту с помощью координат
     function newPointCoord(long, lat, name, p) {
	 if(p.search('НД')>=0){clr='red';}else{clr='green';}
         var newPoint = new ymaps.GeoObject({
             geometry: {
                 type: "Point",
                 coordinates: [long, lat]
             },
             properties: {
                 "name": name,
                      iconContent: p
             }
         }, {
             preset: "islands#"+clr+"StretchyIcon",
             zIndex: 9
         });
         myMap.geoObjects.add(newPoint);
         searchArea(newPoint);
         return newPoint;
     }

     // добавляем участки на карту
     drawAreas(geoJSON);

}

  // загружаем файл с данными участков, в формате .GeoJSON 
 var geoJSON ={};
jQuery.getJSON('../files/regions.geojson', function(areas) {
geoJSON=areas;
  var pointsJSON=[];
jQuery.getJSON('../files/points.geojson', function(pts) {
pointsJSON=pts; 
drawmap();
addManyPoints(pointsJSON);
getareas(pointsJSON);
  });  
  }); 

 }