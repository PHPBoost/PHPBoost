<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 28
 * @since       PHPBoost 2.0 - 2009 01 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class DefaultModuleSetup implements ModuleSetup
{
	protected static $db_querier;
	protected static $dbms_utils;
	private $module_id;
	protected $module_configuration;
	private $id_category = 1;
	private $id_item = 1;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
		self::$dbms_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function __construct($module_id = '')
	{
		if ($module_id)
		{
			$this->module_id = $module_id;
			$module = new Module($module_id, true);
			$this->module_configuration = $module->get_configuration();
		}
	}
	
	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#check_environment()
	 */
	public function check_environment()
	{
		return new ValidationResult();
	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#install()
	 */
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_default_data();
	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#uninstall()
	 */
	public function uninstall()
	{
		$this->drop_tables();
		KeywordsService::get_keywords_manager()->delete_module_relations();
		
		if ($this->module_id && $this->module_configuration->get_configuration_name())
			ConfigManager::delete($this->module_id, 'config');
	}

	/* (non-PHPdoc)
	 * @see kernel/framework/phpboost/module/ModuleSetup#upgrade()
	 */
	public function upgrade($installed_version)
	{
		return null;
	}

	protected function get_sql_tables_list()
	{
		$tables_list = array();
		
		if ($this->module_id)
		{
			if ($this->module_configuration->has_items())
				$tables_list[] = $this->module_configuration->get_items_table_name();
			
			if ($this->module_configuration->has_categories())
				$tables_list[] = $this->module_configuration->get_categories_table_name();
		}
		
		return $tables_list;
	}

	private function drop_tables()
	{
		$tables_list = $this->get_sql_tables_list();
		
		if ($tables_list)
			self::$dbms_utils->drop($tables_list);
	}

	private function create_tables()
	{
		if ($this->module_id)
		{
			if ($this->module_configuration->has_items())
			{
				$item_class_name = $this->module_configuration->get_item_name();
				$item_class_name::create_items_table($this->module_id);
			}
			
			if ($this->module_configuration->has_categories())
			{
				$module_category_class = TextHelper::ucfirst($this->module_id) . 'Category';
				$category_class_name = (class_exists($module_category_class) && is_subclass_of($module_category_class, 'Category')) ? $module_category_class : ($this->module_configuration->feature_is_enabled('rich_categories') ? CategoriesManager::RICH_CATEGORY_CLASS : CategoriesManager::STANDARD_CATEGORY_CLASS);
				$category_class_name::create_categories_table($this->module_configuration->get_categories_table_name());
			}
		}
	}
	
	protected function insert_default_data()
	{
		if ($this->module_id)
		{
			$file = new Folder(PATH_TO_ROOT . '/' . $this->module_id . '/lang/' . LangLoader::get_locale() . '/install.php');
			if ($file->exists())
			{
				$lang = LangLoader::get('install', $this->module_id);
				$this->insert_default_categories($lang);
				$this->insert_default_items($lang);
			}
		}
	}
	
	protected function insert_default_categories($lang)
	{
		if (isset($lang['categories']) && is_array($lang['categories']))
		{
			foreach($lang['categories'] as $category)
			{
				$this->add_category($category['category.name'], isset($category['category.description']) ? $category['category.description'] : '');
			}
		}
	}
	
	protected function insert_default_items($lang)
	{
		if (isset($lang['items']) && is_array($lang['items']))
		{
			foreach($lang['items'] as $item)
			{
				$this->add_item($item['item.title'], $item['item.content'], isset($lang['item.summary']) ? $item['item.summary'] : '');
			}
		}
	}
	
	protected function add_category($name, $description = '', $thumbnail = FormFieldThumbnail::DEFAULT_VALUE, $id_parent = 0, $auth = '', $additional_fields = array())
	{
		self::$db_querier->insert($this->module_configuration->get_categories_table_name(), array_merge(array(
			'id' => $this->id_category,
			'id_parent' => $id_parent,
			'c_order' => $this->id_category,
			'auth' => $auth,
			'name' => $name,
			'rewrited_name' => Url::encode_rewrite($name),
			'description' => $description,
			'thumbnail' => $thumbnail
		), $additional_fields));
		$this->id_category++;
	}

	protected function add_item($title, $content, $summary = '', $thumbnail = FormFieldThumbnail::DEFAULT_VALUE, $id_category = Category::ROOT_CATEGORY, $additional_fields = array())
	{
		$fields = array(
			'id' => $this->id_item,
			'title' => $title,
			'rewrited_title' => Url::encode_rewrite($title),
			'content' => $content,
			'author_user_id' => 1,
			'creation_date' => time(),
			'update_date' => 0,
			'published' => Item::PUBLISHED
		);
		
		if ($this->module_configuration->has_categories())
		{
			$fields['id_category'] = $id_category ? $id_category : ($this->id_category - 1);
		}
		
		if ($this->module_configuration->feature_is_enabled('deferred_publication'))
		{
			$fields['publishing_start_date'] = 0;
			$fields['publishing_end_date'] = 0;
		}
		
		if ($this->module_configuration->feature_is_enabled('sources'))
		{
			$fields['sources'] = TextHelper::serialize(array());
		}
		
		if ($this->module_configuration->has_rich_items())
		{
			$fields['summary'] = $summary;
			$fields['thumbnail'] = $thumbnail;
			$fields['author_custom_name'] = '';
			$fields['views_number'] = 0;
		}
		
		self::$db_querier->insert($this->module_configuration->get_items_table_name(), array_merge($fields, $additional_fields));
		$this->id_item++;
	}
}
?>
