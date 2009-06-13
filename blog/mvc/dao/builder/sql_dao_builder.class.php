<?php
abstract class SQLDAOBuilder implements SQLDAOBuilder
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