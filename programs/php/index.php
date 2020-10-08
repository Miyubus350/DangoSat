<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=10.0, user-scalable=yes">
<title>災害避難誘導アプリ</title>
<meta name="description" content="">
<meta name="keywords" content="">
<style type="text/css">
    <!--
	body {
            -webkit-text-size-adjust: 100%;
            padding: 10px;
        }
    -->
</style>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<?php
include './KanData.php';
$pr="<p>lat,lon=[$lat °,$lon °]<br />temp,hum,press=[$temp °,$hum ％,$pres hp]<br />illuminance=$ill<br /></p>";
?>
<body style="background-image : url(./image/shelter.png);">
<header>
<h1>災害避難誘導アプリ</h1>
<p>このアプリケーションは災害発生時選択された地域で発生した災害に対して有効な避難場所に誘導するアプリです。<br />
缶サットからのデータを受け取り避難時に適切な装備や避難先が起こっている災害に適しているかの判断材料として使います.</p>
</header>
<div id="contents">
<h1>地域</h1>

<div id='map' style='width: 450px; height: 300px;'></div>
<script>
  // API Key
  mapboxgl.accessToken = 'Your_apikey';
  var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [102.0215,19.973],
    zoom: 12
  });
 // put marker 
 var marker1 = new mapboxgl.Marker().setLngLat([102.0215,19.973])
     .setPopup(new mapboxgl.Popup().setHTML(<?php echo '"'.$pr.'"'; ?>)).addTo(map);
 var marker2 = new mapboxgl.Marker().setLngLat([102.018628,19.953622])
     .setPopup(new mapboxgl.Popup().setHTML(<?php echo '"'.$pr.'"'; ?>)).addTo(map);
 var marker3 = new mapboxgl.Marker().setLngLat([102.056050,19.937594])
     .setPopup(new mapboxgl.Popup().setHTML(<?php echo '"'.$pr.'"'; ?>)).addTo(map);
 var marker4 = new mapboxgl.Marker().setLngLat([102.089099,19.909944])
     .setPopup(new mapboxgl.Popup().setHTML(<?php echo '"'.$pr.'"'; ?>)).addTo(map);
 var marker5 = new mapboxgl.Marker().setLngLat([102.124598,19.895913])
     .setPopup(new mapboxgl.Popup().setHTML(<?php echo '"'.$pr.'"'; ?>)).addTo(map);

<?php
if (isset($_GET['c'])) {
echo "
    map.on('load', function () {
        map.loadImage(
            'http://sajk.waitwg.org/~spaus01/image/house.png',
            function (error, image) {
                if (error) throw error;
                map.addImage('house', image);
                map.addSource('point', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': [
                            {
                                'type': 'Feature',
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [102.045, 19.985]
                                }
                            }
                        ]
                    }
                });
                map.addLayer({
                    'id': 'points',
                    'type': 'symbol',
                    'source': 'point',
                    'layout': {
                        'icon-image': 'house',
                        'icon-size': 0.25
                    }
                });
            }
        );
    });
";
}
?>
function goTyhoon() {
	location.href="./?c=ty";
}
function goEarthq() {
	location.href="./?c=eq";
}
function goErupt() {
	location.href="./?c=er";
}
</script>
<img src="image/TYP01.png" width=50 height=50 onclick="goTyhoon()">
<img src="image/earthq1.jpeg" width=50 height=50 onclick="goEarthq()">
<img src="image/erupt01.png" width=50 height=50 onclick="goErupt()">
<?php
$api_key = "api_key=DEMO_KEY";
if (isset($_GET['c'])) {
echo '<br />';
echo '<img src="https://api.nasa.gov/planetary/earth/imagery?lon=-102.00&lat=20.00&'
        .'date='.date("Y-m-d").'&dim=0.15&'
        .$api_key.'" width=450>';
}
?>
</div>
</body>
</html>
