<?php
/*
Plugin Name: Vimeo Album
Plugin URI:  http://www.robertpasquini.com/nerd/2011/vimeo_album/
Description: Allows the user to embed Vimeo albums by entering a shortcode ([vimeoAlbum album_id="**id from vimeo here**"]) into the post area.
Author: Robert Pasquini
Version: 0.2.1
Author URI: http://robertpasquini.com
License: GPL 2.0, @see http://www.gnu.org/licenses/gpl-2.0.html
*/
add_action( 'wp_print_scripts', 'enqueue_vimeo_album_scripts' );
add_action( 'wp_print_styles', 'enqueue_vimeo_album_styles' );

//http://www.lastlifemedia.com/wptest/testpluginjs.js?ver=3.2.1
function enqueue_vimeo_album_scripts(){
		wp_enqueue_script( 'vimeoalbumJs', '/wp-content/plugins/vimeo-album/vimeo_album.js', array( 'jquery' ));
		}
function enqueue_vimeo_album_styles(){
		wp_enqueue_style( 'vimeoalbumStyle', '/wp-content/plugins/vimeo-album/vastyle.css');
		}

class vimeo_album {

    function shortcode($atts, $content=null) 
    {
    ob_start();
    require_once(getcwd().'/wp-content/plugins/vimeo-album/vimeo.php');
	$vimeo = new phpVimeo('9507bd0c1539525bd6af485f4736355a', 'b99cca6e700fd428');
    
    extract( shortcode_atts( array(
		'album_id' => 'no album ID set, get it from vimeo',
		), $atts ) );

	// The Simple API URL
	$api_endpoint = 'http://vimeo.com/api/v2/';
	 
		if (!function_exists('curl_get')){
		// Curl helper function
		function curl_get($url) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$return = curl_exec($curl);
			curl_close($curl);
			return $return;
		}
		}
		

		// Load the videos and info
		$videos = $vimeo->call('vimeo.albums.getVideos', array('album_id' => $album_id));
		$info = simplexml_load_string(curl_get($api_endpoint . 'album/' . $album_id . '/info.xml'));
		
		
		// Thumbnail and title and album description
		$image = $info->album->thumbnail;
		$title = $info->album->title;
		$descrip = $info->album->description;
		$albumCover =  $info->album->thumbnail_large;

//put the html on page whenever shortcode is called
//put the html on page whenever shortcode is called
	echo "<p>".$descrip."</p>
	<div class='vimeo_album_wrapper'>
		<div id='embed".$album_id."' class='embedTargetDiv'>
			<a href='http://vimeo.com/".$videos->videos->video[0]->id."' class='vimeoAlbumA'>
				
				<img class='vaAlbumCover' alt='' src='".$albumCover."' />
				<span class='va_title'><h2>".$title."</h2></span>	
			</a>
			
		</div>
		<div id='thumb".$album_id."' class='embeded".$album_id."'>
			<ul class='vimeo_album-ul'>";
			 foreach ($videos->videos->video as $video): 
				echo"
				<li class='vimeo_album-li'>
					<a href='http://vimeo.com/".$video->id."' class='vimeoAlbumA'>
						".$video->title."
					</a>
				</li>"; 
			endforeach; 
			echo"
			</ul>
		</div>
	</div>
	";

$output_string = ob_get_contents();
 
ob_end_clean();
 
return $output_string;
	}
}

add_shortcode('vimeoAlbum', array('vimeo_album', 'shortcode'));

?>