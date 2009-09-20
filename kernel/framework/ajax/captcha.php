<?php
/*##################################################
 *                               captcha.php
 *                            -------------------
 *   begin                : Februar, 06 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');
header('Content-type: image/jpeg'); //Envoi du header.

$instance = retrieve(GET, 'instance', 1);
$width = retrieve(GET, 'width', 160);
$height = retrieve(GET, 'height', 50);
$font = retrieve(GET, 'font', PATH_TO_ROOT . '/kernel/data/fonts/impact.ttf');
$difficulty = retrieve(GET, 'difficulty', 4);

import('util/captcha');
$Captcha = new Captcha();

$Captcha->set_instance($instance);
$Captcha->set_width($width);
$Captcha->set_height($height);
$Captcha->set_font($font);
$Captcha->set_difficulty($difficulty);
$Captcha->display();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
