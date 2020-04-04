<?php

    //header('Location: http://www.venitaclinic.com/Qweb/site1_Wiztech/WiztechSolution/include/HomeoMap.php?mapId='.$_GET['mapId']);

?>

<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">-->
<?PHP
//header('X-Frame-Options: GOFORIT'); 
error_reporting(E_ERROR | E_PARSE);
ob_start(); // ensures anything dumped out will be caught

$GLOBALS['doNotRequireCommon'];
include_once '../library/config.php';
include_once '../library/database.php';
//include_once '../library/common.php';
//include_once '../library/category-functions.php';
set_error_handler("myErrorHandler");

session_start();

if (!function_exists('isMobile2')) {
            function isMobile2() {
            return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
}


	

?>

<app id="RecordPtHx">
<!-- <img src="../images/CapturedMap.png" width="100%" class="hidden"> -->
<style id="dynamic"></style>


</script>

 <style>
.ui-menu { width: 200px; }
.ui-widget-header { padding: 0.2em; }
#map { height: 100%; }

.wordwrap { 
   white-space: pre-wrap;      /* CSS3 */   
   white-space: -moz-pre-wrap; /* Firefox */    
   white-space: -pre-wrap;     /* Opera <7 */   
   white-space: -o-pre-wrap;   /* Opera 7 */    
   word-wrap: break-word;      /* IE */
}

.modal-ku {
  width: 750px;
  margin: auto;
}

</style>

<link href="../include/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../include/bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
<link href="../include/bootstrap/css/shop.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="../include/bootstrap/jquery-ui-1.11.4.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../include/bootstrap/css/This.css" />
<script src="../include/bootstrap/js/jquery-1.11.0.min.js"></script>
<script src="../include/bootstrap/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script language="JavaScript" type="text/javascript" src="../include/bootstrap/js/bootstrap.min.js"></script>



<!--<body>--><br />
<script>

			//var map;
			var marker = {};
			var poly;

</script>
<br />

	<div id="CurrentBillWrap" class="brown_round_rect" style="min-height:100px;">

		<div id="CurrentBill" style="padding:5px;" width="75%">About Homeopathy in Thailand
        </div>

        <div style="font-size:5em; white-space:nowrap">
        Homeo Map
        </div>

        
      </div>

   <br />

<div class="brown_round_rect" style="height:2000px;">
<member class="col-md-12 col-lg-3" style="padding:35px;">
		<div class="ui-widget-content" style="border-radius:15px; text-align:center; overflow:scroll; max-height:600px; height:600px; padding:15px;">
		
		

<?php

	$sql = "SELECT DISTINCT(userId), record_id FROM line_sms
		WHERE sms_txt='NEW INPUT' ORDER BY record_id DESC;";   
	//echo '$sql = '.$sql.'<hr>';
	$result = dbQuery($sql);

	$arrExistedRec = array();
	$arrMapID2Locale = array();
	$occupation = 'n/a';
	$userMark = array("Lat"=>"13.881210","Lng"=>"100.643804");

	while($row = dbFetchAssoc($result)){
		extract($row);

		if(!array_key_exists($userId,$arrExistedRec)){

			$arrLookup = array('name','tel');
			$rtnArr = getUser($arrLookup,$record_id,$userId);
			//$rtnArr = 'Hi';

			if(isMobile2()){
				$fontEm = ' font-size:2em;';
			}
			else{
				$fontEm = '';
			}


			echo '<pre style="align:center'.$fontEm.'">';
			
			foreach($rtnArr as $key=>$value){

				if(substr($value,0,33) == 'events":[:postback","replyToken'){
					goto skipVal;
				}

				switch(true){
					case $key == 'name' && !is_numeric($key):
						//if($rtnArr['name'] != 'Anonymous'){
							//echo $key.':'.$value.'<br>';
						//}
						switch(true){
							case isset($rtnArr['license id']):
								$memIcon = "../images/DocIcon.png";
								$btnColor = 'btn-success';
								$occupation = 'MD';
								break;
							case isset($rtnArr['lay exp']):
								$memIcon = "../images/NonMDicon.png";
								$btnColor = 'btn-primary';
								$occupation = 'Lay';
								break;
							case isset($rtnArr['user exp']):
								$memIcon = "../images/UserIcon.png";
								$btnColor = 'btn-default';
								$occupation = 'User';
								break;
							default:
								$memIcon = "../images/UserIcon.png";
								$btnColor = 'btn-default';
								break;
						}

						

						echo '<div class="btn '.$btnColor.' btn-block memberBtn" rec_id="'.$record_id.'" style="padding:10px;'.$fontEm.'">&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$memIcon.'" width="30px">&nbsp; '.$value.'</div><br>';
						
						break;
					case $key == 'user homeo caption' && !is_numeric($key):
						//echo "I'M A LAY PRESCRIBER<br>";

						//if($rtnArr['name'] != 'Anonymous'){
							echo '<div class="user_caption wordwrap">';
							echo '<br>สิ่งที่คุณอยากบอกเกี่ยวกับ HOMEOPATHY:<br>';
							echo '<div class="ui-state-highlight wordwrap" style="padding:10px;'.$fontEm.'">'.$value.'</div><br>';
							echo '</div>';
						//}

						
						break;
					case $key == 'license id' && !is_numeric($key):
						//echo "I'M A LAY PRESCRIBER<br>";

						//if($rtnArr['name'] != 'Anonymous'){
							//echo '<br><div class="btn btn-success btn-sm" style="width:100%; padding:10px;">MEDICAL PRESCRIBER</div><br>';
						//}

						
						break;
					case $key == 'lay exp' && !is_numeric($key):
						//echo "I'M A LAY PRESCRIBER<br>";

						//if($rtnArr['name'] != 'Anonymous'){
						if($key.strtolower == 'x'){
							echo '<br>ประสบการณ์ในฐานะ PRESCRIBER:<br><div class="ui-state-highlight wordwrap" style="padding:10px;'.$fontEm.'">'.$value.'</div><br>';
						}
						//}

						
						break;
					case $key == 'user exp' && !is_numeric($key):
						//echo "I'M A LAY PRESCRIBER<br>";

						if($rtnArr['name'] != 'Anonymous'){
							//echo 'ประสบการณ์ในฐานะ LAY PRESCRIBER:<br><div class="ui-state-highlight" style="padding:10px;">'.$value.'</div><br>';
						}

						
						break;
					case $key == 'tel':
						if($rtnArr['name'] != 'Anonymous'){
							//echo $key.':'.$value.'<br>';
						}
						break;
					case $key == 'email':
						if($rtnArr['name'] != 'Anonymous'){
							//echo $key.':'.$value.'<br>';
						}
						break;
					case $key == 'Line ID':
						if($rtnArr['name'] != 'Anonymous'){
							//echo $key.':'.$value.'<br>';
						}
						break;
					case $key == 'FB':
						if($rtnArr['name'] != 'Anonymous'){
							//echo $key.':'.$value.'<br>';
						}
						break;
					case $key == 'eval':
						switch($value){
							case 'eval,3':
								$face = "../images/Good.png";
								break;
							case 'eval,2':
								$face = "../images/Neutral.png";
								break;
							case 'eval,1':
								$face = "../images/Bad.png";
								break;
							case 'eval,X':
								$face = "../images/Qface.png";
								break;
						}
						echo 'I FEEL &nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$face.'" width="30px"> FOR HOMEOPATHY<br>';
						break;
					case $key == 'my location':
						if($value['my location']['lat'] != 'N'){
							$mapId = $value['my location']['mapId'];
							$lat = $value['my location']['lat'];
							$long = $value['my location']['long'];

							

							echo 'location <div class="Location" mapId="'.$mapId.'"> <lat>'.$lat.'</lat>,<long>'.$long.'</div></long><br>';
							$arrMapID2Locale[] = array(
								'mapId'=>$mapId,
								'coor'=>($lat.','.$long),
								'memIcon'=>$memIcon,
								'face'=>$face,
								'occupation'=>$occupation,
								'caption'=>$rtnArr['user homeo caption'],
								'name'=>$rtnArr['name'],
								'rec_id'=>$record_id
							);

							if($mapId == $_GET['mapId']){
								$userMark = array("Lat"=>$lat,"Lng"=>$long);
							}
							else{
								//$userMark = array("Lat"=>"13.881210","Lng"=>"100.643804");
							}
						}
						//echo var_dump($value).'<br>';
						break;
					default:
						if($value != 'SUM DATA'){
							echo $key.':'.$value.'<br>';
						}
						break;
				}
			}
			skipVal:
			echo '</pre></td>';



			$arrExistedRec[$userId] = $record_id;

		}
	}

?>
		

		
		</div>
</member>
<div id="addressInfo" class="" style="min-height:400px; padding:15px;">

        <br />
		<map class="col-md-12 col-lg-9">
        <div id="adressInfo" class="ui-widget-content" style="border-top-left-radius:15px; border-top-right-radius:15px; text-align:center;">ข้อมูลการติดต่อ และที่อยู่</div>

        <!--<iframe width="600" height="450" frameborder="0" style="border:0" 
        src="https://www.google.com/maps/embed/v1/place?q=13.666441%2C100.635033&key=AIzaSyDj5QORstxEudOFeHq-me7M53Xaml6pAWk" allowfullscreen>
        </iframe>-->
		
        <div id="map" style="height:600px; width:100%; background-color:#FFF">
		</div>
		


        <script>
		


		//var map;
		function initMap() {
		  console.log('initMap');
		  // Create the map.
		  window.map = undefined;
		  window.mapStyle = [
								{
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#ebe3cd"
									}
									]
								},
								{
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#523735"
									}
									]
								},
								{
									"elementType": "labels.text.stroke",
									"stylers": [
									{
										"color": "#f5f1e6"
									}
									]
								},
								{
									"featureType": "administrative",
									"elementType": "geometry.stroke",
									"stylers": [
									{
										"color": "#c9b2a6"
									}
									]
								},
								{
									"featureType": "administrative.land_parcel",
									"elementType": "geometry.stroke",
									"stylers": [
									{
										"color": "#dcd2be"
									}
									]
								},
								{
									"featureType": "administrative.land_parcel",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#ae9e90"
									}
									]
								},
								{
									"featureType": "landscape.natural",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#dfd2ae"
									}
									]
								},
								{
									"featureType": "poi",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#dfd2ae"
									}
									]
								},
								{
									"featureType": "poi",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#93817c"
									}
									]
								},
								{
									"featureType": "poi.park",
									"elementType": "geometry.fill",
									"stylers": [
									{
										"color": "#a5b076"
									}
									]
								},
								{
									"featureType": "poi.park",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#447530"
									}
									]
								},
								{
									"featureType": "road",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#f5f1e6"
									}
									]
								},
								{
									"featureType": "road.arterial",
									"stylers": [
									{
										"visibility": "off"
									}
									]
								},
								{
									"featureType": "road.arterial",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#fdfcf8"
									}
									]
								},
								{
									"featureType": "road.highway",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#f8c967"
									}
									]
								},
								{
									"featureType": "road.highway",
									"elementType": "geometry.stroke",
									"stylers": [
									{
										"color": "#e9bc62"
									}
									]
								},
								{
									"featureType": "road.highway",
									"elementType": "labels",
									"stylers": [
									{
										"visibility": "off"
									}
									]
								},
								{
									"featureType": "road.highway.controlled_access",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#e98d58"
									}
									]
								},
								{
									"featureType": "road.highway.controlled_access",
									"elementType": "geometry.stroke",
									"stylers": [
									{
										"color": "#db8555"
									}
									]
								},
								{
									"featureType": "road.local",
									"stylers": [
									{
										"visibility": "off"
									}
									]
								},
								{
									"featureType": "road.local",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#806b63"
									}
									]
								},
								{
									"featureType": "transit.line",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#dfd2ae"
									}
									]
								},
								{
									"featureType": "transit.line",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#8f7d77"
									}
									]
								},
								{
									"featureType": "transit.line",
									"elementType": "labels.text.stroke",
									"stylers": [
									{
										"color": "#ebe3cd"
									}
									]
								},
								{
									"featureType": "transit.station",
									"elementType": "geometry",
									"stylers": [
									{
										"color": "#dfd2ae"
									}
									]
								},
								{
									"featureType": "water",
									"elementType": "geometry.fill",
									"stylers": [
									{
										"color": "#b9d3c2"
									}
									]
								},
								{
									"featureType": "water",
									"elementType": "labels.text.fill",
									"stylers": [
									{
										"color": "#92998d"
									}
									]
								}
								];

		  window.map = new google.maps.Map(document.getElementById('map'), {
			<?php 
				if(isMobile2()){
					echo "zoom : 14";
				}
				else{
					echo "zoom : 11";
				}
			
			?>,
			center: {lat: <?php echo $userMark['Lat']; ?>, lng: <?php echo $userMark['Lng']; ?>},
			styles : window.mapStyle,
			//mapTypeId: google.maps.MapTypeId.TERRAIN,
			position: google.maps.ControlPosition.TOP_CENTER
		  });
		  
		  //center for HF bangna = center: {lat: 13.700000, lng: 100.550000},
		  
		  var image = 'logo.png'; //locate at same folder of this file
		  //var myLatlng = new google.maps.LatLng(13.666441,100.635033); //HF bangna

		  
		  var myLatlng = new google.maps.LatLng(<?php echo $userMark['Lat'].','.$userMark['Lng']; ?>); //Venita clinic 13.881210,100.643804
		  
		  var mark = new google.maps.Marker({
			  position: myLatlng,
			  title:"Me",
			  scaledSize: new google.maps.Size(45, 45),
			  icon: '../images/markMe.png'
		  });
          mark.setMap(window.map); marker['HQ']=mark; //mark.addListener('click', toggleBounce());
		  
		  <?php
			foreach($arrMapID2Locale as $key=>$locale){
				$mKey = $key+2;

				//if($locale['occupation'] == 'User'){
				if($locale['memIcon'] == "../images/UserIcon.png"){
					$icon = $locale['face'];
				}
				else{
					$icon = $locale['memIcon'];
				}
				
				echo "var myLatlng$mKey = new google.maps.LatLng({$locale['coor']});"; //Venita clinic
				echo "var mark$mKey = new google.maps.Marker({";
				echo "position: myLatlng$mKey,";
				echo "title:'{$locale['name']}',";
				echo "icon: {";
						echo "url: '$icon',";
						echo "scaledSize: new google.maps.Size(35, 35)";
				echo "}";
				echo "});";
				echo "mark$mKey.setMap(window.map); marker['$mKey']=mark$mKey;"; //mark2.addListener('click', toggleBounce());";
				echo "google.maps.event.addListener(mark$mKey, 'click', function(event) {";
				//echo "//removeMarker(mark$mKey);";
				//echo "//poly.setMap(null);";
				//echo "//toggleBounce(mark$mKey)";
					echo "$('#AlertMsg').text('RECORD ID {$locale['rec_id']}: {$locale['name']}');";
					echo "popMemberDlg({$locale['rec_id']}, {$locale['coor']});";
					echo "$('#myModal').modal();";
				echo "});";

				echo "marker['{$locale['rec_id']}'] = mark$mKey;";
			}
		?>

		  //$('#mRemark').text('USER GIVEN DETAIL');
          /* var myLatlng3 = new google.maps.LatLng(13.901400,100.543700); //Venita clinic
		  var mark3 = new google.maps.Marker({
			  position: myLatlng3,
			  title:"mark (3)",
              icon: {
                url: 'http://maps.google.com/mapfiles/ms/micons/yellow-dot.png'
              }
		  });
          mark3.setMap(map); marker['3']=mark3; //mark3.addListener('click', toggleBounce()); */

          
		  // To add the marker to the map, call setMap();
		  
		
		  // Construct the circle for each value in citymap.
		  // Note: We scale the area of the circle based on the population.
		  
		  /*for (var city in citymap) {
			// Add the circle for this city to the map.
			var cityCircle = new google.maps.Circle({
			  strokeColor: '#FF0000',
			  strokeOpacity: 0,
			  strokeWeight: 2,
			  fillColor: citymap[city].color,
			  fillOpacity: 0.2,
			  map: map,
			  center: citymap[city].center,
			  radius: citymap[city].km * 1000
			}); //radius in m. 10000 m. = 10km
			
			google.maps.event.addListener(cityCircle, 'click', function(event) {
			   placeMarker(event.latLng);
			});
		  }*/
		  
		  trafficLayer = new google.maps.TrafficLayer();
    	  trafficLayer.setMap(window.map);
		  
		  google.maps.event.addListener(window.map, 'click', function(event) {
			 placeMarker(event.latLng);
		  });

		  gGoogle = google;
		}
		
	
	
	function placeMarker(pos) {
		var count = Object.keys(marker).length;
		console.log('pos =v ');
		console.dir(pos);
		
		var location = { };
		var H;
		var L;
		
		
		if(typeof(pos) == 'string'){
			
			if (typeof marker['dropPoint'] != "undefined") { 
				removeMarker(marker['dropPoint']); //REMOVE MARKER IF CLICK ON IT
				poly.setMap(null);
				count = Object.keys(marker).length;
				console.log('remove previous mark');
			}
			
			
			var pos2;
			pos2 = pos.split(',');
			if(pos2.length < 2)
			{ return; };
			
			if(pos2[1] == ""){
				return;
			}
			
			H = parseFloat(pos2[0]);
			L = parseFloat(pos2[1]);
			
			console.log('>>> H = ' + pos2[1] +', L = ' + pos2[0]);
			
			location = {lat: H, lng: L};
			console.log('gonna set locale ' + H + ',' + L);
		}
		else
		{
			location = pos;
		}

		if(count < 2 || true){
		  var mark = new google.maps.Marker({
			  position: location, 
			  map: map,
			  title: 'จุดสุ่งของ (' + count + ')'
		  });
		  
		  
		  
		  marker['dropPoint']=mark;
		  
		  
		  google.maps.event.addListener(mark, 'click', function(event) {
			 removeMarker(mark);
			 poly.setMap(null);
		  });
		  
		  
		  
		  poly = new google.maps.Polyline({
			strokeColor: '#FF0000',
			strokeOpacity: 1.0,
			strokeWeight: 3,
			map: window.map,
		  });
		  
		  
		  
		  update();
		}
		else
		{
			alert('ท่านต้องลบที่จัดส่งเดิมก่อน');	
		}
	}
	
	function update() {
	  var path = [marker['HQ'].getPosition(), marker['dropPoint'].getPosition()];
	  //poly.setPath(path); //DRAW PATH FROM CENTER
	  
	  var Home = marker['dropPoint'].getPosition();
	  
	  var HomeLat = Home[Object.keys(Home)[0]];
	  var HomeLong = Home[Object.keys(Home)[1]];
	  
	  /*geodesicPoly.setPath(path);
	  var heading = google.maps.geometry.spherical.computeHeading(path[0], path[1]);
	  document.getElementById('heading').value = heading;
	  document.getElementById('origin').value = path[0].toString();
	  document.getElementById('destination').value = path[1].toString();*/
	  
	  polyLengthInMeters = google.maps.geometry.spherical.computeLength(poly.getPath().getArray());
	  $('#distance').text(polyLengthInMeters/1000);
	  $('#mLatLong').text(HomeLat + ',' + HomeLong);
	}
	
	function removeMarker(theMark){
		theMark.setMap(null);
		
		delete marker.dropPoint;
	}

    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2jcfS7eGfzwsPteQnu4ijk_M-42EIQV4&callback=initMap">
    </script>
    	<div class="ui-widget-content" style="border-bottom-left-radius:15px; border-bottom-right-radius:15px; text-align:center;">
		<div>ระยะทาง <a id="distance">?</a> km</div>
        GPRS set Google Map<a id="mLatLong" contenteditable="true">13.881210,100.643804</a>
        <a id="setGoogle" contenteditable="false" href="https://goo.gl/maps/Stywtyd87eu"  target="_blank">คลิก Link เพื่อใช้ Google Map นำทาง</a>
        </div>
	</map>




