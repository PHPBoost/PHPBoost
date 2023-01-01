<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 10 30
 * @since       PHPBoost 1.6 - 2007 01 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$page_path_to_root = retrieve(REQUEST, 'path_to_root', '');
$page_path = retrieve(REQUEST, 'page_path', '');

//Quel éditeur utiliser ? Si ce n'est pas précisé on prend celui par défaut de l'utilisateur
$editor = retrieve(REQUEST, 'editor', ContentFormattingConfig::load()->get_default_editor());

$contents = stripslashes(retrieve(POST, 'contents', ''));

if (empty($contents))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

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
