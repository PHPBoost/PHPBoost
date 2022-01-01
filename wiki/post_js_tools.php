<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2006 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

//On charge le template associé
$lang = LangLoader::get_all_langs('wiki');
$jstools_tpl = new FileTemplate('wiki/wiki_js_tools.tpl');
$jstools_tpl->add_lang($lang);

$jstools_tpl->put_all(array(
	'L_PARAGRAPH_1' => sprintf($lang['wiki.paragraph'], 1),
	'L_PARAGRAPH_2' => sprintf($lang['wiki.paragraph'], 2),
	'L_PARAGRAPH_3' => sprintf($lang['wiki.paragraph'], 3),
	'L_PARAGRAPH_4' => sprintf($lang['wiki.paragraph'], 4),
	'L_PARAGRAPH_5' => sprintf($lang['wiki.paragraph'], 5),
));


?>
