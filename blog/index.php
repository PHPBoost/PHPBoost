<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
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

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';
require_once PATH_TO_ROOT . '/kernel/header.php';

require_once PATH_TO_ROOT . '/blog/controler.class.php';
require_once PATH_TO_ROOT . '/blog/mvc/dispatcher.class.php';

$url = retrieve(GET, 'url', '/view/');

$my_controler = new Controler();
$my_dispatcher = new Dispatcher(array(
    new UrlDispatcherItem($my_controler, 'view', '`^/view/?$`'),
    new UrlDispatcherItem($my_controler, 'view_by_id', '`^/view/([0-9]+)/?$`'),
    new UrlDispatcherItem($my_controler, 'none', '`^/none/?$`'),
));

try
{
	$my_dispatcher->dispatch($url);
}
catch (NoUrlMatchException $ex)
{
	echo $ex->getMessage();
	$Errorh->handler($ex->getMessage(), $ex->getCode(), __LINE__, __FILE__);
}
catch (NoSuchControlerMethodException $ex)
{
    echo $ex->getMessage();
	$Errorh->handler($ex->getMessage(), $ex->getCode(), __LINE__, __FILE__);
}

require_once PATH_TO_ROOT . '/kernel/footer.php';

?>