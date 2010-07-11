<?php
class Resize
{
	private $path;
	private $width;
	private $height;
	private $repertory;
	
	public function __construct($path)
	{
		$this->path = $path;
	}

	private function get_proprity()
	{
		$image = new Image($this->path);
			
		return $image;

	}

	public function set_width($width)
	{
		$this->width = $width;
	}

	public function set_height($height)
	{
		$this->height = $height;
	}

	private function get_width()
	{
		if(!isset($this->width) && isset($this->height))
		{
			$width = $this->get_proprity()->get_height() / $this->height;
			$width = $this->get_proprity()->get_width() / $width;
				return $width;
		}
		else 
			return $this->width;
	}

	private function get_height()
	{
		if(!isset($this->height) && isset($this->width))
		{
			$height = $this->get_proprity()->get_width() / $this->width;
			$height = $this->get_proprity()->get_height() / $height;
				return $height;
		}
		else 
			return $this->height;
	}

	public function set_repertory($repertory)
	{
		$this->repertory = $repertory;

	}

	private function get_repertory()
	{
		if(!isset($this->repertory))
			return $this->path;
		else 
			return $this->repertory;
	}

	public function resize()
	{

		switch ($this->get_proprity()->get_mime()) {
			case 'image/jpeg':
					$this->original = imagecreatefromjpeg($this->path);
				break;
			case 'image/png':
					$this->original = imagecreatefrompng($this->path);
				break;
			case 'image/gif':
					$this->original = imagecreatefromgif($this->path);
				break;
			// TODO Erreur mime non prise en compte
		}
		
		if($this->get_proprity()->get_mime() == 'image/gif')
			$this->new = imagecreate($this->get_width(), $this->get_height()); 
		else
			$this->new = imagecreatetruecolor($this->get_width(), $this->get_height()); 
			
		imagecopyresized($this->new, $this->original, 0, 0, 0, 0, $this->get_width(), $this->get_height(), $this->get_proprity()->get_width(), $this->get_proprity()->get_height()); 
	
		switch ($this->get_proprity()->get_mime()) {
			case 'image/jpeg':
					imagejpeg($this->new, $this->get_repertory());
				break;
			case 'image/png':
					imagepng($this->new, $this->get_repertory());
				break;
			case 'image/gif':
					imagegif($this->new, $this->get_repertory());
				break;
			// TODO mime non prise en compte
		}


	}
	
	public function destroy()
	{
		imagedestroy($this->new);	
	}
	
}

?>