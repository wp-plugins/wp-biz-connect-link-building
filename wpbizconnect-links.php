<?php 
/*  
Plugin Name:WP Biz Connect 
Plugin URI: http://WPbizconnect.com/wordpress 
Description: Fetches links that your site is to link to in our network and embeds them in a page of your choice.  Provides settings so that you can update the published page url at wpbizconnect directly from the plugin. 
Version: 1.1 
Author: WP Biz Connect
Author URI: http://WPbizconnect.com 
*/

add_action('admin_menu', 'WPbizconnect_admin_add_page');
function WPbizconnect_admin_add_page() {
add_options_page('WP Biz Connect', 'WP Biz Connect', 'manage_options', 'WPbizconnect', 'WPbizconnect_options_page');
}

function WPbizconnect_options_page() {
?>

<div>
<h2>WP Biz Connect Setup</h2>
<b>Current Status: </b>
<?php
$options = get_option('WPbizconnect_options');
$api = $options['api'];
$api=str_replace(' ','',$api);
$url = $options['url'];
$url=str_replace(' ','',$url);
$niche = $options['niche'];
$linkto = get_option('siteurl');
$linkto=str_replace(' ','',$linkto);
$setuplink = 'http://wpbizconnect.com/update?url='.$url.'&api='.$api.'&niche='.$niche.'&link='.$linkto;



/**
* Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
* array containing the HTTP server response header fields and content.
*/

function get_web_page( $url )
{
$options = array(
CURLOPT_RETURNTRANSFER => true,     // return web page
CURLOPT_HEADER         => false,    // don't return headers
CURLOPT_FOLLOWLOCATION => true,     // follow redirects
CURLOPT_ENCODING       => "",       // handle all encodings
CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13", // who am i
CURLOPT_AUTOREFERER    => true,     // set referer on redirect
CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
CURLOPT_TIMEOUT        => 120,      // timeout on response
CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
);

$ch      = curl_init( $url );
curl_setopt_array( $ch, $options );
$content = curl_exec( $ch );
$err     = curl_errno( $ch );
$errmsg  = curl_error( $ch );
$header  = curl_getinfo( $ch );

curl_close( $ch );
$header['errno']   = $err;
$header['errmsg']  = $errmsg;
$header['content'] = $content;
return $header;
}

$content=get_web_page( $setuplink);
$content=$content['content'];
$pieces = explode("****HIDEIT****", $content);
echo $pieces[0];
echo $pieces[2];
if ($pieces[1] == '1'){
?>

<h3>Your Site Has Been Approved & The Plugin is Properly Set Up</h3>
<b>Links Page URL:</b> <?php echo $url; ?><br/>
<b>Niche:</b> <?php echo $niche; ?><br/><br/>

If for whatever reason you need to have these settings changed please contact us  <a href="http://WPbizconnect.com/support/" target="_blank">HERE</a>.

<?php

} else {

?>	

<form action="options.php" method="post">
<?php settings_fields('WPbizconnect_options'); do_settings_sections('WPbizconnect'); ?>
<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes');?>" />
</form> <?php } ?>
</div>
<?php
}

add_action('admin_init', 'WPbizconnect_admin_init');
function WPbizconnect_section_text() {
}

function WPbizconnect_under_text() {
echo '';
}

function WPbizconnect_admin_init() {
register_setting( 'WPbizconnect_options', 'WPbizconnect_options', 'WPbizconnect_options_validate');
add_settings_section('WPbizconnect_main', 'Setup', 'WPbizconnect_section_text', 'WPbizconnect'); 
add_settings_section('WPbizconnect_under', '', 'WPbizconnect_under_text', 'WPbizconnect'); 
add_settings_field('WPbizconnect_api', 'API Key', 'WPbizconnect_api_setting_string', 'WPbizconnect', 'WPbizconnect_main');
add_settings_field('WPbizconnect_url', 'Display URL', 'WPbizconnect_url_setting_string', 'WPbizconnect', 'WPbizconnect_main');
add_settings_field('WPbizconnect_niche', 'Niche', 'WPbizconnect_niche_setting_string', 'WPbizconnect', 'WPbizconnect_main');
}

function WPbizconnect_api_setting_string() {
$options = get_option('WPbizconnect_options');
echo "<input id='WPbizconnect_api' name='WPbizconnect_options[api]' size='64' type='text' value='{$options['api']}' />";
}

function WPbizconnect_url_setting_string() {
$options = get_option('WPbizconnect_options');
echo "<input id='WPbizconnect_url' name='WPbizconnect_options[url]' size='64' type='text' value='{$options['url']}' />";
}

function WPbizconnect_niche_setting_string() {
$options = get_option('WPbizconnect_options');
?>

<select  <?php echo "name='WPbizconnect_options[niche]'";?> id="WPbizconnect_niche">
<option><?php echo "{$options['niche']}"; ?></option>
<?php
$setuplink = "http://wpbizconnect.com/niche";
$content=get_web_page( $setuplink);
echo $content['content'];
?>
</select>
<?php
}

function WPbizconnect_options_validate($input) {
return $input;
}

function WPbizconnect_shortcode_return() {
$options = get_option('WPbizconnect_options');
$api = $options['api'];
$url = $options['url'];
$api=str_replace(' ','',$api);
$url=str_replace(' ','',$url);
if(isset($_GET['pg'])){
$pg=$_GET['pg'];
} else {
$pg='0';
}

function get_web_page( $url )
{
$options = array(
CURLOPT_RETURNTRANSFER => true,     // return web page
CURLOPT_HEADER         => false,    // don't return headers
CURLOPT_FOLLOWLOCATION => true,     // follow redirects
CURLOPT_ENCODING       => "",       // handle all encodings
CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13", // who am i
CURLOPT_AUTOREFERER    => true,     // set referer on redirect
CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
CURLOPT_TIMEOUT        => 120,      // timeout on response
CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
);

$ch      = curl_init( $url );
curl_setopt_array( $ch, $options );
$content = curl_exec( $ch );
$err     = curl_errno( $ch );
$errmsg  = curl_error( $ch );
$header  = curl_getinfo( $ch );

curl_close( $ch );
$header['errno']   = $err;
$header['errmsg']  = $errmsg;
$header['content'] = $content;
return $header;
}

$setuplink = 'http://wpbizconnect.com/links/fetch/?url='.$url.'&api='.$api.'&pg='.$pg;
$content=get_web_page( $setuplink);
$contents=$content['content'];
return $contents;
}

add_shortcode('WPbizconnect_links', 'WPbizconnect_shortcode_return')
?>