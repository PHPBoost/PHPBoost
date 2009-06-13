<?php
/*##################################################
 *                           sql_dao_builder.class.php
 *                            -------------------
 *   begin                : June 13 2009
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

mvcimport('mvc/dao/builder/idao_builder');

abstract class SQLDAOBuilder implements IDAOBuilder
{
	public function __construct($model, $cache_path = '/blog/mvc/cache/')
	{
		$this->model = $model;
		$this->cache_path = PATH_TO_ROOT . $cache_path;
	}

	abstract protected function cache_classname();


	protected function generate_content($tpl_name = '')
	{
		import('io/template');
		// TODO change path here
		$tpl = new Template('/blog/mvc/dao/builder/' . $tpl_name);
		$tpl->assign_vars(array(
           'CLASSNAME' => $this->model->name(),
           'TABLE_NAME' => PREFIX . $this->model->name(),
           'PK_NAME' => $this->model->name(),
           'PK_GETTER' => $this->model->primary_key()->getter(),
           'PK_SETTER' => $this->model->primary_key()->setter(),
		));
		$fields = $this->model->fields();
		foreach ($fields as $field)
		{
			$tpl->assign_block_vars('fields', array(
           'NAME' => $field->name(),
           'GETTER' => $field->getter(),
           'SETTER' => $field->setter(),
           'TYPE' => $field->type(),
           'LENGTH' => $field->length(),
			));
		}
		return $tpl->parse(TEMPLATE_STRING_MODE);
	}

	private function generate()
	{
		import('io/fse/file');
		$file = new File($this->full_file_path());
		$file->write("<?php\n" . $this->generate_content() . "\n?>");
		$file->close();
	}

	public protected function full_file_path()
	{
		return self::cache_path() . $this->cache_classname() . '.class.php';
	}

	public function get_cached_instance()
	{
		$classname = $this->cache_classname();
		$cache_file_path = $this->full_file_path();
		if (!@include_once $cache_file_path)
		{
			$this->generate();
		}
		require_once $cache_file_path . '.class.php';

		if (class_exists($classname))
		{
			return new $classname;
		}
		else
		{
			// TODO Special exception here
			throw new Exception('Class "' . $classname . '" doesn\'t exist');
		}
	}

	public static function cache_path()
	{
		return PATH_TO_ROOT . '/blog/mvc/cache/';
	}

	protected $model;
	private $cache_path;
}
?>