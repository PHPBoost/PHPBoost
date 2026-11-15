<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 6.0 - 2019 12 20
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Item
{
	protected int $id = 0;
	protected int $id_category = 0;
	protected string $title = '';
	protected string $rewrited_title = '';
	protected string $content = '';
	protected ?User $author_user = null;
	protected ?Date $creation_date = null;
	protected ?Date $update_date = null;
	protected ?Notation $notation = null;
	protected array $keywords = [];
	protected array $sources = [];

	protected bool $published = false;
	protected ?Date $publishing_start_date = null;
	protected ?Date $publishing_end_date = null;
	protected bool $end_date_enabled = false;

	protected static string $module_id = '';
	protected static ?Module $module = null;
	protected array $additional_attributes_values              = [];
	protected array $additional_attributes_list                = [];
	protected array $additional_attributes_items_table_fields  = [];
	protected array $additional_attributes_items_table_options = [];

	protected bool $content_field_enabled    = true;
	protected bool $content_field_required   = true;
	protected bool $sub_categories_displayed = true;

	const READ_AUTHORIZATIONS         = 1;
	const WRITE_AUTHORIZATIONS        = 2;
	const CONTRIBUTION_AUTHORIZATIONS = 4;
	const DUPLICATION_AUTHORIZATIONS  = 8;
	const MODERATION_AUTHORIZATIONS   = 16;

	const NOT_PUBLISHED        = 0;
	const PUBLISHED            = 1;
	const DEFERRED_PUBLICATION = 2;

	const ASC  = 'ASC';
	const DESC = 'DESC';

	public function __construct(string $module_id = '')
	{
		self::$module_id = !empty($module_id) ? $module_id : Environment::get_running_module_name();
		self::$module = ModulesManager::get_module(self::$module_id);

		$this->set_kernel_additional_attributes_list();
		$this->set_additional_attributes_list();
	}

	public function get_id(): int
	{
		return $this->id;
	}

	public function set_id(int $id): void
	{
		$this->id = $id;
	}

	public function get_id_category(): int
	{
		return $this->id_category;
	}

	public function set_id_category(int $id_category): void
	{
		$this->id_category = $id_category;
	}

	public function get_category(): Category
	{
		return CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($this->id_category);
	}

	public static function get_title_label(): string
	{
		return 'title';
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function set_title(string $value): void
	{
		$this->title = $value;
	}

	public function get_rewrited_title(): string
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_title(string $value): void
	{
		$this->rewrited_title = $value;
	}

	public function rewrited_title_is_personalized(): bool
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public function get_name(): string
	{
		return $this->title;
	}

	public function set_name(string $value): void
	{
		$this->title = $value;
	}

	public function get_rewrited_name(): string
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_name(string $value): void
	{
		$this->rewrited_title = $value;
	}

	public function rewrited_name_is_personalized(): bool
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public static function get_content_label(): string
	{
		return 'content';
	}

	public function get_content(): string
	{
		return $this->content;
	}

	public function set_content(string $content): void
	{
		$this->content = $content;
	}

	public function get_author_user(): User
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_creation_date(): Date
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $date): void
	{
		$this->creation_date = $date;
	}

	public function get_update_date(): ?Date
	{
		return $this->update_date;
	}

	public function set_update_date(?Date $date): void
	{
		$this->update_date = $date;
	}

	public function has_update_date(): bool
	{
		return $this->update_date !== null && $this->update_date > $this->creation_date;
	}

	public function get_notation(): Notation
	{
		return $this->notation;
	}

	public function set_notation(Notation $notation): void
	{
		$this->notation = $notation;
	}

	public function get_keywords(): array
	{
		if ($this->keywords === null)
		{
			$this->keywords = KeywordsService::get_keywords_manager(self::$module_id)->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name(): array
	{
		return array_keys($this->get_keywords());
	}

	public function get_sources(): array
	{
		return $this->sources;
	}

	public function add_source(array $source)
	{
		$this->sources[] = $source;
	}

	public function set_sources(array $sources): void
	{
		$this->sources = $sources;
	}

	public function set_publishing_state(string $state): void
	{
		$this->published = $state;
	}

	public function get_publishing_state(): string
	{
		return $this->published;
	}

	public function is_published(): bool
	{
		$now = new Date();
		return $this->get_authorizations_checker()->read() && ($this->get_publishing_state() == self::PUBLISHED || ($this->get_publishing_state() == self::DEFERRED_PUBLICATION && $this->get_publishing_start_date()->is_anterior_to($now) && ($this->end_date_enabled() ? $this->get_publishing_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status(): string
	{
		switch ($this->published)
        {
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('common.status.draft', 'common-lang');
			case self::PUBLISHED:
				return LangLoader::get_message('common.status.published', 'common-lang');
			case self::DEFERRED_PUBLICATION:
				return $this->is_published() ? LangLoader::get_message('common.status.published', 'common-lang') : LangLoader::get_message('common.status.deffered.date', 'common-lang');
		}
        return '';
	}

	public function get_status_class(): string
	{
		switch ($this->published)
        {
			case self::NOT_PUBLISHED:
				return 'draft';
			case self::PUBLISHED:
				return 'published';
			case self::DEFERRED_PUBLICATION:
				return $this->is_published() ? 'published' : 'waiting';
		}
        return '';
	}

	public function set_publishing_start_date(Date $publishing_start_date): void
	{
		$this->publishing_start_date = $publishing_start_date;
	}

	public function get_publishing_start_date(): ?Date
	{
		return $this->publishing_start_date;
	}

	public function set_publishing_end_date(Date $publishing_end_date)
	{
		$this->publishing_end_date = $publishing_end_date;
		$this->end_date_enabled = true;
	}

	public function get_publishing_end_date(): ?Date
	{
		return $this->publishing_end_date;
	}

	public function end_date_enabled(): bool
	{
		return $this->end_date_enabled;
	}

	private function get_authorizations_checker(): mixed
	{
		return self::$module->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->id_category ? $this->id_category : Category::ROOT_CATEGORY, self::$module_id) : ItemsAuthorizationsService::check_authorizations(self::$module_id);
	}

	public function is_authorized_to_add(): bool
	{
		return $this->get_authorizations_checker()->write() || $this->get_authorizations_checker()->contribution();
	}

	public function is_authorized_to_duplicate(): bool
	{
        return self::$module->get_configuration()->has_duplication() && ($this->get_authorizations_checker()->write() || ($this->get_authorizations_checker()->contribution() && $this->get_authorizations_checker()->duplication()));
    }

	public function is_authorized_to_edit(): bool
	{
		return $this->get_authorizations_checker()->moderation() || (($this->get_authorizations_checker()->write() || ($this->get_authorizations_checker()->contribution() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))));
	}

	public function is_authorized_to_delete(): bool
	{
		return $this->get_authorizations_checker()->moderation() || (($this->get_authorizations_checker()->write() || ($this->get_authorizations_checker()->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	protected function add_additional_attribute(mixed $id, array $parameters): void
	{
		if (isset($parameters['key']))
		{
			if ($parameters['key'] == true)
            {
				$this->additional_attributes_items_table_options[$id] = ['type' => 'key', 'fields' => $id];
            }

			unset($parameters['key']);
		}

		if (isset($parameters['fulltext']))
		{
			if ($parameters['fulltext'] == true)
            {
				$this->additional_attributes_items_table_options[$id] = ['type' => 'fulltext', 'fields' => $id];
            }

			unset($parameters['fulltext']);
		}

		if (isset($parameters['is_url']))
		{
			if ($parameters['is_url'] == true)
            {
				$this->additional_attributes_list[$id] = ['is_url' => $parameters['is_url'], 'is_array' => false];
            }

			unset($parameters['is_url']);
		}
		elseif (isset($parameters['is_array']))
		{
			if ($parameters['is_array'] == true)
            {
				$this->additional_attributes_list[$id] = ['is_array' => $parameters['is_array'], 'is_url' => false];
            }

			unset($parameters['is_array']);
		}
		else
        {
			$this->additional_attributes_list[$id] = ['is_url' => false, 'is_array' => false];
        }

		foreach (['attribute_pre_content_field_parameters', 'attribute_post_content_field_parameters', 'attribute_options_field_parameters'] as $attribute_field)
		{
			if (isset($parameters[$attribute_field]))
			{
				$this->additional_attributes_list[$id][$attribute_field] = $parameters[$attribute_field];

				unset($parameters[$attribute_field]);
			}
		}

		$this->additional_attributes_items_table_fields[$id] = $parameters;
	}

	public function get_additional_attributes_list()
	{
		return $this->additional_attributes_list;
	}

	protected function set_kernel_additional_attributes_list(): void {}

	protected function set_additional_attributes_list(): void {}

	public function get_additional_attributes_items_table_fields(): array
	{
		return $this->additional_attributes_items_table_fields;
	}

	public function get_additional_attributes_items_table_options(): array
	{
		return $this->additional_attributes_items_table_options;
	}

	protected function disable_content_field(): void
	{
		$this->content_field_enabled = false;
	}

	public function content_field_enabled(): bool
	{
		return $this->content_field_enabled;
	}

	protected function unrequire_content_field(): void
	{
		$this->content_field_required = false;
	}

	public function content_field_required(): bool
	{
		return $this->content_field_required;
	}

	protected function hide_sub_categories(): void
	{
		$this->sub_categories_displayed = false;
	}

	public function sub_categories_displayed(): bool
	{
		return $this->sub_categories_displayed;
	}

	public function get_properties(): array
	{
		$category_properties = self::$module->get_configuration()->has_categories() ? ['id_category' => $this->get_id_category()] : [];
		$content_properties = $this->content_field_enabled ? [self::get_content_label() => $this->get_content()] : [];

		return array_merge(
			$category_properties,
			$content_properties,
			[
                'id'                                  => $this->get_id(),
                self::get_title_label()               => $this->get_title(),
                'rewrited_' . self::get_title_label() => $this->get_rewrited_title(),
                'author_user_id'                      => $this->get_author_user()->get_id(),
                'creation_date'                       => $this->get_creation_date()->get_timestamp(),
                'update_date'                         => $this->get_update_date() !== null ? $this->get_update_date()->get_timestamp() : 0,
                'published'                           => $this->get_publishing_state()
            ], $this->get_additional_properties()
        );
	}

	protected function get_additional_properties(): mixed
	{
		$properties = [];

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

		foreach ($this->additional_attributes_list as $id => $attribute)
		{
			if ($attribute['is_url'])
            {
				$properties[$id] = $this->additional_attributes_values[$id]->relative();
            }
			elseif ($attribute['is_array'])
            {
				$properties[$id] = TextHelper::serialize($this->additional_attributes_values[$id]);
            }
			else
            {
				$properties[$id] = $this->additional_attributes_values[$id];
            }
		}

		return $properties;
	}

	public function get_additional_property(string $id): mixed
	{
		return $this->additional_attributes_values[$id];
	}

	public function set_properties(array $properties): void
	{
		$this->set_id($properties['id']);
		$this->set_title($properties[self::get_title_label()]);
		$this->set_rewrited_title($properties['rewrited_' . self::get_title_label()]);

		if ($this->content_field_enabled)
        {
			$this->set_content($properties[self::get_content_label()]);
        }

		$this->set_creation_date(new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE));
		$this->update_date = !empty($properties['update_date']) ? new Date($properties['update_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->set_publishing_state($properties['published']);

		if (self::$module->get_configuration()->has_categories())
        {
			$this->set_id_category($properties['id_category']);
        }

		if (!($author = UserService::get_user($properties['author_user_id'])))
		{
			$author = new User();
			$author->init_visitor_user();
		}

		$this->set_author_user($author);

		if (self::$module->get_configuration()->feature_is_enabled('notation'))
		{
			$notation = new Notation();
			$notation->set_module_name(self::$module_id);
			$notation->set_id_in_module($properties['id']);
			$notation->set_notes_number(isset($properties['notes_number']) ? $properties['notes_number'] : 0);
			$notation->set_average_notes(isset($properties['average_notes']) ? $properties['average_notes'] : 0);
			$notation->set_user_already_noted(isset($properties['note']) && !empty($properties['note']));
			$this->notation = $notation;
		}

		$this->set_additional_properties($properties);
	}

	protected function set_additional_properties(array $properties): void
	{
		if (self::$module->get_configuration()->feature_is_enabled('deferred_publication'))
		{
			$this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date($properties['publishing_start_date'], Timezone::SERVER_TIMEZONE) : null;
			$this->publishing_end_date = !empty($properties['publishing_end_date']) ? new Date($properties['publishing_end_date'], Timezone::SERVER_TIMEZONE) : null;
			$this->end_date_enabled = !empty($properties['publishing_end_date']);
		}

		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$this->set_sources(!empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : []);
		}

		foreach ($this->additional_attributes_list as $id => $attribute)
		{
			if (isset($properties[$id]))
			{
				$this->set_additional_property($id, $properties[$id], $attribute['is_url'], $attribute['is_array']);
			}
		}
	}

	public function set_additional_property(string $id, string $value, bool $is_url = false, bool $is_array = false): void
	{
		if ($is_url)
        {
			$this->additional_attributes_values[$id] = new Url($value);
        }
		elseif ($is_array)
        {
			$this->additional_attributes_values[$id] = TextHelper::unserialize($value);
        }
		else
        {
			$this->additional_attributes_values[$id] = $value;
        }
	}

	protected function kernel_default_properties(): void {}

	protected function default_properties(): void {}

	public function init_default_properties(int $id_category = Category::ROOT_CATEGORY): void
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->update_date = new Date();
		$this->published = self::PUBLISHED;
		$this->sources = [];
		$this->publishing_start_date = new Date();
		$this->publishing_end_date = new Date();
		$this->kernel_default_properties();
		$this->default_properties();
	}

	public function clean_publishing_start_and_end_date(): void
	{
		$this->publishing_start_date = null;
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}

	public function clean_publishing_end_date(): void
	{
		$this->publishing_end_date = null;
		$this->end_date_enabled = false;
	}

	public function get_sorting_fields_list(): array
	{
		$lang = LangLoader::get_all_langs();

		$fields_list = array_merge(
			[
				self::get_title_label() => ['database_field' => self::get_title_label(), 'label' => $lang['common.title'], 'icon' => 'fa fa-sort-alpha-up'],
				'author'                => ['database_field' => 'display_name', 'label' => $lang['common.author'], 'icon' => 'far fa-user'],
				'date'                  => ['database_field' => 'creation_date', 'label' => $lang['common.creation.date'], 'icon' => 'far fa-calendar-alt'],
				'update_date'           => ['database_field' => 'update_date', 'label' => $lang['common.last.update'], 'icon' => 'far fa-calendar-plus']
			],
			$this->get_kernel_additional_sorting_fields(),
			$this->get_additional_sorting_fields()
		);

		if (self::$module && self::$module->get_configuration()->feature_is_enabled('comments') && CommentsConfig::load()->module_comments_is_enabled(self::$module_id))
        {
			$fields_list['comments'] = ['database_field' => 'comments_number', 'label' => $lang['common.sort.by.comments.number'], 'icon' => 'far fa-comments'];
        }
		if (self::$module && self::$module->get_configuration()->feature_is_enabled('notation') && ContentManagementConfig::load()->module_notation_is_enabled(self::$module_id))
        {
			$fields_list['notes'] = ['database_field' => 'average_notes', 'label' => $lang['common.sort.by.best.note'], 'icon' => 'far fa-star'];
        }

		return $fields_list;
	}

	protected function get_kernel_additional_sorting_fields(): array
	{
		return [];
	}

	protected function get_additional_sorting_fields(): array
	{
		return [];
	}

	public function get_sorting_field_options(): array
	{
		$sort_options = [];

		foreach ($this->get_sorting_fields_list() as $id => $parameters)
		{
			$sort_options[$id] = new FormFieldSelectChoiceOption($parameters['label'], $id, ['data_option_icon' => $parameters['icon']]);
		}

		return $sort_options;
	}

	public static function get_sorting_mode_options(): array
	{
		$lang = LangLoader::get_all_langs();
		return [
			new FormFieldSelectChoiceOption($lang['common.sort.asc'], TextHelper::strtolower(self::ASC), ['data_option_icon' => 'fa fa-arrow-up']),
			new FormFieldSelectChoiceOption($lang['common.sort.desc'], TextHelper::strtolower(self::DESC), ['data_option_icon' => 'fa fa-arrow-down'])
		];
	}

	public function get_global_template_vars(): array
	{
		return [
			'C_DISPLAY_SUB_CATEGORIES' => $this->sub_categories_displayed
		];
	}

	public function get_item_url(): string
	{
		return self::$module->get_configuration()->has_categories() ? ItemsUrlBuilder::display($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title(), self::$module->get_id())->rel() : ItemsUrlBuilder::display_item($this->get_id(), $this->get_rewrited_title(), self::$module->get_id())->rel();
	}

	public function get_template_vars(): array
	{
		$categories_template_vars = $comments_template_vars = $notation_template_vars = $newcontent_template_vars = $sources_template_vars = $keywords_template_vars = [];

		if (self::$module->get_configuration()->has_categories())
		{
			$category = $this->get_category();

			$categories_template_vars = [
				'C_HAS_CATEGORY'       => true,
				'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
				'CATEGORY_ID'          => $category->get_id(),
				'CATEGORY_NAME'        => $category->get_name(),
				'CATEGORY_DESCRIPTION' => method_exists($category, 'get_description') ? $category->get_description() : '',
				'U_CATEGORY'           => CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), self::$module_id)->rel(),
				'U_CATEGORY_THUMBNAIL' => method_exists($category, 'get_thumbnail') ? $category->get_thumbnail()->rel() : '',
				'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), self::$module_id)->rel()
			];
		}

		if (self::$module->get_configuration()->feature_is_enabled('comments'))
		{
			$comments_number = CommentsService::get_comments_number(self::$module_id, $this->get_id());

			$comments_template_vars = [
				'C_SEVERAL_COMMENTS' => $comments_number > 1,
				'COMMENTS_LABEL'     => CommentsService::get_number_and_lang_comments(self::$module_id, $this->get_id()),
				'COMMENTS_NUMBER'    => $comments_number,
				'U_COMMENTS'         => self::$module->get_configuration()->has_categories() ? ItemsUrlBuilder::display_comments($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel() : ItemsUrlBuilder::display_item_comments($this->get_id(), $this->get_rewrited_title())->rel(),
			];
		}

		if (self::$module->get_configuration()->feature_is_enabled('notation'))
		{
			$notation_template_vars = [
				'STATIC_NOTATION' => NotationService::display_static_image($this->get_notation())
			];
		}

		if (self::$module->get_configuration()->feature_is_enabled('newcontent'))
		{
			$newcontent_template_vars = [
				'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date(self::$module_id, $this->publishing_start_date != null ? $this->publishing_start_date->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_published()
			];
		}

		if (self::$module->get_configuration()->feature_is_enabled('keywords'))
		{
			$keywords_template_vars = [
				'C_KEYWORDS' => $this->get_keywords()
			];
		}

		if (self::$module->get_configuration()->feature_is_enabled('sources'))
		{
			$sources_template_vars = [
				'C_SOURCES' => $this->get_sources()
			];
		}

		$content            = FormatingHelper::second_parse($this->content);
		$rich_content       = HooksService::execute_hook_display_action(self::$module->get_id(), $content, $this->get_properties());
		$author             = $this->get_author_user();
		$author_group_color = User::get_group_color($author->get_groups(), $author->get_level());

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->update_date, 'update_date'),
			Date::get_array_tpl_vars($this->publishing_start_date, 'deffered_publishing_start_date'),
			$categories_template_vars,
			$comments_template_vars,
			$notation_template_vars,
			$newcontent_template_vars,
			$keywords_template_vars,
			$sources_template_vars,
			[
                // Conditions
                'C_CONTROLS'            => $this->is_authorized_to_edit() || $this->is_authorized_to_duplicate() || $this->is_authorized_to_delete(),
                'C_DUPLICATE'           => $this->is_authorized_to_duplicate(),
                'C_EDIT'                => $this->is_authorized_to_edit(),
                'C_DELETE'              => $this->is_authorized_to_delete(),
                'C_AUTHOR_EXISTS'       => $author->get_id() !== User::VISITOR_LEVEL,
                'C_AUTHOR_GROUP_COLOR'  => !empty($author_group_color),
                'C_HAS_UPDATE_DATE'     => $this->has_update_date(),
                'C_PUBLISHED'           => $this->is_published(),
                'C_DEFFERED_PUBLISHING' => $this->published == self::DEFERRED_PUBLICATION,

                // Item parameters
                'ID'                                              => $this->get_id(),
                TextHelper::strtoupper(self::get_title_label())   => $this->get_title(),
                TextHelper::strtoupper(self::get_content_label()) => $this->content_field_enabled ? $rich_content : '',
                'AUTHOR_DISPLAY_NAME'                             => $author->get_display_name(),
                'AUTHOR_LEVEL_CLASS'                              => UserService::get_level_class($author->get_level()),
                'AUTHOR_GROUP_COLOR'                              => $author_group_color,
                'STATUS'                                          => $this->get_status(),

                // Links
                'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($author->get_id())->rel(),
                'U_AUTHOR_CONTRIB' => ItemsUrlBuilder::display_member_items($author->get_id())->rel(),
                'U_ITEM'           => $this->get_item_url(),
                'U_DUPLICATE'      => ItemsUrlBuilder::duplicate($this->id, self::$module->get_id())->rel(),
                'U_EDIT'           => ItemsUrlBuilder::edit($this->id, self::$module->get_id())->rel(),
                'U_DELETE'         => ItemsUrlBuilder::delete($this->id, self::$module->get_id())->rel(),
                'U_SYNDICATION'    => SyndicationUrlBuilder::rss(self::$module_id, $this->id_category)->rel()
			],
			$this->get_kernel_additional_template_vars(),
			$this->get_additional_template_vars()
		);
	}

	protected function get_kernel_additional_template_vars(): array
	{
		return [];
	}

	protected function get_additional_template_vars(): array
	{
		return [];
	}

	public function get_additional_content_template(): Template
	{
		return new StringTemplate('');
	}

	public function get_template_keyword_vars(Keyword $keyword): array
	{
		return [
			'NAME' => $keyword->get_name(),
			'URL' => ItemsUrlBuilder::display_tag($keyword->get_rewrited_name(), self::$module_id)->rel()
		];
	}

	public function get_template_source_vars(mixed $source_name): mixed
	{
		$vars = [];
		$sources = $this->get_sources();

		if (isset($sources[$source_name]))
		{
			$vars = [
				'C_SEPARATOR' => array_search($source_name, array_keys($sources)) < count($sources) - 1,
				'NAME'        => $source_name,
				'URL'         => $sources[$source_name]
			];
		}

		return $vars;
	}

	public static function create_items_table(string $module_id)
	{
		$module = new Module($module_id, true);

		$class_name = get_called_class();
		$object = new $class_name($module_id);

		$kernel_additional_fields = $kernel_additional_indexes = [];

		if ($object->content_field_enabled())
		{
			$content_field = [$class_name::get_content_label() => ['type' => 'text', 'length' => 16777215]];
			$content_option = ['content' => ['type' => 'fulltext', 'fields' => $class_name::get_content_label()]];
		}
		else
        {
			$content_field = $content_option = [];
        }

		if ($module->get_configuration()->has_categories())
		{
			$kernel_additional_fields['id_category'] = ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0];
			$kernel_additional_indexes['id_category'] = ['type' => 'key', 'fields' => 'id_category'];
		}

		if ($module->get_configuration()->feature_is_enabled('deferred_publication'))
		{
			$kernel_additional_fields['publishing_start_date'] = ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0];
			$kernel_additional_fields['publishing_end_date'] = ['type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0];
		}

		if ($module->get_configuration()->feature_is_enabled('sources'))
		{
			$kernel_additional_fields['sources'] = ['type' => 'text', 'length' => 65000];
		}

		$fields = array_merge([
			'id'                                         => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'autoincrement' => true],
			$class_name::get_title_label()               => ['type' => 'string',  'length' => 255, 'notnull' => 1, 'default' => "''"],
			'rewrited_' . $class_name::get_title_label() => ['type' => 'string',  'length' => 255, 'default' => "''"]
		],
		$content_field,
		[
			'author_user_id'                             => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
			'creation_date'                              => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
			'update_date'                                => ['type' => 'integer', 'length' => 11,  'notnull' => 1, 'default' => 0],
			'published'                                  => ['type' => 'integer', 'length' => 1,   'notnull' => 1, 'default' => 0]
		], $kernel_additional_fields, $object->get_additional_attributes_items_table_fields());

		$options = [
			'primary' => ['id'],
			'indexes' => array_merge([
					'title'   => ['type' => 'fulltext', 'fields' => $class_name::get_title_label()]
				], $content_option, $kernel_additional_indexes, $object->get_additional_attributes_items_table_options()
			)
		];

		PersistenceContext::get_dbms_utils()->create_table($module->get_configuration()->get_items_table_name(), $fields, $options);
	}
}
?>
