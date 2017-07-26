<?php
/*
Plugin Name: BoxOffice
Plugin URI: http://www.woodymood-dev.net/cms/wordpress/en/2009/04/03/plugin-boxoffice
Description: Display a boxoffice of all your articles ordered by their count views. Requires <a href="http://wordpress.org/extend/plugins/popular-posts-plugin/" target="_blank">Popular Posts</a>
Version: 0.2
Author: Anthony Dubois
Author URI: http://www.woodymood-dev.net/cms/wordpress/en/lauteur/
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


/*******************************************************************


				installation


********************************************************************/

register_activation_hook(__FILE__, 'boxoffice_install');
register_deactivation_hook(__FILE__, 'boxoffice_uninstall');

function boxoffice_install() {

	if ( !get_option('boxoffice_show_rank') ) add_option('boxoffice_show_rank', 'yes'); 
	if ( !get_option('boxoffice_show_date') ) add_option('boxoffice_show_date', 'yes');
	if ( !get_option('boxoffice_show_visits') ) add_option('boxoffice_show_visits', 'yes');
	if ( !get_option('boxoffice_id_page') ) add_option('boxoffice_id_page', 0);
}


function boxoffice_uninstall() {

	delete_option('boxoffice_show_rank');	
	delete_option('boxoffice_show_date');
	delete_option('boxoffice_show_visits');
	delete_option('boxoffice_id_page');
}

/*******************************************************************


				init


********************************************************************/

add_action( "init", "boxoffice_init" );

function boxoffice_init() {

	load_plugin_textdomain('boxoffice', PLUGINDIR . '/' . dirname(plugin_basename (__FILE__)) . '/lang');
}


/*******************************************************************


				menu d'administration


********************************************************************/

add_action('admin_menu', 'boxoffice_menu');

function boxoffice_menu() {

  add_options_page('BoxOffice', 'BoxOffice', 8, __FILE__, 'boxoffice_admin');
}


function boxoffice_admin() {
	?>
	<div class="wrap">
	<h2><?php _e('BoxOffice', 'boxoffice') ?></h2>
	<?php 

	if( @$_POST['boxoffice'] == 'admin') {
		
		$boxoffice_id_page = $_POST[ 'boxoffice_id_page' ];
		if ( ereg("^[0-9]+$", $boxoffice_id_page) && $boxoffice_id_page>=1 ) {
			update_option( 'boxoffice_id_page', $boxoffice_id_page );
		}
		else {
			update_option( 'boxoffice_id_page', 0 );
		}
		
		$boxoffice_show_rank = $_POST['boxoffice_show_rank'];
		if ( ereg("^(yes|no)$", $boxoffice_show_rank) ) {
			update_option( 'boxoffice_show_rank', $boxoffice_show_rank );
		}
		else {
			update_option( 'boxoffice_show_rank', 'yes' );
		}
		
		$boxoffice_show_date = $_POST['boxoffice_show_date'];
		if ( ereg("^(yes|no)$", $boxoffice_show_date) ) {
			update_option( 'boxoffice_show_date', $boxoffice_show_date );
		}
		else {
			update_option( 'boxoffice_show_date', 'boxoffice_show_date' );
		}
		
		$boxoffice_show_visits = $_POST['boxoffice_show_visits'];
		if ( ereg("^(yes|no)$", $boxoffice_show_visits) ) {
			update_option( 'boxoffice_show_visits', $boxoffice_show_visits );
		}
		else {
			update_option( 'boxoffice_show_visits', 'yes' );
		}
		
		?>
		<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
		<?php
	}

	?>
	<p>
	<?php _e('To get the box office page view, put somewhere in a page or in a post, this html comment, in a standalone paragraph:', 'boxoffice'); ?>
	</p>
	<pre>&lt;!-- box office replacement point --&gt;</pre>
	<p><?php _e('then, enter below, the post\'s or page\'s ID:', 'boxoffice'); ?></p>
	
	<?php 
	echo '
	
	<form name="form1" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
	
	<input type="hidden" name="boxoffice" value="admin" />
	
	<table class="form-table">
	
	<tr valign="top">
	<th scope="row">' . __('ID of the post or page, where you want to display the box office','boxoffice') . '</th>
	<td><input type="text" name="boxoffice_id_page" value="' . get_option('boxoffice_id_page') . '" /></td>
	</tr>
	
	<tr valign="top">
	<th scope="row">' . __('Show rank','boxoffice') . '</th>
	<td>' . get_radio_form('boxoffice_show_rank', array('yes', 'no')) . '</td>
	</tr>

	<tr valign="top">
	<th scope="row">' . __('Show publishing date','boxoffice') . '</th>
	<td>' . get_radio_form('boxoffice_show_date', array('yes', 'no')) . '</td>
	</tr>

	<tr valign="top">
	<th scope="row">' . __('Show visits','boxoffice') . '</th>
	<td>' . get_radio_form('boxoffice_show_visits', array('yes', 'no')) . '</td>
	</tr>

	</table>
	
	<p class="submit">
	<input type="submit" class="button-primary" value="' . __('Save Changes') . '" />
	</p>
	</form>
	
	</div>';
}

