<?php

class Image
{
	function __construct($path)
	{
		$this->path = $path;		
	}
	
	private function get_property()
	{
		$property = getimagesize($this->path);
		return $property;
	}
	
	public function get_width()
	{
		$property = $this->get_property();
		$width = $property[0];
		return $width;
	}
	
	public function get_height()
	{
		$property = $this->get_property();
		$height = $property[1];
		return $height;
	}
	
	public function get_mime()
	{
		$property = $this->get_property();
		$mime = $property['mime'];
		return $mime;
	}
	
	public function get_size()
	{
		$size = filesize($this->path);
		
		return $size;
	
	}	

}

?>