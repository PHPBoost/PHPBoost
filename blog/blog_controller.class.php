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

// TODO change to import('mvc/controller');
mvcimport('mvc/controller');
mimport('blog/model/blog');

class BlogController extends AbstractController
{
	function view()
	{
		try
		{
			// Creates a blog and some blog posts
			$blog = new Blog('Mon Blog Perso');
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
//			global $Sql;
//            $Sql->query_inject('TRUNCATE phpboost_blog', __LINE__, __FILE__);
//            $Sql->query_inject('TRUNCATE phpboost_blogpost', __LINE__, __FILE__);
		}
		catch (Exception $ex)
		{
			echo '<hr />' . $ex->getMessage() . '<Br />';
			echo '<pre>';print_r($ex->getTraceAsString());echo '</pre><hr />';
		}
	}

	function view_by_id($id)
	{
		echo 'Je suis view by id avec comme id : ' . $id;
	}
	function special($id)
	{
		echo 'Je suis special : ' . $id . '<br />';
		echo 'param1=' . $_GET['param1'] . '&amp;param2=' . $_GET['param2'];
	}
	public function init()
	{
		echo '<br />Init()<hr />';
	}

	public function destroy()
	{
		echo '<hr />Destroy()<br />';
	}
}
?>