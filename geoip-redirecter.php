<?php
/**
 * @package GeoIP Redirecter
 */
/*
Plugin Name: GeoIP Redirecter
Plugin URI: http://www.waterwhite.sk
Description: Custom redirection of webpage for visitors from other countries to other url. Shows simple strip block on top of page with notification. Works with timeout setting to let visitor see content of original site for a while. Visitor can decide to stay or be redirected.
Version: 1.2.0
Author: waterwhite
Author URI: http://www.waterwhite.sk
License: GPL2
Text Domain: geoip-redirecter
*/

/* INSTALLATION */
$geoip_redirecter_version = get_option('geoip_redirecter_version');
if (empty($geoip_redirecter_version)) { // first install
	add_option('geoip_redirecter_redirect_url','http://www.redirected-site.com');
	add_option('geoip_redirecter_timeout','0');
	add_option('geoip_redirecter_scope','true');
	add_option('geoip_redirecter_notif','You are now viewing www.mysite.com. We recommend you to switch to our homepage:');
	add_option('geoip_redirecter_ask_manual','Do you want to be redirected to:');
	add_option('geoip_redirecter_version','1.2.0'); // CHANGE
} elseif ($geoip_redirecter_version != '1.2.0' ) {
	update_option('geoip_redirecter_version','1.2.0'); // CHANGE / automatic update # in DB
}

/* Load localizaton init */
function geoip_redirecter_init() {
  load_plugin_textdomain( 'geoip-redirecter', false, 'geoip-redirecter/languages' );
}
add_action('init', 'geoip_redirecter_init');


/* ADMINISTRATION AREA */
// create submenu to Options on Administration
function geoip_redirecter_menu() {
	add_options_page('GeoIP Redirecter settings', 'GeoIP Redirecter', 'administrator', 'geoip-redirecter-settings', 'geoip_redirecter_settings_page', 'dashicons-admin-generic');
}

// let WordPress know what settings we intend to use in Administration
function geoip_redirecter_settings() {
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_country_code' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_redirect_url' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_scope' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_timeout' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_ip' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_notif' );
	register_setting( 'geoip-redirecter-settings-group', 'geoip_redirecter_ask_manual' );
}