<script>

/* function toggleBounce(tMarker) {
		if (tMarker.getAnimation() !== null) {
		tMarker.setAnimation(null);
	} else {
		tMarker.setAnimation(google.maps.Animation.BOUNCE);
	} 

	//alert('marker clicker');
	} */
$(document).ready(function(){
	$('#mLatLong').bind('keyup',function(e){
		var code = e.which; // recommended to use e.which, it's normalized across browsers
		if(code==13 || code==86){
			placeMarker($('#mLatLong').text());
			alert('YYY');
		}

	});

	

	console.log('there are ' + $('.Location').length + ' locations to display');

	$('.memberBtn').click(function(){
		var $itsPre = $(this).closest('pre');
		console.log($itsPre.text());
		var $itsLat = $itsPre.find('lat');
		var $itsLng = $itsPre.find('long');

		if($itsLat.length > 0){
			console.log('use Member Lat:Lng');
			popMemberDlg($(this).attr('rec_id'), ($itsLat.text() + ',' + $itsLng.text()));
		}
		else{
			console.log('use USer Lat:Lng')
			popMemberDlg($(this).attr('rec_id'), (<?php echo $userMark['Lat'].','.$userMark['Lng'] ?>));
		}
	});

	$('#mapFullScreen').click(function(){

		var map = document.querySelector(".gm-style");
		map.requestFullscreen();

	})
	
});

