<?php

mimport('blog/model/dao/blog_post_dao');

class BlogPost
{
	public function __construct($title = '', $content = '', $date = '')
	{
		$this->title = $title;
		$this->content = $content;
		$this->date = $date;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_title()
	{
		return $this->id;
	}

	public function get_content()
	{
		return $this->content;
	}

    public function get_date()
    {
        return $this->date;
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

    public function set_date($value)
    {
        $this->date = $value;
    }
    
    public function set_blog_id($value)
    {
        $this->blog_id = $value;
    }

	private $id;
	private $title;
	private $content;
    private $date;
    private $blog_id;
}
?>