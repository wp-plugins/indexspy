<?php
//connect to db
include_once('../../../wp-config.php');
function writeFile($path,$content)
{
	$handle = fopen($path, "w");
	if (fwrite($handle, $content) === FALSE) {
		echo '<tr><td>can not write on '.$path.'</td></tr>';
		exit;
	}
}
function getItem($title,$link,$description)
{
	$item='<item>';
	$item.="<title><![CDATA[$title]]></title>";
	$item.="<link><![CDATA[$link]]></link>";
	$item.="<description><![CDATA[$description]]></description>";
	$item.='<pubDate>'.date('r').'</pubDate>';
	$item.='</item>';
	return $item;
}

$blogsense_url = SBIS_PURL;
//pull in variables

$ids = $_POST['ids'];
$ids = explode(",",$ids);

$feed_name = $_POST['feed_name'];
$feed_description = $_POST['feed_description'];

//foreach url 
foreach ($ids as $key=>$val)
{
	//getting option/settings from Google XML Sitemaps
	$query = "SELECT * FROM ".$wpdb->prefix."posts WHERE ID='$val'";
	$result = mysql_query($query);
	if (!$result){echo $query; echo mysql_error();}
	$count = mysql_num_rows($result); 
	

	while ($arr = mysql_fetch_array($result))
	{
		$link[$key] = get_permalink($val);
		$title[$key] = $arr['post_title'];
		$description[$key] = strip_tags($arr['post_content'], '<br><b><i><span>');
		$description[$key] = substr($description[$key], 0 ,600);
		$description[$key] = "$description[$key]...";	

	}
}

$feed_name_prepare = str_replace(" ","_",$feed_name);
//build and save xml file
$xml = '<?xml version="1.0" encoding="utf-8"?>';  
$xml.='<rss version="2.0">';  
$xml.='<channel> ';
$xml.="<title>$feed_name</title>";
$xml.="<link><![CDATA[$blogsense_url/my-feeds/$feed_name_prepare.xml]]></link>";
$xml.="<description>$feed_description</description>"; 
$xml.='<language>en-us</language>';
//echo $titles[$a];exit;	
//echo $item; exit;
foreach($title as $a=>$b)
{
$item = getItem($title[$a],$link[$a],$description[$a]);
$xml.=$item;												
				
}
$xml.='</channel></rss>';

$filename = SBIS_PPATH."/my-feeds/$feed_name_prepare.xml";	
writeFile($filename,$xml);
echo "".$blogsense_url."/my-feeds/".$feed_name_prepare.".xml";
 
?>