function moveToLocation(lat, lng){
	const center = new google.maps.LatLng(lat, lng);
	// using global variable:
	window.map.panTo(center);
}

var $htmlDlg = undefined;

function popMemberDlg(rec_id, coorStr){

		//alert('coorStr = ' + coorStr)
		try{
			sCoor = coorStr.split(',');

			moveToLocation(sCoor[0], sCoor[1]);
			
			marker[rec_id].setAnimation(4);

			
		}
		catch(e){
			moveToLocation(<?php echo $userMark['Lat']; ?>, <?php echo $userMark['Lng']; ?>);
		}

		<?php 
		
			if(isMobile2()){
				$fontEm2 = 'font-size:4em;';
			}
			else{
				$fontEm2 = 'font-size:2.5em;';
			}
		
		
		?>

        $htmlDlg = $('<div>',{
			id: rec_id,
			style: '<?php echo $fontEm2; ?>'
		})

		/* $htmlDlg = $htmlDlg + '<br>License ID : <data id="license"> - </dat><br>';
		$htmlDlg = $htmlDlg + '<br><dat id="eval"></dat><br>';
		$htmlDlg = $htmlDlg + '<br>Experience : <dat id="share_exp"> - </dat><br>';
		$htmlDlg = $htmlDlg + '<br>Comment : <dat id="user homeo caption"> - </dat><br>';
		 */

        //$url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsOfUserByRecId.php';
        $url = 'http://localhost:82/Qweb/site1_wiztech/WiztechSolution/include/smsOfUserByRecID.php';
        
        $.post($url,{
            RecId: rec_id,
            mapType: 'Homeo'
        },function(data){
            //console.dir($.parseJSON(data));

			var jData = $.parseJSON(data);
			var uData = {};

			$htmlDlg = $htmlDlg.append('<table><tr><td></td><td></td></tr>');

			$.each(jData, function(key, value){
				console.log(key + '=>' + value);

				

				//$.each(arrValue, function(key, value){
					uData = {
						'name': 'ชื่อสมาชิก',
						'share exp': 'Sharing',
						'email': 'E mail',
						'tel': 'โทร',
						'eval': 'ความรู้สึก',
						'user homeo caption': 'สิ่งที่อยากบอก',
						'license id': 'รหัสเวชกรรม'
					};
				//});
				//console.log (value);

				$htmlDlg = $htmlDlg.append('<tr><td><b>' + uData[key] + '</b></td><td>' + value + '</td></tr>');
			})

			

            //$htmlDlg = '\nReturn ' + data;
        })

		$("#AlertHeader").text('MEMBER INFO');
        $("#AlertMsg").html($htmlDlg);
        $("#myModal").modal();
}

