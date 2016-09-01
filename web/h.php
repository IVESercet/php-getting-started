<?php
header('Access-Control-Allow-Origin: https://ivesercet.github.io');  
header("Access-Control-Allow-Headers: APIT , APIC");

if (isset($_POST["token"])){

	function txtread($filename){
		$a = fopen($filename, "r");
		$size = filesize($filename);
		$b = null;
		if ($size){
			$b = fread($a,$size);
		}
		fclose($a);
		return $b;
	}
	function txtwrite($filename,$body){
		$a = fopen($filename, "w");
		fwrite($a,$body);
	}

	$bg = imagecreatefrompng('img/bg.png');
	$data = imagecreatefromstring(base64_decode($_POST["token"]));
	
	//if (imagesx($data)==420&&imagesy($data)==344){
		imagecopy($bg, $data, 80, 50, 0, 0, imagesx($data), imagesy($data));
		$album = array(
			"所有" => 1647252092205778,
			"柴灣" => 1757683311162655,
			"黃克競" => 1757678501163136,
			"葵涌" => 1757684181162568,
			"觀塘" => 1757684364495883,
			"李惠利" => 1757684561162530,
			"香港知專設計學院" => 1757684707829182,
			"摩理臣山" => 1757828421148144,
			"沙田" => 1757684834495836,
			"青衣" => 1757685057829147,
			"屯門" => 1757682444496075
		);
		$id = $album[urldecode(base64_decode($_SERVER['HTTP_APIC']))];
		if (isset($album[urldecode(base64_decode($_SERVER['HTTP_APIC']))])){
			$number = txtread( __DIR__."/record/last.index")+1;
			txtwrite(__DIR__."/record/last.index",$number);
			
			$hash = "IVE".str_pad($number, 6, "0", STR_PAD_LEFT);
			$filename = __DIR__."/record/$hash";
			imagepng($bg, $filename);
			
			require_once "Facebook/autoload.php";
			
			$fb = new Facebook\Facebook([
			  'app_id' => '1537970036261310',
			  'app_secret' => '3dcd67017ec5ecf8075b63d0e83d8184',
			  'default_graph_version' => 'v2.5',
			  'fileUpload' => true
			]);

			$data = [
			  'message' => "#$hash",
			  'source' => $fb->fileToUpload($filename)
			];

			try {
				$response = $fb->post("/$id/photos", $data, 'EAAV2xo2vwb4BAOKpltZBI7zIbAV95srjZAS7L1FyGEQI5ZCskqI7MXcbtnPxADXQZBe4CAVxxrjQyQMwA6VQ6KGrMZCPs35aTynIMQtB5hJ0xn42dTZB4ZCLNBxczmb3pzzk1gfctmZCwQaUkbZB53K22ClQzIgY0G3QZD');
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				exit;
			}
			
			$graphNode = $response->getGraphNode();
			
			echo $graphNode['id'];
			
			txtwrite($filename,$graphNode['id']);
			imagedestroy($bg);
		}
	//}

	
	
}

?>