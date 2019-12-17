<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 28
 * @since       PHPBoost 2.0 - 2007 11 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

load_module_lang('stats'); //Chargement de la langue du module.

$visit = (bool)retrieve(GET, 'visit', false);
$visit_year = (int)retrieve(GET, 'year', 0);
$pages = (bool)retrieve(GET, 'pages', false);
$pages_year = (int)retrieve(GET, 'pages_year', 0);
$referer = (bool)retrieve(GET, 'referer', false);
$keyword = (bool)retrieve(GET, 'keyword', false);
$members = (bool)retrieve(GET, 'members', false);
$browser = (bool)retrieve(GET, 'browser', false);
$os = (bool)retrieve(GET, 'os', false);
$all = (bool)retrieve(GET, 'all', false);
$user_lang = (bool)retrieve(GET, 'lang', false);
$bot = (bool)retrieve(GET, 'bot', false);

$l_title = $LANG['site'];
$l_title = ($visit || $visit_year) ? $LANG['guest_s'] : $l_title;
$l_title = $pages ? $LANG['page_s'] : $l_title;
$l_title = $referer ? $LANG['referer_s'] : $l_title;
$l_title = $keyword ? $LANG['keyword_s'] : $l_title;
$l_title = $members ? $LANG['member_s'] : $l_title;
$l_title = $browser ? $LANG['browser_s'] : $l_title;
$l_title = $os ? $LANG['os'] : $l_title;
$l_title = $user_lang ? $LANG['stat_lang'] : $l_title;
$l_title = $bot ? $LANG['robots'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if (!empty($l_title))
	$Bread_crumb->add($LANG['stats.module.title'], url('stats.php'));
	$Bread_crumb->add($l_title, '');
define('TITLE', $LANG['stats.module.title'] . (!empty($l_title) ? ' - ' . $l_title : ''));
?>