</script>



<detail class="col-md-12 col-lg-12" style="padding:15px;">

	<br>
	<div class="btn btn-default btn-block" style="font-size:4em;" id="mapFullScreen">SHOW MAP IN FULL SCREEN</div>
	<br>

	<div class="col-md-12 col-lg-12" style="padding:15px">
		<b>MEMBER TYPE</b><br>
		<br>
		<img src="../images/DocIcon.png" width="40px">&nbsp;&nbsp;คือผู้ที่เป็นหมอ เภสัชกร หรือผู้มีใบประกอบโรคศิลป์<br>
		<img src="../images/NonMDicon.png" width="40px">&nbsp;&nbsp;คือผู้ผ่านการอบรม Homeopathy และมีการสั่งใช้ยารักษาคนใกล้ตัว<br>
		<img src="../images/UserIcon.png" width="40px">&nbsp;&nbsp;คือผู้ที่รู้จัก Homeopathy และร่วมแชร์ความคิดเห็น<br>
		<hr>
		<b>EVALUATION TYPE</b><br>
		<br>
		<img src="../images/Good.png" width="40px">&nbsp;&nbsp;รู้สึกประทับใจกับ Homeopathy<br>
		<img src="../images/Neutral.png" width="40px">&nbsp;&nbsp;รู้สึกกลางๆ กับ Homeopathy<br>
		<img src="../images/Bad.png" width="40px">&nbsp;&nbsp;รู้สึกแย่ กับ Homeopathy<br>
		<img src="../images/Qface.png" width="40px">&nbsp;&nbsp;ยังไม่มีข้อมูล หรือยังไม่เข้าใจ Homeopathy<br>
	</div>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg modal-ku">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="AlertHeader">Modal Header</h4>
        </div>
        <div class="modal-body" id="AlertMsg">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End of Modal -->
  




		<!-- รายละเอียดเกี่ยวกับผู้ให้ความเห็นนี้ &nbsp;&nbsp;&nbsp;<a id="CObutt"><btn id="btn">[+]</btn></a> -->
        <!-- <div style="min-height:300px; text-align:left; padding:20px; font-size:2em;" id="mRemark" class="white_round_rect" contenteditable="false" width="100%">
         -->  

        
		</div> <!--CurrentBill-->
