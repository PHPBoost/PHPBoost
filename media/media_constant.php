<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 03
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

// Informations on files.
define('MEDIA_STATUS_UNAPROBED', 0);
define('MEDIA_STATUS_UNVISIBLE', 1);
define('MEDIA_STATUS_APROBED', 2);

// Authorized files extension.
$mime_type = array(
	'audio' => array(
		'mp3' => 'audio/mpeg',
		'ahost' => 'audio/host',
	),
	'video' => array(
		'flv' => 'video/x-flv',
		'mp4' => 'video/mp4',
		'ogg' => 'video/ogg',
		'webm' => 'video/webm',
		'swf' => 'application/x-shockwave-flash',
		'vhost' => 'video/host',
	)
);

// Tpl file depending on mime type.
$mime_type_tpl = array(
	'video/x-flv' => 'format/media_flv.tpl',
	'video/mp4' => 'format/media_html5_player.tpl',
	'video/ogg' => 'format/media_html5_player.tpl',
	'video/webm' => 'format/media_html5_player.tpl',
	'application/x-shockwave-flash' => 'format/media_swf.tpl',
	'audio/mpeg' => 'format/media_mp3.tpl',
	'video/host' => 'format/host_player.tpl',
	'audio/host' => 'format/host_player.tpl',
);

// Trusted host
$host_ok = array(
	'video' => array(
		'www.arte.tv',
		'www.dailymotion.com',
		'www.netflix.com',
		'www.primevideo.com',
		'vimeo.com',
		'player.vimeo.com',
		'www.youtube.com',
		'youtu.be',
		'www.labsoweb.fr'
	),
	'audio' => array(
		'music.amazon.com',
		'www.deezer.com',
		'www.spotify.com',
		'soundcloud.com',
		'w.soundcloud.com',
	)
);

// Host file players
$host_players = array(
	'youtu' => 'https://www.youtube.com/embed/',
    'vimeo' => 'https://player.vimeo.com/video/',
    'dailymotion' => 'https://www.dailymotion.com/embed/video/',
    'arte' => 'https://www.arte.tv/player/v5/',
    'soundcloud' => 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/',
)

?>
