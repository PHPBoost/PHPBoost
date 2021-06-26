<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 2.0 - 2007 11 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

$lang   = LangLoader::get('common', 'stats');
$common_lang = LangLoader::get('common-lang');
$user_lang   = LangLoader::get('user-lang');

$visit            = (bool)retrieve(GET, 'visit', false);
$visit_year       = (int)retrieve(GET, 'year', 0);
$pages            = (bool)retrieve(GET, 'pages', false);
$pages_year       = (int)retrieve(GET, 'pages_year', 0);
$referer          = (bool)retrieve(GET, 'referer', false);
$keyword          = (bool)retrieve(GET, 'keyword', false);
$members          = (bool)retrieve(GET, 'members', false);
$browser          = (bool)retrieve(GET, 'browser', false);
$os               = (bool)retrieve(GET, 'os', false);
$all              = (bool)retrieve(GET, 'all', false);
$country     	  = (bool)retrieve(GET, 'lang', false);
$bot              = (bool)retrieve(GET, 'bot', false);
$erase            = (bool)retrieve(POST, 'erase', false);
$erase_occasional = (bool)retrieve(POST, 'erase-occasional', false);

$l_title = $lang['stats.website'];
$l_title = ($visit || $visit_year) ? $user_lang['user.guests'] : $l_title;
$l_title = $pages ? $common_lang['common.pages'] : $l_title;
$l_title = $referer ? $lang['stats.referers'] : $l_title;
$l_title = $keyword ? $common_lang['common.keywords'] : $l_title;
$l_title = $members ? $user_lang['user.members'] : $l_title;
$l_title = $browser ? $lang['stats.browsers'] : $l_title;
$l_title = $os ? $lang['stats.os'] : $l_title;
$l_title = $country ? $lang['stats.countries'] : $l_title;
$l_title = $bot ? $lang['stats.robots'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if (!empty($l_title))
	$Bread_crumb->add($lang['stats.module.title'], url('stats.php'));
	$Bread_crumb->add($l_title, '');
define('TITLE', $lang['stats.module.title'] . (!empty($l_title) ? ' - ' . $l_title : ''));
?>
