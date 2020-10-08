<?php 
// lon 経度、lat 緯度
if (isset($_GET["lat"],$_GET['lon'],$_GET['temp'],$_GET['hum'],$_GET['pres'],$_GET['ill'])) {
	$fp=fopen("KanData.dat","w");
		fprintf($fp,"<?php");
		fprintf($fp,"$lat=%s;\n",$_GET["lat"]);
		fprintf($fp,"$lon=%s;\n",$_GET["lon"]);
		fprintf($fp,"$temp=%s;\n",$_GET["temp"]);
		fprintf($fp,"$hum=%s;\n",$_GET["hum"]);
		fprintf($fp,"$pres=%s;\n",$_GET["pres"]);
		fprintf($fp,"$ill=%s;\n",$_GET["ill"]);
		fprintf($fp,"?>");
	fclose($fp);
	echo "{res:'ok'}";
} else {
	echo "{res:'ng'}";
}
