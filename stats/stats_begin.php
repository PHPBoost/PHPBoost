<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 17
 * @since       PHPBoost 2.0 - 2007 11 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

$lang = LangLoader::get('common', 'stats');
$main_lang = LangLoader::get('main');

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
$user_lang        = (bool)retrieve(GET, 'lang', false);
$bot              = (bool)retrieve(GET, 'bot', false);
$erase            = (bool)retrieve(POST, 'erase', false);
$erase_occasional = (bool)retrieve(POST, 'erase-occasional', false);

$l_title = $lang['site'];
$l_title = ($visit || $visit_year) ? $main_lang['guest_s'] : $l_title;
$l_title = $pages ? $lang['page.s'] : $l_title;
$l_title = $referer ? $lang['referer.s'] : $l_title;
$l_title = $keyword ? $lang['keyword.s'] : $l_title;
$l_title = $members ? $main_lang['member_s'] : $l_title;
$l_title = $browser ? $lang['browser.s'] : $l_title;
$l_title = $os ? $lang['os'] : $l_title;
$l_title = $user_lang ? $lang['stat.lang'] : $l_title;
$l_title = $bot ? $lang['robots'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if (!empty($l_title))
	$Bread_crumb->add($lang['stats.module.title'], url('stats.php'));
	$Bread_crumb->add($l_title, '');
define('TITLE', $lang['stats.module.title'] . (!empty($l_title) ? ' - ' . $l_title : ''));
?>
