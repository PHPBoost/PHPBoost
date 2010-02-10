<?php
/*##################################################
 *                     CachedStringTemplateLoader.class.php
 *                            -------------------
 *   begin                : February 6, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class CachedStringTemplateLoader implements CacherTemplateLoader
{
	private $content = '';
	private $cache_file_path = '';

	public function __construct($content)
	{
		$this->content = $content;
		$this->compute_cache_file_path();
	}

	private function compute_cache_file_path()
	{
		$this->cache_file_path = PATH_TO_ROOT . '/cache/tpl/string-' . md5($this->content) . '.php';
	}

	public function get_cache_file_path()
	{
		if (!$this->file_cache_exists())
		{
			$content = $this->get_parsed_content();
			$this->generate_cache_file($content);
		}
		return $this->cache_file_path;
	}

	public function load()
	{
		if (!$this->file_cache_exists())
		{
			$content = $this->get_parsed_content();
			$this->generate_cache_file($content);
			return $content;
		}

		return file_get_contents_emulate($this->cache_file_path);
	}

	private function file_cache_exists()
	{
		return file_exists($this->cache_file_path) && @filesize($this->cache_file_path) !== 0;
	}

	private function generate_cache_file()
	{
		$cache_file = new File($this->cache_file_path);
		$cache_file->lock();
		$cache_file->write($this->get_parsed_content());
		$cache_file->unlock();
		$cache_file->close();
		$cache_file->change_chmod(0666);
	}

	private function get_parsed_content()
	{
		$parser = new DefaultTemplateParser();
		return $parser->parse($this->content);
	}

}
?>