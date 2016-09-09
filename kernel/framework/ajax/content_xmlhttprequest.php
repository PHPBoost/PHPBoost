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

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$page_path_to_root = retrieve(REQUEST, 'path_to_root', '');
$page_path = retrieve(REQUEST, 'page_path', '');

//Quel éditeur utiliser ? Si ce n'est pas précisé on prend celui par défaut de l'utilisateur
$editor = retrieve(REQUEST, 'editor', ContentFormattingConfig::load()->get_default_editor());

$contents = stripslashes(retrieve(POST, 'contents', ''));

$ftags = retrieve(POST, 'ftags', TSTRING_UNCHANGE);
$forbidden_tags = explode(',', $ftags);

$formatting_factory = AppContext::get_content_formatting_service()->create_factory($editor);

//On prend le bon parseur avec la bonne configuration
$parser = $formatting_factory->get_parser();

$parser->set_content($contents);
$parser->set_path_to_root($page_path_to_root);
$parser->set_page_path($page_path);

if (!empty($forbidden_tags))
{
    $parser->set_forbidden_tags($forbidden_tags);
}

$parser->parse();

//On parse la deuxième couche (code, math etc) pour afficher
$second_parser = $formatting_factory->get_second_parser();
$second_parser->set_content($parser->get_content());
$second_parser->set_path_to_root($page_path_to_root);
$second_parser->set_page_path($page_path);

$second_parser->parse();

$contents = $second_parser->get_content();

echo $contents;

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>