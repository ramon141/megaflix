<?php
	include_once("bloggerClass.php");
	if(isset($_GET['url'])){
		$stream = new bloggerStream();
$stream->loadApi($_GET['url']);
$videoLink = $stream->grab(); //direct mp4 url
$posterImg = $stream->poster(); // poster image url
	}
	echo $videoLink;
?>
