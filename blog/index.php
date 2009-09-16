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

import('mvc/dispatcher/dispatcher');
mimport('blog/controllers/blog_controller');
mimport('blog/controllers/blog_post_controller');

$url_controller_mappers = array(new UrlControllerMapper('BlogController', '`/java/(.*)`'));
$url_controller_method_mappers = array(
	new UrlControllerMethodMapper('BlogController', 'test', '`^/test/?$`'),
	new UrlControllerMethodMapper('BlogController', 'blogs', '`^/?$`'),
	new UrlControllerMethodMapper('BlogController', 'view', '`^/([0-9]+)/?$`'),
	new UrlControllerMethodMapper('BlogController', 'create', '`^/create/?$`'),
	new UrlControllerMethodMapper('BlogController', 'create_valid', '`^/create/valid/?$`'),
	new UrlControllerMethodMapper('BlogController', 'edit', '`^/([0-9]+)/edit/?$`'),
	new UrlControllerMethodMapper('BlogController', 'edit_valid', '`^/([0-9]+)/edit/valid/?$`'),
	new UrlControllerMethodMapper('BlogController', 'delete', '`^/([0-9]+)/delete/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'posts', '`^/([0-9]+)/posts/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'view', '`^/([0-9]+)/posts/([0-9]+)/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'create', '`^/([0-9]+)/post/add/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'create_valid', '`^/([0-9]+)/post/add/valid/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'edit', '`^/[0-9]+/post/([0-9]+)/edit/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'edit_valid', '`^/[0-9]+/post/([0-9]+)/edit/valid/?$`'),
	new UrlControllerMethodMapper('BlogPostController', 'delete', '`^/[0-9]+/post/delete/([0-9]+)/?$`')
);
Dispatcher::do_dispatch($url_controller_mappers, $url_controller_method_mappers);

?>