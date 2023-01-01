<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 01
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

// Informations on files.
define('MEDIA_STATUS_DISAPPROVED', 0);
define('MEDIA_STATUS_INVISIBLE', 1);
define('MEDIA_STATUS_APPROVED', 2);

// Authorized files extension.
$mime_type = array(
	'audio' => array(
		'mp3'   => 'audio/mpeg',
		'ahost' => 'audio/host',
	),
	'video' => array(
		'mp4'   => 'video/mp4',
		'ogg'   => 'video/ogg',
		'webm'  => 'video/webm',
		'vhost' => 'video/host',
	)
);

// Tpl file depending on mime type.
$mime_type_tpl = array(
	'video/mp4'   => 'format/media_html5_player.tpl',
	'video/ogg'   => 'format/media_html5_player.tpl',
	'video/webm'  => 'format/media_html5_player.tpl',
	'audio/mpeg'  => 'format/media_mp3.tpl',
	'video/host'  => 'format/host_player.tpl',
	'audio/host'  => 'format/host_player.tpl',
);

// Trusted host
$config = MediaConfig::load();
$peertube_link = $config->get_peertube_constant();
$peertube_host = explode('/', $peertube_link);
$host_ok = array(
	'audio' => array(
		'music.amazon.com',
		'www.deezer.com',
		'www.spotify.com',
		'soundcloud.com',
		'w.soundcloud.com',
	),
	'video' => array(
		'www.dailymotion.com',
		'www.netflix.com',
		'www.primevideo.com',
		'vimeo.com',
		'player.vimeo.com',
		'www.youtube.com',
		'youtu.be',
		'www.twitch.tv',
		'player.twitch.tv',
		'odysee.com',
		$peertube_host[2]
	)
);

$peertube_host_player = explode('.', $peertube_host[2]);
$sliced_name = array_slice($peertube_host_player, 0, -1);
$peertube_player = implode('.', $sliced_name);
// Host file players
$host_players = array(
	'youtu'          => 'https://www.youtube.com/embed/',
    'vimeo'          => 'https://player.vimeo.com/video/',
    'twitch'         => 'https://player.twitch.tv/?video=',
    'dailymotion'    => 'https://www.dailymotion.com/embed/video/',
    'odysee'         => 'https://odysee.com/$/embed/',
    $peertube_player => $peertube_link . '/videos/embed/',
    'soundcloud'     => 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/',
)

?>
