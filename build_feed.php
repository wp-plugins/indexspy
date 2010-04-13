<?php
//connect to db
include_once('../../../wp-config.php');

//$current_url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."";
//$blogsense_url = explode('includes',$current_url);
//$blogsense_url = $blogsense_url[0];
$blogsense_url = SBIS_PURL;

//pull in variables
$urls = $_POST['urls'];
$urls = explode(",",$urls);
$feed_name = $_POST['feed_name'];
$feed_description = $_POST['feed_description'];
//$urls = array('http://www.cubetripods.com/?page_id=2');
//$feed_name = "dada";

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


//foreach url 
foreach ($urls as $key=>$val)
{
    $val = trim(strip_tags($val));
	$val= explode('ttp://',$val);
	$val = "http://".$val[1];
	//echo $val;exit;
	//echo 1;
    if (substr($val, -1 , 1)=='/')
	{
	  $nval = substr($val,0, -1);
	}
	else
	{
	  $nval = $val;
	}
    $chop = explode('/',$nval);
	//print_r($chop);exit;
	$count = count($chop);
	$count = $count-1;
	//echo $count;
	$last_piece = $chop[$count];
	//echo $last_piece;
	$pid = $last_piece;
	$query = "SELECT * from ".$table_prefix."posts where post_name='$pid' OR guid='$pid' OR guid='$val' LIMIT 1";
	$result = mysql_query($query);
	if (!$result){ echo $query; echo mysql_error();}
	$array = mysql_fetch_array($result);
	
	//echo $array;
	//echo count($array); 
	//print_r($array);exit;
	//echo $array['post_title']; exit;
	//build items
	$title[$key] = $array['post_title'];
	$description[$key] = strip_tags($array['post_content'], '<br><b><i><span>');
	$description[$key] = substr($description[$key], 0 ,600);
	$description[$key] = "$description[$key]...";	
	$link[$key] = $val;
	
	//echo $link[$key]; 
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