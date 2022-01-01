<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 08 11
 * @since       PHPBoost 1.6 - 2007 05 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

if (defined('PHPBOOST') !== true)	exit;

define('WIKI_CREATE_ARTICLE', 0x01);
define('WIKI_CREATE_CAT', 0x02);
define('WIKI_RESTORE_ARCHIVE', 0x04);
define('WIKI_DELETE_ARCHIVE', 0x08);
define('WIKI_EDIT', 0x10);
define('WIKI_DELETE', 0x20);
define('WIKI_RENAME', 0x40);
define('WIKI_REDIRECT', 0x80);
define('WIKI_MOVE', 0x100);
define('WIKI_STATUS', 0x200);
define('WIKI_COM', 0x400);
define('WIKI_RESTRICTION', 0x800);
define('WIKI_READ', 0x1000);

?>
