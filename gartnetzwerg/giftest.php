<?php



require_once 'classes/GifCreator.php';

$frames = array("http://www.sarracenia.com/photos/dionaea/dionamusci070.jpg", 
		"http://www.flowers.org.uk/wp-content/uploads/2012/12/Pitcher-Plant.jpg", 	
		"http://i1110.photobucket.com/albums/h443/meizzwang/IMG_6847.jpg");
$frames_local = array('plants/plant01.jpg',
		'plants/plant02.jpg',
		'plants/plant03.jpg');
$durations = array(50, 50, 50);

$gc = new GifCreator\GifCreator();
try{
	
	$gc->create($frames_local, $durations, 0);
	$gif_binary = $gc->getGif();
	file_put_contents('animated_picture.gif', $gif_binary);
}
catch (\Exception $ex){
	echo $ex->getMessage()."\n";
}



?>