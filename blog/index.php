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
mimport('blog/controllers/blog_controller');
mimport('blog/controllers/blog_post_controller');

$blog_controller = new BlogController();
$blog_post_controller = new BlogPostController();
$my_dispatcher = null;
try
{
	$my_dispatcher = new Dispatcher(array(
	new UrlDispatcherItem($blog_controller, 'blogs', '`^/?$`'),
	new UrlDispatcherItem($blog_controller, 'view', '`^/([0-9]+)/?$`'),
	new UrlDispatcherItem($blog_controller, 'create', '`^/create/?$`'),
	new UrlDispatcherItem($blog_controller, 'create_valid', '`^/create/valid/?$`'),
	new UrlDispatcherItem($blog_controller, 'edit', '`^/([0-9]+)/edit/?$`'),
	new UrlDispatcherItem($blog_controller, 'edit_valid', '`^/([0-9]+)/edit/valid/?$`'),
	new UrlDispatcherItem($blog_controller, 'delete', '`^/([0-9]+)/delete/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'posts', '`^/([0-9]+)/posts/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'view', '`^/([0-9]+)/posts/([0-9]+)/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'create', '`^/([0-9]+)/post/add/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'create_valid', '`^/([0-9]+)/post/add/valid/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'edit', '`^/[0-9]+/post/([0-9]+)/edit/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'edit_valid', '`^/[0-9]+/post/([0-9]+)/edit/valid/?$`'),
	new UrlDispatcherItem($blog_post_controller, 'delete', '`^/[0-9]+/post/delete/([0-9]+)/?$`')
	));

	try
	{
		$my_dispatcher->dispatch();
	}
	catch (NoUrlMatchException $ex)
	{
		// This is the only dispatcher exception that could be launched
		// in production.
		if (DEBUG)
		{
			echo $ex->getMessage();
		}
		else
		{
			redirect(PATH_TO_ROOT . '/member/404.php');
		}
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