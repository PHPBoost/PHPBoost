<?php
/*##################################################
 *                              download_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Autorisation sur le module.
if( !$groups->check_auth($SECURE_MODULE['download'], ACCESS_MODULE) )
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

load_module_lang('download', $CONFIG['lang']); //Chargement de la langue du module.
$cache->load_file('download');

define('READ_CAT_DOWNLOAD', 0x01);
define('WRITE_CAT_DOWNLOAD', 0x02);
define('READ_FILE_DOWNLOAD', 0x01);
define('EXEC_FILE_DOWNLOAD', 0x02);

$get_note =  !empty($_GET['note']) ? numeric($_GET['note']) : 0;
$idurl = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
$idcat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;

$CAT_DOWNLOAD[$idcat]['name'] = !empty($CAT_DOWNLOAD[$idcat]['name']) ? $CAT_DOWNLOAD[$idcat]['name'] : '';
$download['title'] = '';
if( !empty($idurl) && !empty($idcat) )
{ 
	$download = $sql->query_array('download', '*', "WHERE visible = 1 AND id = '" . $idurl . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	define('TITLE', $LANG['title_download'] . ' - ' . addslashes($download['title']));
}
elseif( !empty($idcat) )
	define('TITLE', $LANG['title_download'] . ' - ' . addslashes($CAT_DOWNLOAD[$idcat]['name']));
else
	define('TITLE', $LANG['title_download']);

$l_com_note = !empty($idurl) ? (!empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '') ) : '';

speed_bar_generate($SPEED_BAR, $LANG['title_download'], transid('download.php'), 
$CAT_DOWNLOAD[$idcat]['name'], (empty($idurl) ? '' : transid('download.php?cat=' . $idcat, 'download-' . $idcat . '+' . url_encode_rewrite($CAT_DOWNLOAD[$idcat]['name']) . '.php')),
$download['title'], ((!empty($get_note) || !empty($_GET['i'])) ? transid('download.php?cat=' . $idcat . '&amp;id=' . $idurl, 'download-' . $idcat . '-' . $idurl . '+' . url_encode_rewrite($download['title']) . '.php') : ''),
$l_com_note, '');

?>