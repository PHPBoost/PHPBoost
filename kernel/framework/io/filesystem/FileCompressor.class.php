<?php
/*##################################################
 *                               FileCompressor.class.php
 *                            -------------------
 *   begin                : March 29, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

class FileCompressor 
{
	private $files_css = array();
	private $files_js = array();
	private $scripts_css = array();
	private $scripts_js = array();
	private $content_css = '';
	private $content_javascript = '';
	private $file_cache_css;
	private $file_cache_js;
	
	const CSS = 'css';
	const JAVASCRIPT = 'javascript';
	
	public function __construct() 
	{
		$this->file_cache_css = new File(PATH_TO_ROOT. '/cache/css/global.css');
		$this->file_cache_js = new File(PATH_TO_ROOT. '/cache/js/global.js');
	}
	
	public function add_file_javascript($file)
	{
		$this->add_file(self::JAVASCRIPT, $file);
	}
	
	public function add_file_css($file)
	{
		$this->add_file(self::CSS, $file);
	}
	
	public function add_script_javascript($script)
	{
		$this->add_script(self::JAVASCRIPT, $script);
	}
	
	public function add_script_css($script)
	{
		$this->add_script(self::CSS, $script);
	}
	
	public function generate_cache_files()
	{
		$this->file_cache_css->delete();
		$this->file_cache_css->lock();
		$this->file_cache_css->write($this->compress_css());
		$this->file_cache_css->unlock();
		$this->file_cache_css->close();
		$this->file_cache_css->change_chmod(0666);
		
		$this->file_cache_js->delete();
		$this->file_cache_js->lock();
		$this->file_cache_js->write($this->compress_javascript());
		$this->file_cache_js->unlock();
		$this->file_cache_js->close();
		$this->file_cache_js->change_chmod(0666);
	}

	private function add_file($type, $file)
	{
		if (!file_exists($file))
		{
			throw new Exception('File not '. $file .' exist !');
		}
		
		switch($type)
		{
			case self::CSS:
					$this->files_css[] = $file;
				break;
			case self::JAVASCRIPT:
					$this->files_js[] = $file;
				break;
			default:
				throw new Exception('Language '. $type .' not include in the File compressor');
		}
	}
	
	private function add_script($type, $script)
	{
		switch($type)
		{
			case self::CSS:
					$this->scripts_css[] = $script;
				break;
			case self::JAVASCRIPT:
					$this->scripts_js[] = $script;
				break;
			default:
				throw new Exception('Language '. $type .' not include in the File compressor');
		}
	}
	
	private function compress_javascript()
	{
		$content_js = '';
		foreach ($this->files_js as $file)
		{
			$content_js .= file_get_contents($file);
		}
		
		foreach ($this->scripts_js as $script)
		{
			$content_js .= $script;
		}
		
		require_once(PATH_TO_ROOT. '/kernel/lib/php/jsmin/jsmin.php');
		return trim(JSMin::minify($content_js));
	}
	
	private function compress_css()
	{
		$content_css = '';
		foreach ($this->files_css as $file)
		{
			$content_css .= file_get_contents($file);
		}
		
		foreach ($this->scripts_css as $script)
		{
			$content_css .= $script;
		}
		
		require_once(PATH_TO_ROOT. '/kernel/lib/php/csstidy/class.csstidy.php');
		$tidy = new csstidy();
		$tidy->load_template('high_compression');
		$tidy->set_cfg('sort_selectors', FALSE);
		$tidy->set_cfg('sort_properties', FALSE);
		$tidy->parse($content_css);
		return $tidy->print->plain();
	}
}
?>