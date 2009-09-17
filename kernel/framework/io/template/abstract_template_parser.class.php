<?php
/*##################################################
 *                           abstract_template_parser.class.php
 *                            -------------------
 *   begin                : June 18 2009
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

import('io/template/template_parser');

abstract class AbstractTemplateParser implements TemplateParser
{
	protected $filepath;
	protected $cache_filepath;
	protected $content;
	protected $template;
	
	public function parse($template_object, $filepath)
	{
		$this->template = $template_object;
		$this->filepath = $filepath;
		$this->compute_cache_filepath();
		if (!$this->is_in_cache())
		{
			$this->generate_cache();
		}
		$this->execute();
	}
	
	protected abstract function compute_cache_filepath();
	
	private function is_in_cache()
	{	
		if (file_exists($this->cache_filepath))
		{
			return @filemtime($this->filepath) <= @filemtime($this->cache_filepath) && @filesize($this->cache_filepath) !== 0;
		}
		return false;
	}
	
	private function generate_cache()
	{
		$this->load();
		$this->do_parse();
		$this->clean();
		$this->optimize();
		$this->save();
	}
	
	protected abstract function execute();
	
	private function load()
	{
		$this->content = @file_get_contents_emulate($this->filepath);
		if ($this->content === false)
		{
			die('Template::load(): The ' . $this->filepath . ' template loading failed.');
		}
		if (empty($this->content))
		{
			die('Template::load(): The ' . $this->filepath . ' template is empty.');
		}
		
		return true;
	}
	
	protected abstract function do_parse();
	
	protected function clean()
	{
		$this->content = preg_replace(
			array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'),
			array('', '', '', ''),
			$this->content
		);
	}
	
	protected function optimize()
	{
	}
	
	private function save()
	{
		import('io/filesystem/file');
		$file = new File($this->cache_filepath);
		$file->open(WRITE);
		$file->lock();
		$file->write($this->content);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}
}


?>