<?php
session_start();

//include helper functions
include_once('../../../wp-config.php');
$ids = $_GET['ids'];
$ids = explode(",",$ids);
if ($ids)
{
	foreach ($ids as $k=>$v)
	{
		//getting option/settings from Google XML Sitemaps
		$link = get_permalink($v);
		
		echo $link."<br>";
	}
}

	
?>