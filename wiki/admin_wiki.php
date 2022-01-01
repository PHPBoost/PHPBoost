<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2006 11 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('wiki');

define('TITLE', StringVars::replace_vars($lang['form.module.title'], array('module_name' => ModulesManager::get_module('wiki')->get_configuration()->get_name())));
require_once('../admin/admin_header.php');
include_once('../wiki/wiki_functions.php');

$config = WikiConfig::load();
$request = AppContext::get_request();

$update = $request->get_postvalue('update', false);
$display_categories_on_index = $request->get_postvalue('display_categories_on_index', false);
$hits_counter = $request->get_postvalue('hits_counter', false);
$sticky_menu = $request->get_postvalue('sticky_menu', false);

$index_text = stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED)));

$view = new FileTemplate('wiki/admin_wiki.tpl');
$view->add_lang($lang);

// Execute on validation
if ($update)
{
	$config->set_wiki_name(TextHelper::strprotect(retrieve(POST, 'wiki_name', $lang['wiki.module.title'], TSTRING_AS_RECEIVED), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE));
	$config->set_number_articles_on_index(retrieve(POST, 'number_articles_on_index', 0));
	if ($display_categories_on_index)
		$config->display_categories_on_index();
	else
		$config->hide_categories_on_index();
	if ($hits_counter)
		$config->enable_hits_counter();
	else
		$config->disable_hits_counter();
	if ($sticky_menu)
		$config->enable_sticky_menu();
	else
		$config->disable_sticky_menu();
	$config->set_index_text(stripslashes(wiki_parse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED))));

	WikiConfig::save();

	//Régénération du cache
	WikiCategoriesCache::invalidate();

	HooksService::execute_hook_action('edit_config', 'wiki', array('title' => StringVars::replace_vars($lang['form.module.title'], array('module_name' => ModulesManager::get_module('wiki')->get_configuration()->get_name())), 'url' => WikiUrlBuilder::configuration()->rel()));

	$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.success.config'], MessageHelper::SUCCESS, 4));
}

//On travaille uniquement en BBCode, on force le langage de l'éditeur
$content_editor = AppContext::get_content_formatting_service()->get_default_factory();
$editor = $content_editor->get_editor();
$editor->set_identifier('contents');

$view->put_all(array(
	'KERNEL_EDITOR'               => $editor->display(),
	'HITS_SELECTED'               => $config->is_hits_counter_enabled() ? 'checked="checked"' : '',
	'STICKY_MENU_SELECTED'        => $config->is_sticky_menu_enabled() ? 'checked="checked"' : '',
	'WIKI_NAME'                   => $config->get_wiki_name(),
	'HIDE_CATEGORIES_ON_INDEX'    => !$config->are_categories_displayed_on_index() ? 'checked="checked"' : '',
	'DISPLAY_CATEGORIES_ON_INDEX' => $config->are_categories_displayed_on_index() ? 'checked="checked"' : '',
	'ITEMS_NUMBER_ON_INDEX'       => $config->get_number_articles_on_index(),
	'DESCRIPTION'                 => FormatingHelper::unparse($config->get_index_text()),
));

$view->display();

require_once('../admin/admin_footer.php');

?>
