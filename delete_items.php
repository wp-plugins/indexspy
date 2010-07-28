<?php
session_start();

//include helper functions
include_once('../../../wp-config.php');
$ids = $_POST['ids'];
$ids = explode(',',$ids);
if ($ids)
{
	foreach ($ids as $k=>$v)
	{
		$query = "UPDATE ".$wpdb->prefix."posts SET post_status='trash' WHERE ID='$v' ";
		$result = mysql_query($query);
		if (!$result){echo $query; echo mysql_error();}
		echo $v.",";
	}
}

$_SESSION['indexspy'] = "renew";
	
?>