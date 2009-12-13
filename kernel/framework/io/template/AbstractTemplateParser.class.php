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



abstract class AbstractTemplateParser implements TemplateParser
{
	protected $cache_filepath;
	protected $content;
	/**
	 * @var TemplateLoader
	 */
	protected $loader;
	/**
	 * @var Template
	 */
	protected $template;

	protected $resource = null;

	public function parse($template_object, $template_loader)
	{
		$this->template = $template_object;
		$this->loader = $template_loader;

		$this->compute_cache_filepath();
		if (!$this->is_cache_valid())
		{
			$this->generate_cache();
		}
		$this->execute();
		return $this->resource;
	}

	protected abstract function compute_cache_filepath();

	private function is_cache_valid()
	{
		return $this->loader->is_cache_file_valid($this->cache_filepath);
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
		$this->loader->load();
		$this->content = $this->loader->get_resource_as_string();
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

	protected function get_getvar_method_name($varname)
	{
		$method = 'var';
		$tiny_varname = $varname;

		$split_index = strpos($varname, '_');

		if ($split_index > 0)
		{
			$prefix = substr($varname, 0, $split_index);
			$tiny_var = substr($varname, $split_index + 1);
			switch ($prefix)
			{
				case 'L':
					$method = 'lang_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'E':
					$method = 'htmlescaped_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'J':
					$method = 'js_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'EL':
					$method = 'htmlescaped_lang_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'JL':
					$method = 'js_lang_var';
					$tiny_varname =& $tiny_var;
					break;
				default:
					break;
			}
		}

		return array('method' => 'get_' . $method, 'varname' => $tiny_varname);
	}

	private function save()
	{

		$file = new File($this->cache_filepath);
		$file->open(File::WRITE);
		$file->lock();
		$file->write($this->content);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}
}


?>