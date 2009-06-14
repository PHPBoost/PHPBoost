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

define('TITLE', 'Blog');
defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';
// TODO remove this line (content will be in functions.inc.php already imported in begin.php
require_once PATH_TO_ROOT . '/blog/func.inc.php';

mvcimport('mvc/dispatcher');
mimport('blog/blog_controller');

$my_controller = new BlogController();
$my_dispatcher = null;
try
{
	$my_dispatcher = new Dispatcher(array(
	new UrlDispatcherItem($my_controller, 'list_blogs', '`^/?$`'),
	new UrlDispatcherItem($my_controller, 'view_blog', '`^/([0-9]+)/?$`'),
	new UrlDispatcherItem($my_controller, 'create_blog', '`^/create/?$`'),
	new UrlDispatcherItem($my_controller, 'create_blog_valid', '`^/create/valid/?(?:\?token=.+)?$`'),
	new UrlDispatcherItem($my_controller, 'delete_blog', '`^/([0-9]+)/delete/?$`'),
	new UrlDispatcherItem($my_controller, 'edit_blog', '`^/([0-9]+)/edit/?$`'),
	new UrlDispatcherItem($my_controller, 'edit_blog_valid', '`^/([0-9]+)/edit/valid/?(?:\?token=.+)?$`'),
	new UrlDispatcherItem($my_controller, 'view_posts', '`^/([0-9]+)/posts/?$`'),
	new UrlDispatcherItem($my_controller, 'view_post', '`^/([0-9]+)/posts/([0-9]+)/?$`'),
	new UrlDispatcherItem($my_controller, 'add_post', '`^/([0-9]+)/add/?$`'),
	new UrlDispatcherItem($my_controller, 'delete_post', '`^/([0-9]+)/delete/([0-9]+)/?(?:\?token=.+)?$`'),
	));

	try
	{
		$my_dispatcher->dispatch();
	}
	catch (NoUrlMatchException $ex)
	{
		// This is the only dispatcher exception that could be launched
		// in production.
		echo $ex->getMessage();
		//redirect(PATH_TO_ROOT . '/member/404.php');
	}
	catch (NoSuchControllerMethodException $ex)
	{
		require_once PATH_TO_ROOT . '/kernel/header.php';
		// This exception should only be launched in development
		echo $ex->getMessage();
		require_once PATH_TO_ROOT . '/kernel/footer.php';
	}
}
catch (NoSuchControllerException $ex)
{
	// This exception should only be launched in development
	require_once PATH_TO_ROOT . '/kernel/header.php';
	echo $ex->getMessage();
	require_once PATH_TO_ROOT . '/kernel/footer.php';
}

//echo '<hr />';
//$url = Dispatcher::get_url('/blog', ''); $url = $url->absolute(); echo '<a href="' . $url . '">Blog</a><br />';
//$url = Dispatcher::get_url('/blog', '/'); $url = $url->absolute(); echo '<a href="' . $url . '">Blog</a><br />';
//$url = Dispatcher::get_url('/blog', '/view/'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog', '/view/42'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog', '/view/37/'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog/', '/view'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog/', '/none/'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog/', '/test/'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog/', '/special/42/?param1=42&amp;param2=37'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';
//$url = Dispatcher::get_url('/blog/', '/special/256/?param1=007&amp;param2=128'); $url = $url->absolute(); echo '<a href="' . $url . '">' . $url . '</a><br />';

?>