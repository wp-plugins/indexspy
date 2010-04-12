<?php
/*
	Plugin Name: IndexSpy-WP
	Plugin URI: http://www.nohatlabs.com
	Description: Check if google indexed your pages/posts. Must have <a href='http://wordpress.org/extend/plugins/google-sitemap-generator/' target=_blank>XML Sitemap Generator Plugin</a> to work with this plugin.
	Version: 1.0
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*-------------------------------------------------------------------------------------------------------------------------------------------
					D E C L E A R I N G / I N C L U D I N G     C O N T A N T S
-------------------------------------------------------------------------------------------------------------------------------------------*/

$sbis_aul = 8;    //admin user level    // Refer http://codex.wordpress.org/Roles_and_Capabilities
define('SBIS_PURL',  get_option('siteurl').'/wp-content/plugins/wp-indexspy');
define('SBIS_PPATH', ABSPATH.'wp-content/plugins/wp-indexspy');

/*----------------------------------------------------------------------------------------------------
									A D M I N     M E N U
----------------------------------------------------------------------------------------------------*/
function esoft_app_plugin_menu() {
	global $sbis_aul;
	add_options_page('IndexSpy Options', 'Index Spy', $sbis_aul, 'wp-index-spy', 'wp_index_spy_options_page');
}
add_action('admin_menu', 'esoft_app_plugin_menu');


function wp_index_spy_options_page(){
	
	//getting option/settings from Google XML Sitemaps
	$options  = get_option('sm_options');
	$filename = $options["sm_b_filename"];
	
	$status   = get_option('sm_status');
	foreach( $status as $key => $opt ){
		if( $key == "_xmlPath" ) $xmlpath = $opt;
		if( $key == "_xmlUrl" ) $xmlurl = $opt;
	}
	
	$open = @file_get_contents($xmlurl);
	
?>

	<?php if(!$open): ?>
	
		<div class="wrap">
			<h2>Google Index Spy</h2>
			<div id="message" class="updated fade"><p class="">You Must have <a href='http://wordpress.org/extend/plugins/google-sitemap-generator/' target=_blank>XML Sitemap Generator Plugin</a> activated and a sitemap built to use this plugin.</p></div>
		</div>
		<?php exit; ?>
	
	<?php else: ?>

		<div class="wrap">
			<h2>Google Index Spy</h2>
			<center>
			    <?php
				    $link = "http://www.blogsense-wp.com/2/index_spy_ad.php";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $link);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_USERAGENT, $agents[rand(0,(count($agents)-1))]);
					$data = curl_exec($ch);
					curl_close($ch);
					echo $data;
					
				?>
				
				<div style='width:800px;text-align:right;padding-bottom:5px;' align=right>
					<span id=id_export_rss style='cursor:pointer;font-size:10px;'>[export rss]</span>
				</div>
				<table id="id_table_indexspy" style='padding:1px;font-size:10px;'></table>
				<div id="pager2"></div> 
				<div style='display:none;' id='id_dialog_create_feed' align=left>
					<center>
						<br />
						Name: <br /><input id='id_input_feed_name' size=20 /> <br />
						Description:<br /> <textarea id='id_input_feed_description' rows=2 cols=20></textarea> <br />
					</center>
				</div>
				<input type=hidden id=id_input_urls_store />
				<br /><br />
			</center>
		</div>
	
	<?php endif; ?>

<?php
	
}


/*----------------------------------------------------------------------------------------------------
							A D M I N     H E A D     S E C T I O N
----------------------------------------------------------------------------------------------------*/

function sbis_admin_head(){

	//getting option/settings from Google XML Sitemaps
	$options  = get_option('sm_options');
	$filename = $options["sm_b_filename"];
	
	$status   = get_option('sm_status');
	foreach( $status as $key => $opt ){
		if( $key == "_xmlPath" ) $xmlpath = $opt;
		if( $key == "_xmlUrl" ) $xmlurl = $opt;
	}
	
	$blogsense_url = explode($filename, $xmlurl);
	$blogsense_url = $blogsense_url[0];
	
	$page = $_GET['page'];
	
	if( $page=='wp-index-spy' ):
	?>


<!--IndexSpy plugin starts-->
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo SBIS_PURL; ?>/js/jqgrid/css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo SBIS_PURL; ?>/css/jquery-ui-1.7.2.custom.css">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SBIS_PURL; ?>/js/jqgrid/js/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="<?php echo SBIS_PURL; ?>/js/jqgrid/js/jquery.jqGrid.min.js"></script>

<script type="text/javascript">
	jQuery.jgrid.no_legacy_api = true;
	jQuery.jgrid.useJSON = true;
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function () {  
		jQuery('#id_table_indexspy').jqGrid({ 
		    url: '<?php echo SBIS_PURL; ?>/indexspy.php?sitemap=<?php echo $xmlurl; ?>', 
			datatype: 'json', 
			colNames:[
				'url',
				'status'
			], 
			colModel:[ 
				{name:'url',index:'url', width:700, align:'left'}, 
				{name:'status',index:'status',  width:100, align:'middle'} 
			],
			height: 'auto',
			viewrecords: true, 
			caption:'IndexSpy 1.5',
			multiselect: true,
			rowNum:10000, 
			//rowList:[1000,2000,3000], 
			//pager: '#pager2', 
			sortname: 'url',
			sortorder: 'desc'	
		});
		
		jQuery("#id_export_rss").click( function() 
		{ 
			var urls; 
			urls = jQuery("#id_table_indexspy").jqGrid('getGridParam','selarrrow'); 
			jQuery('#id_input_urls_store').val(urls);
			jQuery("#id_dialog_create_feed").dialog({
				autoOpen:true,
				buttons: { 						
				 'Create': function(){
						var name = jQuery('#id_input_feed_name').val();
						var description = jQuery('#id_input_feed_description').val();
						var urls = jQuery('#id_input_urls_store').val();
						var blogsense_url = '<?php echo $blogsense_url;?>';						
						jQuery.post("<?php echo SBIS_PURL; ?>/build_feed.php", { feed_name: name, feed_description:description ,	urls: urls },  function(data){
									 alert("Feed Created: " + data);
						}); 					
					 }
				},
				 close: function() {
							jQuery('#id_dialog_create_feed').dialog('hide');
							jQuery('#id_dialog_create_feed').dialog('destroy');
						},
				 width: 300,
				 height: 'auto',
				 hide: 'slide',
				 show: 'slide',
				 title: 'Create RSS Feed'
			});    
		});
	});  
                                
</script> 
<!--IndexSpy plugin ends-->


	<?php
	
	endif;
	
}
add_action('admin_head', 'sbis_admin_head');


if( !function_exists('pre_print') ):
function pre_print($var){
	echo "<pre>";
		print_r($var);
	echo "</pre>";
}
endif;


?>