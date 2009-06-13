<?php

mvcimport('mvc/dao/abstract_dao');

class BlogPostDAO extends AbstractDAO
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
		new Model('BlogPost', new ModelField('id', 'integer', 12), array(
		new ModelField('title', 'string', 64),
		new ModelField('content', 'string', 65535),
		new ModelField('date', 'integer', 16),
		new ModelField('blog_id', 'integer', 12),
		)));
	}
	
	private static $instance;
}

?>