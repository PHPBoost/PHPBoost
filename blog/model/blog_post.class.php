<?php

import('util/date');
mimport('blog/model/dao/blog_post_dao');

class BlogPost
{
	public function __construct($title = '', $content = '')
	{
		$this->title = $title;
		$this->content = $content;
	}

	public function get_date($date_format = DATE_FORMAT)
	{
		if ($this->creation_date != null)
        {
            return $this->creation_date->format($date_format);
        }
        return null;
	}
	
	public function get_id()
	{
		return $this->id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function get_creation_date()
	{
		if ($this->creation_date != null)
		{
			return $this->creation_date->get_timestamp();
		}
		return null;
	}

	public function get_blog_id()
	{
		return $this->blog_id;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_title($value)
	{
		$this->title = $value;
	}


	public function set_content($value)
	{
		$this->content = $value;
	}

	public function set_creation_date($value)
	{
		if ($value instanceof Date)
		{
		$this->creation_date = $value;
		}
		else
		{
			$this->creation_date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $value);
		}
	}

	public function set_blog_id($value)
	{
		$this->blog_id = $value;
	}

	private $id;
	private $title;
	private $content;
	private $creation_date;
	private $blog_id;
}
?>