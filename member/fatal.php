<?php
/***************************************************************************
 *                                fatal.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright          : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
 *
 ***************************************************************************
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
***************************************************************************/

require_once '../kernel/begin.php';

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
	<title>' . $LANG['error'] . '</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="../templates/' . get_utheme() . '/theme/design.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/global.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/generic.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/bbcode.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="../templates/' . get_utheme() . '/theme/content.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut" href="../favicon.ico" />
</head>
<body><br /><br /><br />';

//Récupération de l'erreur dans les logs.
$errinfo = $Errorh->get_last__error_log();
if (empty($errinfo))
	list($errinfo['errno'], $errinfo['errstr'], $errinfo['errline'], $errinfo['errfile']) = array('-1', '???', '0', 'unknow');

$Template->set_filenames(array(
	'error'=> 'member/error.tpl'
));

$class = $Errorh->get_errno_class($errinfo['errno']);
	
$Template->assign_vars(array(
	'THEME' => get_utheme(),
	'ERRORH_IMG' => 'stop',
	'ERRORH_CLASS' => $class,
	'C_ERRORH_CONNEXION' => false,
	'C_ERRORH' => true,
	'L_ERRORH' => sprintf($LANG[$class], $errinfo['errstr'], $errinfo['errline'], basename($errinfo['errfile'])),
	'L_ERROR' => $LANG['error'],
	'U_BACK' => '<a href="' . get_start_page() . '">' . $LANG['home'] . '</a>' . (!empty($_SERVER['HTTP_REFERER']) ? ' &raquo; <a href="' . url($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : ' &raquo; <a href="javascript:history.back(1)">' . $LANG['back'] . '</a>'),
));

$Template->pparse('error');

echo '</body></html>';

require_once '../kernel/footer_no_display.php';

?>