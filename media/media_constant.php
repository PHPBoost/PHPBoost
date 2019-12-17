<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 18
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

// Informations sur les fichiers.
define('MEDIA_STATUS_UNAPROBED', 0);
define('MEDIA_STATUS_UNVISIBLE', 1);
define('MEDIA_STATUS_APROBED', 2);

// Type de fichier autorisés.
$mime_type = array(
	'audio' => array(
		'mp3' => 'audio/mpeg',
	),
	'video' => array(
		'flv' => 'video/x-flv',
		'mp4' => 'video/mp4',
		'ogg' => 'video/ogg',
		'webm' => 'video/webm',
		'swf' => 'application/x-shockwave-flash',
	)
);

// Fichier en fonction du mime type.
$mime_type_tpl = array(
	'video/x-flv' => 'format/media_flv.tpl',
	'video/mp4' => 'format/media_html5_player.tpl',
	'video/ogg' => 'format/media_html5_player.tpl',
	'video/webm' => 'format/media_html5_player.tpl',
	'application/x-shockwave-flash' => 'format/media_swf.tpl',
	'audio/mpeg' => 'format/media_mp3.tpl'
);

// Host de confiance!
$host_ok = array(
	'video' => array(
		'www.dailymotion.com',
		'www.netflix.com',
		'www.primevideo.com',
		'www.vimeo.com',
		'www.youtube.com'
	),
	'audio' => array(
		'music.amazon.com',
		'www.deezer.com',
		'www.spotify.com'
	)
);

?>
