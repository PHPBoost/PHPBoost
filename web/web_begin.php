<?php
/*##################################################
 *                              web_begin.php
 *                            -------------------
 *   begin                : Nobember 28, 2007
 *   copyright            : (C) 2007 Viarre régis
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('web'); //Chargement de la langue du module.

$get_note = AppContext::get_request()->get_getint('note', 0);
$idweb = AppContext::get_request()->get_getint('id', 0);
$idcat = AppContext::get_request()->get_getint('cat', 0);

$Cache->load('web'); //$CAT_WEB et $CONFIG_WEB en global.

$CAT_WEB[$idcat]['name'] = !empty($CAT_WEB[$idcat]['name']) ? $CAT_WEB[$idcat]['name'] : '';
$web['title'] = '';
if (!empty($idweb) && !empty($idcat))
{ 
	$web = $Sql->query_array(PREFIX . 'web' , '*', "WHERE aprob = 1 AND id = '" . $idweb . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($web['title']));
}
elseif (!empty($idcat))
	define('TITLE', $LANG['title_web'] . ' - ' . addslashes($CAT_WEB[$idcat]['name']));
else
	define('TITLE', $LANG['title_web']);
	
$l_com_note = !empty($get_note) ? $LANG['note'] : (!empty($_GET['i']) ? $LANG['com'] : '');
$Bread_crumb->add($LANG['title_web'], url('web.php')); 
$Bread_crumb->add($CAT_WEB[$idcat]['name'], (empty($idweb) ? '' : url('web.php?cat=' . $idcat, 'web-' . $idcat . '.php')));
$Bread_crumb->add($web['title'], ((!empty($get_note) || !empty($_GET['i'])) ? url('web.php?cat=' . $idcat . '&amp;id=' . $idweb, 'web-' . $idcat . '-' . $idweb . '.php') : ''));
$Bread_crumb->add($l_com_note, '');

?>