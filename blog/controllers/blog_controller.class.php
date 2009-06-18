<?php
/*##################################################
 *                           controller.class.php
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

mvcimport('mvc/view');
import('modules/modules_discovery_service');

mimport('blog/controllers/abstract_blog_controller');
mimport('blog/models/blog');


class BlogController extends AbstractBlogController
{
	public function blogs()
	{
		$view = new View('blog/list.tpl');
		$this->init_env($view);
		$blogs = BlogDAO::instance()->find_all(0, 20, 'creation_date', ICriteria::DESC);
		$view->assign_vars(array(
            'U_CREATE' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE)->absolute(),
            'U_LIST' => Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute()
		));

		foreach ($blogs as $blog)
		{
			$view->assign_block_vars('blogs', array(
                'TITLE' => $blog->get_title(),
                'DESCRIPTION' => second_parse($blog->get_description()),
        	    'E_TITLE' => htmlspecialchars($blog->get_title()),
                'U_DETAILS' => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
                'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
                'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute(),
                'USER' => $blog->get_property('member_login')
			));
		}
		$view->parse();
	}

	public function view($blog_id, $page = 1)
	{
		$view = new View('blog/blog.tpl');
		$blog = BlogDAO::instance()->find_by_id($blog_id);
		if ($blog === null)
		{
			// TODO error message here
			$view->assign_vars(array('L_ERROR_MESSAGE' => 'ERROR_MESSAGE'));
			die('error');
		}

		$this->init_env($view, array( $blog->get_title() => ''));
		$posts = BlogPostDAO::instance()->find_by_blog_id($blog->get_id(), ($page - 1) * self::POSTS_PER_PAGE, $page * self::POSTS_PER_PAGE);

		$view->assign_vars(array(
            'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
            'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute(),
            'TITLE' => $blog->get_title(),
            'DESCRIPTION' => second_parse($blog->get_description()),
            'USER' => second_parse($blog->get_property('member_login'))
		));

		foreach ($posts as $post)
		{
			$view->assign_block_vars('posts', array(
                'TITLE' => $post->get_title(),
                'CONTENT' => second_parse($post->get_content()),
                'CREATION_DATE' => $post->get_date()
			));
		}

		$view->parse();
	}

	public function create($blog = null, $error_message = null, $blog_id = -1)
	{
		$view = new View('blog/save.tpl');
		if ($blog_id >= 0)
		{
			$this->init_env($view, array(
			$blog->get_title() => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
			$this->lang['edit'] => $blog->action_url(Blog::ACTION_EDIT)->absolute()
			));
			$view->assign_vars(array(
                'U_FORM_VALID' => $blog->action_url(Blog::ACTION_EDIT_VALID)->absolute(),
                'L_SAVE_BLOG' => sprintf($this->lang['edit_blog'], $blog->get_title()),
                'TITLE' => $blog->get_title(),
                'EL_SAVE' => $this->lang['edit']
			));
		}
		else
		{
			$this->init_env($view, array($this->lang['create_new_blog'] => ''));
			$view->assign_vars(array(
	            'U_FORM_VALID' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE_VALID)->absolute(),
                'L_SAVE_BLOG' => $this->lang['create_new_blog'],
                'EL_SAVE' => $this->lang['create']
			));
		}

		$view->assign_vars(array(
            'TITLE_MAX_LENGTH' => BlogDAO::instance()->get_model()->field('title')->length(),
		    'KERNEL_EDITOR' => display_editor('description')
		));
		if (!empty($error_message))
		{
			$view->assign_vars(array('ERROR_MESSAGE' => $error_message));
		}
		if ($blog instanceof Blog)
		{
			$blog->set_description(unparse($blog->get_description()));
			$view->assign_vars(array(
	            'DESCRIPTION' => $blog->get_description()
			));
		}
		$view->parse();
	}

	public function create_valid($blog_id = -1)
	{
		$this->check_token();
		$blog = new Blog(retrieve(POST, 'title', ''), strparse(retrieve(POST, 'description', '', TSTRING_AS_RECEIVED), array(), false));
		if ($blog_id >= 0)
		{
			$blog->set_id($blog_id);
		}
		try
		{
			BlogDAO::instance()->save($blog);
			redirect($blog->action_url(Blog::ACTION_DETAILS)->absolute());
		}
		catch (BlogValidationExceptionMissingFields $ex)
		{
			$this->create($blog, $this->lang['missing_fields']);
		}
		catch (ValidationException $ex)
		{
			// TODO process exception here thrown by
			// before_save method. You could implement this method
			// the way you want in order to check object completeness
			// or authorizations
		}
	}

	public function edit($blog_id)
	{
		$blog = BlogDAO::instance()->find_by_id($blog_id);
		$this->create($blog, null, $blog_id);
	}
	public function edit_valid($blog_id)
	{
		$this->create_valid($blog_id);
	}
	public function delete($blog_id)
	{
		$this->check_token();
		BlogDAO::instance()->delete($blog_id);
		redirect(Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute());
	}

	function test()
	{
		$this->init_env();
		try
		{
			// Creates a blog and some blog posts
			$blog = new Blog('Mon Blog Perso', 'Pas de description');
			$blog->add(new BlogPost('Mon Premier Post', 'Voici mon premier post.<br />
            J\'espère que vous appréciez le blog'));
			$blog->add(new BlogPost('Mon Second Post', 'Voici mon second post.<br />
            J\'espère que vous appréciez <strong>toujours</strong> le blog'));
			$blog->add(new BlogPost('Mon Troisième Post', 'Voici mon troisième post.<br />
            J\'espère que vous appréciez <strong>encore</strong> le blog'));
			BlogDAO::instance()->save($blog);

			// Retrieves the blog and the last 2 blog posts
			$blog_id = $blog->get_id();
			unset($blog);
			$blog = BlogDAO::instance()->find_by_id($blog_id);
			$posts = BlogPostDAO::instance()->find_by_blog_id($blog_id, 0, 2, true);

			echo '<h1>' . $blog->get_title() . '</h1>';
			foreach ($posts as $post)
			{
				echo '<h2>(' . $post->get_date(DATE_FORMAT_LONG) . ') ' . $post->get_title() . '</h2>';
				echo '<p>' . $post->get_content() . '</p>';
			}

			// Edit the blog and the third post
			$blog->set_title('Mon Nouveau Blog Perso');
			$posts[0]->set_title($posts[0]->get_title() . ' [EDITED]');
			$posts[0]->set_content($posts[0]->get_content() . '<br /> @+ ;)');
			BlogPostDAO::instance()->save($posts[0]);
			// Delete the 1° blog post
			BlogPostDAO::instance()->delete($posts[1]);
			// Adds another blog post
			$blog->add(new BlogPost('Mon Quatrième Post', 'Voici mon quatrième post.<br />
            J\'espère que vous appréciez <strong>encore et toujours</strong> le blog'));
			// Saves
			BlogDAO::instance()->save($blog);
			echo '<hr />';
			// Retrieves the blog and the last 5 blog posts
			unset($blog); unset($posts);
			$blog = BlogDAO::instance()->find_by_id($blog_id);
			$posts = BlogPostDAO::instance()->find_by_blog_id($blog_id, 0, 5, false);

			echo '<h1>' . $blog->get_title() . '</h1>';
			foreach ($posts as $post)
			{
				echo '<h2>(' . $post->get_date(DATE_FORMAT_LONG) . ') ' . $post->get_title() . '</h2>';
				echo '<p>' . $post->get_content() . '</p>';
			}

			// Delete All
			BlogDAO::instance()->delete($blog);
			//          global $Sql;
			//            $Sql->query_inject('TRUNCATE phpboost_blog', __LINE__, __FILE__);
			//            $Sql->query_inject('TRUNCATE phpboost_blogpost', __LINE__, __FILE__);
		}
		catch (Exception $ex)
		{
			echo '<hr />' . $ex->getMessage() . '<Br />';
			echo '<pre>';print_r($ex->getTraceAsString());echo '</pre><hr />';
		}
	}


	const POSTS_PER_PAGE = 3;
}
?>