</detail>

	<br />
    <br />
    <br />
    <br />
	<br />
    <br />
    <br />
    <br />
	<br />
    <br />
    <br />
    <br />
	<br />
    <br />
    <br />
    <br />

</div>

	




<br />

<!--</body>-->
<!--</html>-->
</app>

<?php

	function getUser($arrKW, $SetFirstRec, $userId){
	
		$sql = "SELECT sms_txt FROM line_sms WHERE record_id >= $SetFirstRec 
		AND userId = '$userId' AND sms_txt NOT LIKE '%\"WTH\"%' ORDER BY record_id ASC LIMIT 30;";   
		 
		//echo '$sql = '.$sql.'<hr>';
		$result = dbQuery($sql);

		$arrExistedRec = array();
		$rtnArr = array();
		$newInpMark = false;

		while($row = dbFetchAssoc($result)){
			extract($row);

			if( array_key_exists('my location', json_decode($sms_txt,true)) ){
				$latLong = json_decode($sms_txt,true);
				//echo var_dump($latLong);
				$rtnArr['my location'] = $latLong;
			}
			else {
				//Extra code for json that escape detection
				$sms_txt = str_replace('{"','{ "', $sms_txt);
				$sms_txt = str_replace('"}','" }', $sms_txt);

				$fTxt = explode('":"',$sms_txt);

				

				$f2Txt = explode('" }',$fTxt[1])[0];

				$possibleKey = explode('{ "', $fTxt[0])[1];
				$possibleVal = $f2Txt;

				

				if($possibleKey == ''){
					$defKey = count($rtnArr);
					$rtnArr[$defKey] = $sms_txt;
					$rowKW = $sms_txt;

					if($sms_txt == 'SUM DATA'){
						goto EndQuery;
					}

					if($sms_txt == 'NEW INPUT'){
						//Ignore
						if(!$newInpMark){
							$newInpMark = true;
						}
						else{
							$rtnArr = array('ERROR'=>'in complete record set');
							$newInpMark = false;
							goto EndQuery;

						}
						goto Ignore;
					}
					$rtnArr[count($rtnArr)] = $rowKW;
				}
				else{
					$defKey = $possibleKey;
					//$rowKW = $possibleVal;
					$rtnArr[$defKey] = $possibleVal;

				}

				
				/* if($smsTxt == 'SUM DATA'){
					goto EndQuery;
				}

				if($rowKW == 'NEW INPUT'){
					//Ignore
					if(!$newInpMark){
						$newInpMark = true;
					}
					else{
						$rtnArr = array('ERROR'=>'in complete record set');
						$newInpMark = false;
						goto EndQuery;

					}
					goto Ignore;
				} */


				

				//$rtnArr[count($rtnArr)] = $rowKW;
			//}
			//if(array_key_exists(array_keys($rowKW)[0],$arrKW)){
			

			Ignore:
			
			} 
		}

EndQuery:
		//$rtnArr['sql'] = $sql;
		return $rtnArr;
	}

	function isJson($string) {
		json_decode($string);
		return (json_last_error() === JSON_ERROR_NONE);
	}

?>