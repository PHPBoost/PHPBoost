<?php

import('mvc/model/sql_dao');
import('mvc/model/mapping_model');

class BlogPostDAO extends SQLDAO
{
	public static function instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new BlogPostDAO();
		}
		return self::$instance;
	}

	public function __construct()
	{
		parent::__construct(
		new Model('BlogPost', new ModelField('id', 'integer', 12), array(
		new ModelField('title', 'string', 64),
		new ModelField('content', 'string', 16777215),
		new ModelField('creation_date', 'integer', 16),
		new ModelField('blog_id', 'integer', 12),
		)));
	}

	public function save(PropertiesMapInterface $blog_post)
	{
		if ($blog_post->get_creation_date() === null)
		{
			import('util/date');
			$blog_post->set_creation_date(new Date());
		}
		parent::save($blog_post);
	}

	public function find_by_blog_id($blog_id, $offset = 0, $max_results = 100, $most_recent_first = true)
	{
//		return $this->get_blog_id_criteria($blog_id)->results_list($max_results, $offset,
//		    'creation_date', $most_recent_first ? ICriteria::DESC : ICriteria::ASC);
	}

	public function delete_all_blog_post($blog_id)
	{
//		$this->get_blog_id_criteria($blog_id)->delete();
	}

	private function get_blog_id_criteria($blog_id)
	{
//		$criteria = $this->create_criteria();
//		$restriction = $criteria->create_restriction();
//		$criteria->add($restriction->eq($this->model->field('blog_id'), $blog_id));
//		return $criteria;
	}

	private static $instance;
}

?>