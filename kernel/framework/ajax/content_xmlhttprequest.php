<?php
/*##################################################
 *                        content_xmlhttprequest.php
 *                            -------------------
 *   begin                : January, 25 2007
 *   copyright            : (C) 2007 Viarre Régis
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

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$page_path_to_root = retrieve(GET, 'path_to_root', '');

//Quel éditeur utiliser ? Si ce n'est pas précisé on prend celui par défaut de l'utilisateur
$editor = retrieve(GET, 'editor', $CONFIG['editor']);

$contents = utf8_decode(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED));

$ftags = retrieve(POST, 'ftags', TSTRING_UNCHANGE);

//On prend le bon parseur avec la bonne configuration
$content_manager = new ContentFormattingFactory($editor);
$parser = $content_manager->get_parser($editor);

$parser->set_content($contents, MAGIC_QUOTES);

if (!empty($forbidden_tags))
{
    $parser->set_forbidden_tags($forbidden_tags);
}

$parser->parse();

//On parse la deuxième couche (code, math etc) pour afficher
$contents = second_parse(stripslashes($parser->get_content()));

//Remplacement du path to root si ce n'est pas le même (cas peu fréquent)
if (preg_match('`^[./]+$`U', $page_path_to_root) && PATH_TO_ROOT != '..')
{
    $contents = str_replace('"' . PATH_TO_ROOT . '/', '"' . $page_path_to_root . '/', $contents);
}

echo $contents;

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');

?>