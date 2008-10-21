<?php
/*##################################################
 *                              video_begin.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHPBOOST') !== true)
	exit;

$Cache->load('video');
load_module_lang('video'); //Chargement de la langue du module.

// Permissions des catégories.
define('VIDEO_READ', 1);
define('VIDEO_WRITE', 2);
define('VIDEO_APROB', 4);
define('VIDEO_FLOOD', 8);
define('VIDEO_EDIT', 16);
define('VIDEO_DELETE', 32);
define('VIDEO_MODERATE', 64);

// Informtations sur les fichiers.

// Paramètres d'affichage.
define('ACTIV_COM', 1); // Afficher les commentaires.
define('ACTIV_NOTE', 2); // Afficher les notes.
define('ACTIV_POSTER', 4); // Afficher le posteur.
define('ACTIV_DATE', 8); // Afficher la date.
define('ACTIV_VIEW', 16); // Afficher le nombre de consulation.
define('ACTIV_DESC', 32); // Afficher la description.

?>