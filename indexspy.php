<?php
session_start();
//include helper functions
include_once('../../../wp-config.php');

$nav_url = get_option('home')."/wp-content/plugins/indexspy/images/link.gif";

include('is_helper_functions.php');

//checks to see if grid has already been loaded
//if it has then it will take the array of urls (session['url']) and statuses (session['status') and
//use an array multisort to sort the arrays the way the ajax requests them to be sorted
//else it will build the grid for the first time
if ( $_SESSION['url'] && $_GET['sord'] && $_GET['sidx'] )
{
  $s_url =  $_SESSION['url'];
  $s_status =  $_SESSION['status'];
  $sort_col = $_GET['sidx'];
  $sort_type = $_GET['sord'];
  
  if ($sort_col=='url')
  {
   if ($sort_type=='asc')
   {
		array_multisort($s_url, SORT_ASC, SORT_STRING,$s_status);
   }
   else
   {
		array_multisort($s_url, SORT_DESC, SORT_STRING,$s_status);
   }
   $url = $s_url;
   $status = $s_status;
	//echo count($status);
  }
  //exit;
  
  if ($sort_col=='status')
  {
   if ($sort_type=='asc')
   {
		array_multisort($s_status, SORT_ASC, SORT_STRING,$s_url);
   }
   else
   {
		array_multisort($s_status, SORT_DESC, SORT_STRING,$s_url);
   }
   $url = $s_url;
   $status = $s_status;
	//echo count($status);
  }
  for ($i=0;$i<count($status);$i++)
  {
	$responce->rows[$i]['id']= $url[$i]; 
	$responce->rows[$i]['cell']=array($url[$i],$status[$i]);
  }
}
//else it builds the grid and creates a session of all the urls
else
{
	//sitemap url  here
	$sitemap_url = $_GET['sitemap'];
	
	 //get xml file into string
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$sitemap_url");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	$string = curl_exec($ch);
	if (!$string)
    {
	    $string = file_get_contents($sitemap_url);
	}

	
	for($key=0;$key<$linkcount;$key++)
	{
	  if ( $key & 1 ) {
		$bgcolor="#e4ecfe";
	  } else { 
	  	$bgcolor="#ffffff";
	  }
	  
	  $url[$key] = get_string_between($string, "<loc>", "</loc>");
	  $string = str_replace("<loc>$url[$key]</loc>", "", $string);
	  $keyphrase = "info:$url[$key]";
	  $status[$key] = google_search_api(array(
						"q" => "$keyphrase",
					  ));		
	  if ($status[$key]==1)
	  {
		 $status[$key] = '<font color=green><i>indexed</i></font>';
	  }
	  else
	  {
		 $status[$key] = '<font color=red><i>unindexed</i></font>';
	  }
	  $url[$key] = "<a href='$url[$key]' target=_blank><img src='$nav_url' border=0></a> &nbsp;$url[$key]";
	  //$data = "[";
	  //$responce->url = $url[$key]; 
	  //$responce->status = $status[$key]; 
	  //$data .= "{url:'$url[$key]',status:'$status[$key]'},";
	  $responce->rows[$key]['id']= $url[$key]; 
	  $responce->rows[$key]['cell']=array($url[$key],$status[$key]);

	}
}//end else
	
$_SESSION['url'] = $url;
$_SESSION['status'] = $status;

echo json_encode($responce); exit;
//echo '{"url":"http://www.blogsense-wp.com/hosted/testblog/New-Title/","status":"failure"}';
  
?>

