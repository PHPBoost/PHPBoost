<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 1.6 - 2006 10 09
*/

include_once('../kernel/begin.php');
define('TITLE', '');
include_once('../kernel/header.php');

AppContext::get_response()->redirect('/wiki/wiki.php');

?>
