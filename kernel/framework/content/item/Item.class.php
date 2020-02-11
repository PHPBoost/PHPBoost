<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 11
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class Item
{
	protected $id;
	protected $id_category;
	protected $title;
	protected $rewrited_title;
	protected $content;
	protected $author_user;
	protected $creation_date;
	protected $update_date;
	protected $notation;
	protected $keywords;
	protected $sources;

	protected $published;
	protected $publishing_start_date;
	protected $publishing_end_date;
	protected $end_date_enabled;

	protected static $module_id;
	protected static $module;
	protected $additional_attributes_values = array();
	protected static $additional_attributes_list = array();
	protected static $additional_attributes_items_table_fields = array();
	protected static $additional_attributes_items_table_options = array();

	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;

	const NOT_PUBLISHED = 0;
	const PUBLISHED = 1;
	const DEFERRED_PUBLICATION = 2;

	const ASC = 'ASC';
	const DESC = 'DESC';

	public static function __static()
	{
		self::$module_id = Environment::get_running_module_name();
		self::$module    = ModulesManager::get_module(self::$module_id);
	}

	public function __construct($module_id = '')
	{
		if ($module_id)
		{
			self::$module_id = $module_id;
			self::$module    = ModulesManager::get_module(self::$module_id);
		}
		else
		{
			self::__static();
		}
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id(int $id)
	{
		$this->id = $id;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_id_category(int $id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public static function get_title_label()
	{
		return 'title';
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($value)
	{
		$this->title = $value;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_title($value)
	{
		$this->rewrited_title = $value;
	}

	public function rewrited_title_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public function get_name()
	{
		return $this->title;
	}

	public function set_name($value)
	{
		$this->title = $value;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_name($value)
	{
		$this->rewrited_title = $value;
	}

	public function rewrited_name_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public static function get_content_label()
	{
		return 'content';
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $date)
	{
		$this->creation_date = $date;
	}

	public function get_update_date()
	{
		return $this->update_date;
	}

	public function set_update_date(Date $date)
	{
		$this->update_date = $date;
	}

	public function get_notation()
	{
		return $this->notation;
	}

	public function set_notation(Notation $notation)
	{
		$this->notation = $notation;
	}

	public function get_keywords()
	{
		if ($this->keywords === null)
		{
			$this->keywords = KeywordsService::get_keywords_manager(self::$module_id)->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}

	public function get_sources()
	{
		return $this->sources;
	}

	public function add_source($source)
	{
		$this->sources[] = $source;
	}

	public function set_sources($sources)
	{
		$this->sources = $sources;
	}

	public function set_publishing_state($state)
	{
		$this->published = $state;
	}

	public function get_publishing_state()
	{
		return $this->published;
	}

	public function is_published()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publishing_state() == self::PUBLISHED || ($this->get_publishing_state() == self::DEFERRED_PUBLICATION && $this->get_publishing_start_date()->is_anterior_to($now) && ($this->end_date_enabled ? $this->get_publishing_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
			case self::PUBLISHED:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::DEFERRED_PUBLICATION:
				return $this->is_published() ? LangLoader::get_message('status.approved.now', 'common') : LangLoader::get_message('status.approved.date', 'common');
			break;
		}
	}

	public function get_status_class()
	{
		switch ($this->published) {
			case self::NOT_PUBLISHED:
				return 'draft';
			break;
			case self::PUBLISHED:
				return 'published';
			break;
			case self::DEFERRED_PUBLICATION:
				return $this->is_published() ? 'published' : 'waiting';
			break;
		}
	}

	public function set_publishing_start_date(Date $publishing_start_date)
	{
		$this->publishing_start_date = $publishing_start_date;
	}

	public function get_publishing_start_date()
	{
		return $this->publishing_start_date;
	}

	public function set_publishing_end_date(Date $publishing_end_date)
	{
		$this->publishing_end_date = $publishing_end_date;
		$this->end_date_enabled = true;
	}

	public function get_publishing_end_date()
	{
		return $this->publishing_end_date;
	}

	public function end_date_enabled()
	{
		return $this->end_date_enabled;
	}

	private function get_authorizations_checker()
	{
		return self::$module->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, self::$module_id) : ItemsAuthorizationsService::check_authorizations(self::$module_id);
	}

	public function is_authorized_to_add()
	{
		return $this->get_authorizations_checker()->write() || $this->get_authorizations_checker()->contribution();
	}

	public function is_authorized_to_manage()
	{
		return $this->get_authorizations_checker()->moderation() || (($this->get_authorizations_checker()->write() || ($this->get_authorizations_checker()->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	protected static function add_additional_attribute($id, array $parameters)
	{
		if (isset($parameters['key']))
		{
			if ($parameters['key'] == true)
				self::$additional_attributes_items_table_options[$id] = array('type' => 'key', 'fields' => $id);
			
			unset($parameters['key']);
		}
		
		if (isset($parameters['fulltext']))
		{
			if ($parameters['fulltext'] == true)
				self::$additional_attributes_items_table_options[$id] = array('type' => 'fulltext', 'fields' => $id);
			
			unset($parameters['fulltext']);
		}
		
		if (isset($parameters['is_url']))
		{
			if ($parameters['is_url'] == true)
				self::$additional_attributes_list[$id] = array('is_url' => $parameters['is_url']);
			
			unset($parameters['is_url']);
		}
		else
			self::$additional_attributes_list[$id] = array('is_url' => false);
		
		self::$additional_attributes_items_table_fields[$id] = $parameters;
	}

	public function get_additional_attributes_list()
	{
		return self::$additional_attributes_list;
	}

	public function get_properties()
	{
		$category_properties = self::$module->get_configuration()->has_categories() ? array('id_category' => $this->get_id_category()) : array();
		
		return array_merge(
			$category_properties,
			array(
			'id'                                  => $this->get_id(),
			self::get_title_label()               => $this->get_title(),
			'rewrited_' . self::get_title_label() => $this->get_rewrited_title(),
			self::get_content_label()             => $this->get_content(),
			'author_user_id'                      => $this->get_author_user()->get_id(),
			'creation_date'                       => $this->get_creation_date()->get_timestamp(),
			'update_date'                         => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : 0,
			'published'                           => $this->get_publishing_state()
		), $this->get_additional_properties());
	}

	protected function get_additional_properties()
	{
		$properties = array();
		
		if (self::$module->get_configuration()->feature_is_enabled('deferred_publication'))
		{
			$properties['publishing_start_date'] = $this->get_publishing_start_date() !== null ? $this->get_publishing_start_date()->get_timestamp() : 0;
			$properties['publishing_end_date']   = $this->get_publishing_end_date() !== null ? $this->get_publishing_end_date()->get_timestamp() : 0;
			$this->end_date_enabled = !empty($properties['publishing_end_date']);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$properties['sources'] = TextHelper::serialize($this->get_sources());
		}
		
		foreach (self::$additional_attributes_list as $id => $attribute)
		{
			if ($attribute['is_url'])
				$properties[$id] = $this->additional_attributes_values[$id]->relative();
			else
				$properties[$id] = $this->additional_attributes_values[$id];
		}
		
		return $properties;
	}

	public function get_additional_property($id)
	{
		return $this->additional_attributes_values[$id];
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_title($properties[self::get_title_label()]);
		$this->set_rewrited_title($properties['rewrited_' . self::get_title_label()]);
		$this->set_content($properties[self::get_content_label()]);
		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		$this->update_date = !empty($properties['update_date']) ? new Date($properties['update_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->set_publishing_state($properties['published']);
		
		$author = new User();
		if (!empty($properties['author_user_id']))
			$author->set_properties($properties);
		else
			$author->init_visitor_user();

		$this->set_author_user($author);

		if (self::$module->get_configuration()->feature_is_enabled('notation'))
		{
			$notation = new Notation();
			$notation->set_module_name(self::$module_id);
			$notation->set_id_in_module($properties['id']);
			$notation->set_number_notes($properties['number_notes']);
			$notation->set_average_notes($properties['average_notes']);
			$notation->set_user_already_noted(!empty($properties['note']));
			$this->notation = $notation;
		}
		
		$this->set_additional_properties($properties);
	}

	protected function set_additional_properties(array $properties)
	{
		if (self::$module->get_configuration()->feature_is_enabled('deferred_publication'))
		{
			$this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date($properties['publishing_start_date'], Timezone::SERVER_TIMEZONE) : null;
			$this->publishing_end_date = !empty($properties['publishing_end_date']) ? new Date($properties['publishing_end_date'], Timezone::SERVER_TIMEZONE) : null;
			$this->end_date_enabled = !empty($properties['publishing_end_date']);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$this->set_sources(!empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : array());
		}
		
		foreach (self::$additional_attributes_list as $id => $attribute)
		{
			if (isset($properties[$id]))
			{
				$this->set_additional_property($id, $properties[$id], $attribute['is_url']);
			}
		}
	}

	public function set_additional_property($id, $value, $is_url = false)
	{
		if ($is_url)
			$this->additional_attributes_values[$id] = new Url($value);
		else
			$this->additional_attributes_values[$id] = $value;
	}

	protected function kernel_default_properties() {}

	protected function default_properties() {}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->published = self::PUBLISHED;
		$this->sources = array();
		$this->publishing_start_date = new Date();
		$this->publishing_end_date = new Date();
		$this->kernel_default_properties();
		$this->default_properties();
	}

	public function clean_publishing_start_and_end_date()
	{
		$this->publishing_start_date = null;
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}

	public function clean_publishing_end_date()
	{
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}

	public static function get_sorting_fields_list()
	{
		$common_lang = LangLoader::get('common');
		
		$fields_list = array_merge(
			array(
				self::get_title_label() => array('database_field' => self::get_title_label(), 'label' => $common_lang['form.title'], 'icon' => 'fa fa-sort-alpha-up'),
				'author'                => array('database_field' => 'display_name', 'label' => $common_lang['author'], 'icon' => 'far fa-user'),
				'date'                  => array('database_field' => 'creation_date', 'label' => $common_lang['form.date.creation'], 'icon' => 'far fa-calendar-alt'),
				'update_date'           => array('database_field' => 'update_date', 'label' => $common_lang['form.date.update'], 'icon' => 'far fa-calendar-plus')
			),
			self::get_kernel_additional_sorting_fields(),
			self::get_additional_sorting_fields()
		);
		
		if (self::$module && self::$module->get_configuration()->feature_is_enabled('comments') && CommentsConfig::load()->module_comments_is_enabled(self::$module_id))
			$fields_list['comments'] = array('database_field' => 'number_comments', 'label' => $common_lang['sort_by.comments.number'], 'icon' => 'far fa-comments');
		if (self::$module && !self::$module->get_configuration()->feature_is_enabled('notation') && ContentManagementConfig::load()->module_notation_is_enabled(self::$module_id))
			$fields_list['notes'] = array('database_field' => 'average_notes', 'label' => $common_lang['sort_by.best.note'], 'icon' => 'far fa-star');
		
		return $fields_list;
	}

	protected static function get_kernel_additional_sorting_fields()
	{
		return array();
	}

	protected static function get_additional_sorting_fields()
	{
		return array();
	}

	public static function get_sorting_field_options()
	{
		$sort_options = array();
		
		foreach (self::get_sorting_fields_list() as $id => $parameters)
		{
			$sort_options[] = new FormFieldSelectChoiceOption($parameters['label'], $id, array('data_option_icon' => $parameters['icon']));
		}
		
		return $sort_options;
	}

	public static function get_sorting_mode_options()
	{
		$common_lang = LangLoader::get('common');
		return array(
			new FormFieldSelectChoiceOption($common_lang['sort.asc'], TextHelper::strtolower(self::ASC), array('data_option_icon' => 'fa fa-arrow-up')),
			new FormFieldSelectChoiceOption($common_lang['sort.desc'], TextHelper::strtolower(self::DESC), array('data_option_icon' => 'fa fa-arrow-down'))
		);
	}

	public function get_template_vars()
	{
		$categories_template_vars = $comments_template_vars = $notation_template_vars = $newcontent_template_vars = $sources_template_vars = $idcard_template_vars = array();
		
		if (self::$module->get_configuration()->has_categories())
		{
			$category = $this->get_category();
			
			$categories_template_vars = array(
				'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
				'CATEGORY_ID'          => $category->get_id(),
				'CATEGORY_NAME'        => $category->get_name(),
				'CATEGORY_DESCRIPTION' => $category->get_description(),
				'U_CATEGORY'           => CategoriesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
				'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
				'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($category->get_id())->rel()
			);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('comments'))
		{
			$comments_number = CommentsService::get_comments_number(self::$module_id, $this->get_id());
			
			$comments_template_vars = array(
				'C_SEVERAL_COMMENTS' => $comments_number > 1,
				'COMMENTS_LABEL'     => CommentsService::get_number_and_lang_comments(self::$module_id, $this->get_id()),
				'COMMENTS_NUMBER'    => $comments_number,
				'U_COMMENTS'         => self::$module->get_configuration()->has_categories() ? ItemsUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel() : ItemsUrlBuilder::display_item_comments($this->get_id(), $this->get_rewrited_title())->rel(),
			);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('notation'))
		{
			$notation_template_vars = array(
				'STATIC_NOTATION' => $this->get_notation()->get_number_notes() > 0 ? NotationService::display_static_image($this->get_notation()) : '&nbsp;'
			);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('newcontent'))
		{
			$newcontent_template_vars = array(
				'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date(self::$module_id, $this->publishing_start_date != null ? $this->publishing_start_date->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_published()
			);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$sources_template_vars = array(
				'C_SOURCES' => $this->get_sources()
			);
		}
		
		// if (self::$module->get_configuration()->feature_is_enabled('idcard'))
		// {
			// $idcard_template_vars = array(
				// 'C_ID_CARD' => ContentManagementConfig::load()->module_id_card_is_enabled(self::$module_id) && $this->is_published(),
				// 'ID_CARD'   => IdcardService::display_idcard($this->get_author_user())
			// );
		// }

		$content            = FormatingHelper::second_parse($this->content);
		$author             = $this->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->update_date, 'update_date'),
			$categories_template_vars,
			$comments_template_vars,
			$notation_template_vars,
			$newcontent_template_vars,
			$sources_template_vars,
			$idcard_template_vars,
			array(
			// Conditions
			'C_CONTROLS'           => $this->is_authorized_to_manage(),
			'C_AUTHOR_EXIST'       => $author->get_id() !== User::VISITOR_LEVEL,
			'C_AUTHOR_GROUP_COLOR' => !empty($author_group_color),

			// Item parameters
			'ID'                                              => $this->get_id(),
			TextHelper::strtoupper(self::get_title_label())   => $this->get_title(),
			TextHelper::strtoupper(self::get_content_label()) => $content,
			'AUTHOR_DISPLAY_NAME'                             => $author->get_display_name(),
			'AUTHOR_LEVEL_CLASS'                              => UserService::get_level_class($author->get_level()),
			'AUTHOR_GROUP_COLOR'                              => $author_group_color,

			// Links
			'U_AUTHOR'      => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_ITEM'        => self::$module->get_configuration()->has_categories() ? ItemsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title(), self::$module->get_id())->rel() : ItemsUrlBuilder::display_item($this->get_id(), $this->get_rewrited_title(), self::$module->get_id())->rel(),
			'U_EDIT'        => ItemsUrlBuilder::edit($this->id)->rel(),
			'U_DELETE'      => ItemsUrlBuilder::delete($this->id)->rel(),
			'U_SYNDICATION' => SyndicationUrlBuilder::rss(self::$module_id, $this->id_category)->rel()
			),
			$this->get_kernel_additional_template_vars($content),
			$this->get_additional_template_vars()
		);
	}

	protected function get_kernel_additional_template_vars()
	{
		return array();
	}

	protected function get_additional_template_vars()
	{
		return array();
	}

	public function get_template_source_vars($source_name)
	{
		$vars = array();
		$sources = $this->get_sources();

		if (isset($sources[$source_name]))
		{
			$vars = array(
				'C_SEPARATOR' => array_search($source_name, array_keys($sources)) < count($sources) - 1,
				'NAME'        => $source_name,
				'URL'         => $sources[$source_name]
			);
		}

		return $vars;
	}

	public static function create_items_table($module_id = '')
	{
		if ($module_id)
		{
			self::$module_id = $module_id;
			self::$module    = new Module($module_id, true);
		}
		
		$kernel_additional_fields = $kernel_additional_indexes = array();
		
		if (self::$module->get_configuration()->has_categories())
		{
			$kernel_additional_fields['id_category'] = array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0);
			$kernel_additional_indexes['id_category'] = array('type' => 'key', 'fields' => 'id_category');
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('deferred_publication'))
		{
			$kernel_additional_fields['publishing_start_date'] = array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0);
			$kernel_additional_fields['publishing_end_date'] = array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0);
		}
		
		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$kernel_additional_fields['sources'] = array('type' => 'text', 'length' => 65000);
		}
		
		$fields = array_merge(array(
			'id'                                  => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'autoincrement' => true),
			self::get_title_label()               => array('type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_' . self::get_title_label() => array('type' => 'string',  'length' => 255, 'default' => "''"),
			self::get_content_label()             => array('type' => 'text',    'length' => 16777215),
			'author_user_id'                      => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
			'creation_date'                       => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
			'update_date'                         => array('type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0),
			'published'                           => array('type' => 'integer', 'length' => 1,   'notnull' => 1, 'default' => 0)
		), $kernel_additional_fields, self::$additional_attributes_items_table_fields);

		$options = array(
			'primary' => array('id'),
			'indexes' => array_merge(array(
				'title'   => array('type' => 'fulltext', 'fields' => self::get_title_label()),
				'content' => array('type' => 'fulltext', 'fields' => self::get_content_label())
				), $kernel_additional_indexes, self::$additional_attributes_items_table_options
			)
		);

		PersistenceContext::get_dbms_utils()->create_table(self::$module->get_configuration()->get_items_table_name(), $fields, $options);
	}
}
?>
