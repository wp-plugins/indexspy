<?php

function str_replace_once($remove , $replace , $string)
{
	// Looks for the first occurence of $needle in $haystack
	// and replaces it with $replace.
	$pos = strpos($string, $remove);
	if ($pos === false) 
	{
	// Nothing found
	return $haystack;
	}
	return substr_replace($string, $replace, $pos, strlen($remove));
}  


function get_string_between($string, $start, $end)
{
     $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);   
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
}

function array_sort($array, $key, $order)
{
	$tmp = array();
	foreach($array as $akey => $array2)
	{
		$tmp[$akey] = $array2[$key];
	}
   
	if($order == "desc")
	{arsort($tmp , SORT_NUMERIC );}
	else
	{asort($tmp , SORT_NUMERIC );}

	$tmp2 = array();       
	foreach($tmp as $key => $value)
	{
		$tmp2[$key] = $array[$key];
	}       
   
	return $tmp2;
} 

function google_search_api($args, $endpoint = 'web'){
	$referer = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]."";
	$url = "http://ajax.googleapis.com/ajax/services/search/".$endpoint;
 
	if ( !array_key_exists('v', $args) )
		$args['v'] = '1.0';
 
	$url .= '?'.http_build_query($args, '', '&');
	//echo $url;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	$string = curl_exec($ch);
	curl_close($ch);
	//echo $string; exit;
	
	if (strstr($string, 'GwebSearch'))
	{
	  return 1;
	}
	else
	{
	  return 2;
	}
}


function prepare_tags($array)
{
   $trash .="after,before,when,while,since,until,although,though,even,while,if,unless,only,case,that,this,because,since,now,as,in,on,around,to,I,he,she,it,they,them,both,either,";
   $trash .="and,so,top,most,best,&,inside,for,their,from,one,two,three,four,five,six,seven,eight,nine,ten,1,2,3,4,5,6,7,8,9,0,user,inc,is,isn't,are,aren't,do,don't,does,anyone,really,-,";
   $trash .="too,over,under,into,the,a,an,my,mine,against,inbetween,me,~,*,was,you,with,your,will,win,by";
   $trash = explode(",", $trash);
   foreach ($array as $key=>$value)
   {
     $replace =array(' ','|','&','*','%','$','#','@','~','/','amp;','.',';',':','?','!','"','(',')','[',']',',','+');
     $array[$key] = str_replace($replace, "",$value);
     foreach ($trash as $k=>$v)
	 {
	     $value= strtolower($value);
		 $v = strtolower($v);
		 if ($value==$v)
		 {
		  unset($array[$key]);
		 }
		 
	 }
   }
   $array = array_filter($array);   
   shuffle($array);
   return  $array;
}

function get_meta($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$url");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	$string = curl_exec($ch);
	curl_close($ch);
	
	$title = get_string_between($string, "<title>" ,"</title>");
	$tags = explode(" ",$title);			
	$tags = prepare_tags($tags);
	$comments = "";
	return array($title,$comments,$tags);
}

function flush_work (){
        
	flush();
	ob_flush();
	flush();
	ob_flush();
	flush();
	ob_flush();
    
}


?>