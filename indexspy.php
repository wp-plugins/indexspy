<?php
session_start();
//include helper functions
include_once('../../../wp-config.php');

$nav_url = get_option('home')."/wp-content/plugins/wp-indexspy/images/link.gif";

if ($_SESSION['indexspy'] != get_option('home'))
{
	$new_result = 1;
}
else
{
	$new_result = 0;
}

include('is_helper_functions.php');

//checks to see if grid has already been loaded
//if it has then it will take the array of urls (session['url']) and statuses (session['status') and
//use an array multisort to sort the arrays the way the ajax requests them to be sorted
//else it will build the grid for the first time
if ( $_SESSION['url'] && $_GET['sord'] && $_GET['sidx']&&$new_result==0)
{
  //echo 1; exit;
  $s_post_id = $_SESSION['post_id'];
  $s_url =  $_SESSION['url'];
  $s_status =  $_SESSION['status'];
  $sort_col = $_GET['sidx'];
  $sort_type = $_GET['sord'];
  $page = $_GET['page'];
  $limit = $_GET['rows'];
  
  
  if ($sort_col=='id')
  {
   if ($sort_type=='asc')
   {
		array_multisort($s_post_id, SORT_ASC, SORT_NUMERIC,$s_url, $s_status);
		//array_multisort($s_post_id, SORT_ASC, SORT_STRING,$s_status);
   }
   else
   {
		array_multisort($s_post_id, SORT_DESC, SORT_NUMERIC,$s_url );
		array_multisort($s_post_id, SORT_DESC, SORT_NUMERIC, $s_status);
   }
   $post_id = $s_post_id;
   $url = $s_url;
   $status = $s_status;
  }
  
  if ($sort_col=='url')
  {
   if ($sort_type=='asc')
   {
		array_multisort($s_url, SORT_ASC, SORT_STRING,$s_status);
		array_multisort($s_url, SORT_ASC, SORT_STRING,$s_post_id);
   }
   else
   {
		array_multisort($s_url, SORT_DESC, SORT_STRING,$s_status);
		array_multisort($s_url, SORT_DESC, SORT_STRING,$s_post_id);
   }
   $post_id = $s_post_id;
   $url = $s_url;
   $status = $s_status;
	//echo count($status);
  }
  //exit;
  
  if ($sort_col=='status')
  {
   if ($sort_type=='asc')
   {
		array_multisort($s_status, SORT_ASC, SORT_STRING,$s_url, $s_post_id);
   }
   else
   {
		array_multisort($s_status, SORT_DESC, SORT_STRING,$s_url, $s_post_id);
   }
   $post_id = $s_post_id;
   $url = $s_url;
   $status = $s_status;
	//echo count($status);
  }
  
  
  for ($i=0;$i<count($status);$i++)
  {
	$responce->rows[$i]['id']= $post_id[$i]; 
	$responce->rows[$i]['cell']=array($post_id[$i],$url[$i],$status[$i]);
  }
}
//else it builds the grid and creates a session of all the urls
else
{
	$page = $_GET['page'];
	$limit = $_GET['rows'];
	
	//getting option/settings from Google XML Sitemaps
	$query = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_status='publish'";
	$result = mysql_query($query);
	if (!$result){echo $query; echo mysql_error();}
	$count = mysql_num_rows($result); 
	

	while ($arr = mysql_fetch_array($result))
	{
		$post_id[] = $arr['ID'];
		$permalinks[] = get_permalink($arr['ID']);
	}
	$linkcount = count($permalinks);
	
	for($key=0;$key<$linkcount;$key++)
	{
	  if ( $key & 1 ) {
		$bgcolor="#e4ecfe";
	  } else { 
	  	$bgcolor="#ffffff";
	  }
	  
	  $url[$key] = $permalinks[$key];
	  $string = $permalinks[$key];
	  $keyphrase = "info:$permalinks[$key];";
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
	  $k=$key+1;

	  $responce->rows[$key]['id']= $post_id[$key]; 
	  $responce->rows[$key]['cell']=array($post_id[$key],$url[$key],$status[$key]);
	 
	}
}//end else
$_SESSION['post_id'] = $post_id;;	
$_SESSION['url'] = $url;
$_SESSION['status'] = $status;
$_SESSION['indexspy'] = get_option('home');
echo json_encode($responce); exit;
//echo '{"url":"http://www.blogsense-wp.com/hosted/testblog/New-Title/","status":"failure"}';
  
?>