if ( !function_exists('get_radio_form') ) { 
function get_radio_form($name, $values) {

	$current_value = get_option($name);
	
	$ret = '';
	
	foreach ( $values as $value ) {
		if ( $value == $current_value ) {
			$checked = ' checked="checked"';
		}
		else {
			$checked = '';
		}
		$ret .= 
		'<input type="radio" name="' . $name . '" value="' . $value . '"' . $checked . ' />' . $value . '<br />';
	}

	return $ret;

}
}

/*******************************************************************


				content filter


********************************************************************/

add_filter( 'the_content', 'boxoffice_content' );
		
function boxoffice_content($content) {

	global $post;
	
	$id = get_option('boxoffice_id_page');
	
	if ( 
			$id != 0 && 
			ereg("^[0-9]+$", $id) && 
			$post->ID==$id && 
			function_exists('popular_posts_views') && 
			(is_single() || is_page()) && 
			strpos($content, '<p><!-- box office replacement point --></p>') !== false ) { 

		$replace = boxoffice();
		
		$content = str_replace('<p><!-- box office replacement point --></p>', $replace, $content);
	}

	return $content;	
}


/*******************************************************************


				template tag


********************************************************************/

function boxoffice() {

	$ret = '';

	$args = array(
	'post_type' => 'post',
	'numberposts' => -1,
	'post_parent' => null
	); 
	
	$all_posts_com = get_posts($args);
	
	if ($all_posts_com) {
	
		$ret = 
		'
		<div class="boxoffice">
		<table>';
	
		$all = array();
		
		foreach ($all_posts_com as $post_given) {
		
			$date_stamp = strtotime( $post_given->post_date );
			$date_s = date_i18n( get_option( 'date_format' ), $date_stamp );
			
			$vv = popular_posts_views($post_given->ID);
			$vv = ($vv > 1 ? $vv . ' ' . __('visits', 'boxoffice') : ($vv == 1 ? ' 1 ' . __('visit', 'boxoffice') : ''));
			
			$title = '<a href="' . get_permalink($post_given->ID) . '">' . $post_given->post_title . '</a>';
			
			$new_post = array($post_given->ID, $title, $date_s, $vv);
			
			if ( !array_key_exists($vv, $all) ) {
				// new count, create
				$all[$vv] = array();
				$all[$vv][] = $new_post;
			}
			else {
				// old count
				$all[$vv][] = $new_post;
			}
			
		}
		
		krsort($all, SORT_NUMERIC);
		$i = 0;
		$classes = array('even','odd');
		foreach ( $all as $tp ) {
			foreach ( $tp as $p ) {
				$i++;
				
				$ret .= '<tr class="' . $classes[$i%2] . '">';
				if ( get_option('boxoffice_show_rank') == 'yes' ) $ret .= '<td class="rank">' . $i . '.</td>';
				if ( get_option('boxoffice_show_visits') == 'yes' ) $ret .= '<td class="visits">' . $p[3] . '</td>';
				$ret .= '<td class="title">' . $p[1] . '</td>';
				if ( get_option('boxoffice_show_date') == 'yes' ) $ret .= '<td class="date">' . $p[2] . '</td>';
				$ret .= '</tr>';
			}
			
		}
		
		$ret .= '</table></div>';
	}
	return $ret;
}

?>