// settings interface in Administration
function geoip_redirecter_settings_page() {
	global $geoip_redirecter_version;
  ?><div class="wrap">
	<h2><?php _e('GeoIP Redirecter settings page','geoip-redirecter'); ?></h2>
	<p><?php _e('Custom redirection of webpage for visitors from other countries to other url. Shows simple strip block on top of page with notification. Works with timeout setting to let visitor see content of original site for a while. Visitor can decide to stay or be redirected.','geoip-redirecter'); ?></p>
	<p><?php _e('You can filter which countries to redirect, set up one common redirection url and timeout how long will visitor stay on page before redirection','geoip-redirecter'); ?>.</p>

	<form method="post" action="options.php">
			<?php settings_fields( 'geoip-redirecter-settings-group' ); ?>
			<?php do_settings_sections( 'geoip-redirecter-settings-group' ); ?>
			<table class="form-table">
					<tr valign="top">
					<th scope="row"><?php _e('Country code(s) to redirect','geoip-redirecter'); ?>:</th>
					<td><input type="text" name="geoip_redirecter_country_code" value="<?php echo esc_attr( get_option('geoip_redirecter_country_code') ); ?>" /> <em><?php _e('(coma separated) e.g.','geoip-redirecter'); ?>: </em><kbd>tw, cn, mo</kbd></td>
					</tr>
					 
					<tr valign="top">
					<th scope="row"><?php _e('URL address where to redirect','geoip-redirecter'); ?>:</th>
					<td><input type="text" name="geoip_redirecter_redirect_url" value="<?php echo esc_attr( get_option('geoip_redirecter_redirect_url') ); ?>" placeholder="http://" /> <em><?php _e('Insert also','geoip-redirecter'); ?> </em><kbd>http://</kbd><em> <?php _e('or','geoip-redirecter'); ?> </em><kbd>https://</kbd><em> <?php _e('before url link','geoip-redirecter'); ?></em></td>
					</tr>
					 
					<tr valign="top">
					<th scope="row"><?php _e('Activation scope','geoip-redirecter'); ?>:</th>
					<td>
						<input type="radio" id="scope_h" name="geoip_redirecter_scope" <?php if(esc_attr( get_option('geoip_redirecter_scope') ) == 'false') echo 'checked="checked"'; ?> value="false" /><label for="scope_h"><?php _e('homepage only','geoip-redirecter'); ?></label></br>
						<input type="radio" id="scope_e" name="geoip_redirecter_scope" <?php if(esc_attr( get_option('geoip_redirecter_scope') ) == 'true') echo 'checked="checked"'; ?> value="true" /><label for="scope_e"><?php _e('everywhere','geoip-redirecter'); ?></label>
						<p><em><?php _e('Where will plugin be active: homepage only or on each page of website','geoip-redirecter'); ?>.</em></p>
					</td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><?php _e('Waiting timeout','geoip-redirecter'); ?>:<br /> (<?php _e('in seconds','geoip-redirecter'); ?>)</th>
					<td><input type="text" name="geoip_redirecter_timeout" value="<?php echo esc_attr( get_option('geoip_redirecter_timeout') ); ?>" /> <em><?php _e('Set zero','geoip-redirecter'); ?>: </em><kbd>0</kbd><em> <?php _e('for no automatic redirection','geoip-redirecter'); ?>.</em></td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><?php _e('IP address override','geoip-redirecter'); ?>:<br /> (<?php _e('optional','geoip-redirecter'); ?>)</th>
					<td><textarea name="geoip_redirecter_ip" id="geoip_redirecter_ip" cols="20" rows="5"><?php echo esc_attr( get_option('geoip_redirecter_ip') ); ?></textarea><br />
					<p><em><?php _e('Add IP address, one or more (coma separated) to exclude from redirecting','geoip-redirecter'); ?>.</em></p></td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><?php _e('Notification text','geoip-redirecter'); ?>:</th>
					<td><textarea name="geoip_redirecter_notif" id="geoip_redirecter_notif" cols="50" rows="2"><?php echo esc_attr( get_option('geoip_redirecter_notif') ); ?></textarea><br />
					<p><em><?php _e('New url link to redirect will be added automatically','geoip-redirecter'); ?>.</em></p></td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><?php _e('Manual redirect text','geoip-redirecter'); ?>:</th>
					<td><textarea name="geoip_redirecter_ask_manual" id="geoip_redirecter_ask_manual" cols="50" rows="1"><?php echo esc_attr( get_option('geoip_redirecter_ask_manual') ); ?></textarea><br />
					<p><em><?php _e('New line with "yes/no" question will appear in strip block, if this field is filled','geoip-redirecter'); ?>.</em></p></td>
					</tr>
			</table>
			<p style="text-align:right;"><a href="http://www.geoplugin.com/geolocation/" target="_new"><?php _e('IP Geolocation','geoip-redirecter'); ?></a> <?php _e('by','geoip-redirecter'); ?> <a href="http://www.geoplugin.com/" target="_new">geoPlugin</a> | <em>GeoIP Redirecter <?php _e('Plugin version','geoip-redirecter'); ?>: <?php echo $geoip_redirecter_version; ?></em></p>
			
			<?php submit_button(); ?>

	</form>
	</div> <?php
}



/* FRONTSIDE AREA */

// enqueue css files to header (string $handle, string $src = false, array $deps = array(), string|bool|null $ver = false, string $media = 'all')
function enqueue_css_files() {
	wp_enqueue_style( 'geoip-redirecter', plugin_dir_url( __FILE__ ) . 'css/geoip-redirecter.css', null, null, 'all' );
}
// enqueue js files to header (name, path, depends on, version, to footer?)
function enqueue_js_files() {
	wp_enqueue_script( 'geoip-redirecter', plugin_dir_url( __FILE__ ) . 'js/geoip-redirecter.js', array( 'jquery' ), '1.0', true );
}


