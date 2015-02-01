<?php
/*##################################################
 *                              media_constant.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        		: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

// Permissions des cat�gories.
define('MEDIA_AUTH_READ', 1);
define('MEDIA_AUTH_CONTRIBUTION', 2);
define('MEDIA_AUTH_WRITE', 4);

// Information mime type.
define('MEDIA_TYPE_BOTH', 0);
define('MEDIA_TYPE_MUSIC', 1);
define('MEDIA_TYPE_VIDEO', 2);

// Informtations sur les fichiers.
define('MEDIA_STATUS_UNAPROBED', 0);
define('MEDIA_STATUS_UNVISIBLE', 1);
define('MEDIA_STATUS_APROBED', 2);

// Param�tres d'affichage.
define('MEDIA_DL_COM', 1); // Affiche au niveau de la liste des vid�os l'affichage des commentaires.
define('MEDIA_DV_COM', 2); // Affiche au niveau de la vid�o l'affichage des commentaires.
define('MEDIA_DL_NOTE', 4); // Affiche au niveau de la liste des vid�os l'affichage des notes.
define('MEDIA_DV_NOTE', 8); // Affiche au niveau de la vid�o l'affichage des notes.

// Type de fichier autoris�s.
$mime_type = array(
	'audio' => array(
		'mp3' => 'audio/mpeg',
	),
	'video' => array(
		'flv' => 'video/x-flv',
		'mp4' => 'video/mp4',
		'swf' => 'application/x-shockwave-flash',
	)
);

// Fichier en fonction du mime type.
$mime_type_tpl = array(
	'video/x-flv' => 'format/media_flv.tpl',
	'video/mp4' => 'format/media_flv.tpl',
	'application/x-shockwave-flash' => 'format/media_swf.tpl',
	'audio/mpeg' => 'format/media_mp3.tpl'
);

// Host de confiance!
$host_ok = array(
	'video' => array(
		'www.dailymotion.com',
		'www.youtube.com',
		'video.google.fr',
		'www.wat.tv'
	),
	'audio' => array(
		'www.deezer.com',
		'widgets.jamendo.com'
	)
);

?>