<?php
/*##################################################
 *                             blog_dao.class.php
 *                            -------------------
 *   begin                : June 02, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('BLOG_DAO__CLASS','blog_dao');

mvcimport('mvc/dao/abstract_dao');

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc
 */
class BlogDAO extends AbstractDAO
{
    public static function instance()
    {
        if (self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
	public function __construct()
	{
		parent::__construct(
			new Model('Blog', new ModelField('id', 'integer', 12), array(
	        new ModelField('title', 'string', 64),
	        new ModelField('description', 'string', 65535),
	        new ModelField('user_id', 'integer', 12)),
	        array(new ModelField(DB_TABLE_MEMBER . '.login', 'string', 255, 'member_login')),
	        array(DB_TABLE_MEMBER . '.user_id' => 'user_id')
        ));
	}

	public function save($blog)
	{
		parent::save($blog);
		$posts = $blog->get_added_posts();
		if (!empty($posts))
		{
			foreach ($posts as $post)
			{
				$post->set_blog_id($blog->get_id());
				BlogPostDAO::instance()->save($post);
			}
		}
	}
	
	public function before_save($blog)
	{
		$title = $blog->get_title();
		$description = $blog->get_description();
		if (empty($title) || empty($description))
		{
            throw new BlogValidationExceptionMissingFields();		
		}
	}
	
	public function delete($blog)
	{
		BlogPostDAO::instance()->delete_all_blog_post(is_numeric($blog) ? $blog : $blog->get_id());
		parent::delete($blog);
	}
	
	private static $instance;
}

class BlogValidationException extends ValidationException
{
	public function __construct($message = 'Blog Validation Exception')
	{
		parent::__construct($message);
	}
}

class BlogValidationExceptionMissingFields extends BlogValidationException { }
?>