// content of notification on homepage
function addContent($content) {
	// activation scope determination
	if ((is_home() || is_front_page() || is_page( 'home' ) || is_page( 6 )) || esc_attr( get_option('geoip_redirecter_scope')) == 'true') {
		$friendly_link = str_replace("http://", "", esc_attr( get_option('geoip_redirecter_redirect_url') ) );
		$your_ip = $_SERVER['REMOTE_ADDR'];
		
		$ip_excluded = str_replace(" ", "", esc_attr( get_option('geoip_redirecter_ip') ) );
		$ips = explode(",",strtoupper($ip_excluded));
		if (!in_array( $your_ip , $ips)) {
		
			// activated for testing purpose only
			if ($your_ip == '127.0.0.1') {	
				$your_ip = '202.4.159.255'; // CN
				$your_ip = '31.3.63.255 '; // SK
				$your_ip = '5.145.63.255'; // CH
				$your_ip = '119.81.238.26'; // SG 90% / HK 10%
				$your_ip = '122.100.191.255'; // MO
			}
		
			$geoPlugin = unserialize( file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $your_ip) );

			$countries = str_replace(" ", "", esc_attr( get_option('geoip_redirecter_country_code') ) );
			$os = explode(",",strtoupper($countries));
			
			
			if (in_array( $geoPlugin['geoplugin_countryCode'] , $os)) {
				if (esc_attr( get_option('geoip_redirecter_timeout')) > 0) {
					$js_content = '
						<span class="geoip-countdown"></span>
						<script type="text/javascript">
							jQuery(document).ready(function() {
								var count = '.esc_attr( get_option('geoip_redirecter_timeout') ).';
								var countdown = setInterval(function(){
								jQuery(".geoip-countdown").html("Page will be redirected after <strong>" + count + "</strong> seconds.");
								if (count == 0) {
									clearInterval(countdown);
									if(get_cookie("geoip-redirecter") == null || get_cookie("geoip-redirecter") != 0 ){ 
										window.open("'. esc_attr( get_option('geoip_redirecter_redirect_url')). '", "_self");
									}
									jQuery(".geoip-countdown").html("");
								}
								count--;
								}, 1000);
							});
						</script>';
					} else {
						$js_content = '';
					}
	//				$countryname = ' - ' .$geoPlugin['geoplugin_countryName'].' ('.$geoPlugin['geoplugin_countryCode'].')';
					$countryname = '';
					
					$content .= '<div class="geoip-redirecter">
						<span class="geoip-row">'. esc_attr( get_option('geoip_redirecter_notif') ) .'
						<a href="'.esc_attr( get_option('geoip_redirecter_redirect_url') ).'">'.$friendly_link.'</a>'.$countryname.'</span>';
					
					$content .= '<p class="geoip-manual">'.esc_attr( get_option('geoip_redirecter_ask_manual') ).' <a href="'.esc_attr( get_option('geoip_redirecter_redirect_url') ).'" class="geoip-btn">yes</a> <span class="geoip-btn geoip-close">no</span>';
						
					$content .= $js_content;
					
					$content .= '</p></div>';
					
					
			}
			
			echo $content;
		}
	}
}   

 


/* EXECUTION AREA */
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'geoip_redirecter_menu');
	add_action('admin_init', 'geoip_redirecter_settings');
}

if (!isset($_COOKIE['geoip-redirecter']) || $_COOKIE['geoip-redirecter']!=0) {
	add_action('wp_enqueue_scripts', 'enqueue_css_files');
	add_action('wp_enqueue_scripts', 'enqueue_js_files');
	add_action('wp_footer', 'addContent');
}

