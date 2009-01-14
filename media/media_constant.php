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

// Permissions des catégories.
define('MEDIA_AUTH_READ', 1);
define('MEDIA_AUTH_CONTRIBUTION', 2);
define('MEDIA_AUTH_WRITE', 4);

// Information mime type.
define('MEDIA_TYPE_BOTH', 0);
define('MEDIA_TYPE_MUSIC', 1);
define('MEDIA_TYPE_VIDEO', 2);

// Informtations sur les fichiers.
define('MEDIA_STATUS_UNVISIBLE', 1);
define('MEDIA_STATUS_APROBED', 2);

// Paramètres d'affichage.
define('MEDIA_DL_COM', 1); // Affiche au niveau de la liste des vidéos l'affichage des commentaires.
define('MEDIA_DV_COM', 2); // Affiche au niveau de la vidéo l'affichage des commentaires.
define('MEDIA_DL_NOTE', 4); // Affiche au niveau de la liste des vidéos l'affichage des notes.
define('MEDIA_DV_NOTE', 8); // Affiche au niveau de la vidéo l'affichage des notes.
define('MEDIA_DL_USER', 16); // Affiche au niveau de la liste des vidéos l'affichage du nom du posteur.
define('MEDIA_DV_USER', 32); // Affiche au niveau de la vidéo l'affichage du nom du posteur.
define('MEDIA_DL_COUNT', 64); // Affiche au niveau de la liste des vidéos l'affichage du compteur.
define('MEDIA_DV_COUNT', 128); // Affiche au niveau de la vidéo l'affichage du compteur.
define('MEDIA_DL_DATE', 256); // Affiche au niveau de la liste des vidéos l'affichage de la date d'ajout.
define('MEDIA_DV_DATE', 512); // Affiche au niveau de la vidéo l'affichage de la date d'ajout.
define('MEDIA_DL_DESC', 1024); // Affiche au niveau de la liste des vidéos l'affichage de la description de la vidéo.
define('MEDIA_DV_DESC', 2048); // Affiche au niveau de la vidéo l'affichage de la date de la description de la vidéo.

// Paramètre pour le mini module.
define('NUM_MEDIA', 5);
// Paramètre de redirection en seconde.
define('TIME_REDIRECT', 5);
// Nombre de fichiers par page dans le panneau de modération.
define('NUM_MODO_MEDIA', 15);

?>