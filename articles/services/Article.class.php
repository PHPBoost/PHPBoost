<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Article
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $description;
	private $contents;
	private $picture_url;
	private $number_view;

	private $author_custom_name;
	private $author_custom_name_enabled;
	private $author_name_displayed;
	private $author_user;
	private $notation;

	private $published;
	private $publishing_start_date;
	private $publishing_end_date;
	private $date_created;
	private $end_date_enabled;
	private $date_updated;

	private $sources;
	private $keywords;

	const SORT_ALPHABETIC = 'title';
	const SORT_DATE = 'date_created';
	const SORT_AUTHOR = 'display_name';
	const SORT_NUMBER_VIEWS = 'number_view';
	const SORT_NOTATION = 'average_notes';
	const SORT_NUMBER_COMMENTS = 'number_comments';

	const SORT_FIELDS_URL_VALUES = array(
		self::SORT_ALPHABETIC => 'title',
		self::SORT_DATE => 'date',
		self::SORT_AUTHOR => 'author',
		self::SORT_NUMBER_VIEWS => 'views',
		self::SORT_NOTATION => 'notes',
		self::SORT_NUMBER_COMMENTS => 'comments'
	);

	const ASC = 'ASC';
	const DESC = 'DESC';

	const NOT_PUBLISHED = 0;
	const PUBLISHED_NOW = 1;
	const PUBLISHED_DATE = 2;

	const AUTHOR_NAME_NOTDISPLAYED = 0;
	const AUTHOR_NAME_DISPLAYED = 1;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function rewrited_title_is_personalized()
	{
		return $this->rewrited_title != Url::encode_rewrite($this->title);
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function get_description_enabled()
	{
		return !empty($this->description);
	}

	public function get_real_description()
	{
		if ($this->get_description_enabled())
		{
			return FormatingHelper::second_parse($this->description);
		}
		else
		{
			$clean_contents = preg_split('`\[page\].+\[/page\](.*)`usU', FormatingHelper::second_parse($this->contents), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			return TextHelper::cut_string(@strip_tags($clean_contents[0], '<br><br/>'), (int)ArticlesConfig::load()->get_number_character_to_cut());
		}
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}

	public function get_picture()
	{
		if (!$this->picture_url instanceof Url)
			return $this->get_default_thumbnail();

		return $this->picture_url;
	}

	public function has_picture()
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	public function get_default_thumbnail()
	{
		$file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_item_thumbnail.png');
		if ($file->exists())
			return new Url('/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_item_thumbnail.png');
		else
			return new Url('/templates/default/images/default_item_thumbnail.png');
	}

	public function set_number_view($number_view)
	{
		$this->number_view = $number_view;
	}

	public function get_number_view()
	{
		return $this->number_view;
	}

	public function get_author_name_displayed()
	{
		return $this->author_name_displayed;
	}

	public function set_author_name_displayed($displayed)
	{
		$this->author_name_displayed = $displayed;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function get_author_custom_name()
	{
		return $this->author_custom_name;
	}

	public function set_author_custom_name($author_custom_name)
	{
		$this->author_custom_name = $author_custom_name;
	}

	public function is_author_custom_name_enabled()
	{
		return $this->author_custom_name_enabled;
	}

	public function set_notation(Notation $notation)
	{
		$this->notation = $notation;
	}

	public function get_notation()
	{
		return $this->notation;
	}

	public function set_publishing_state($published)
	{
		$this->published = $published;
	}

	public function get_publishing_state()
	{
		return $this->published;
	}

	public function is_published()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publishing_state() == self::PUBLISHED_NOW || ($this->get_publishing_state() == self::PUBLISHED_DATE && $this->get_publishing_start_date()->is_anterior_to($now) && ($this->end_date_enabled ? $this->get_publishing_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->published) {
			case self::PUBLISHED_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::PUBLISHED_DATE:
				return LangLoader::get_message('status.approved.date', 'common');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('status.approved.not', 'common');
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

	public function set_date_created(Date $date_created)
	{
		$this->date_created = $date_created;
	}

	public function get_date_created()
	{
		return $this->date_created;
	}

	public function get_date_updated()
	{
		return $this->date_updated;
	}

	public function set_date_updated(Date $date_updated)
	{
		$this->date_updated = $date_updated;
	}

	public function add_source($source)
	{
		$this->sources[] = $source;
	}

	public function set_sources($sources)
	{
		$this->sources = $sources;
	}

	public function get_sources()
	{
		return $this->sources;
	}

	public function get_keywords()
	{
		if ($this->keywords === null)
		{
			$this->keywords = KeywordsService::get_keywords_manager()->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id'                    => $this->get_id(),
			'id_category'           => $this->get_id_category(),
			'title'                 => $this->get_title(),
			'rewrited_title'        => $this->get_rewrited_title(),
			'description'           => $this->get_description(),
			'contents'              => $this->get_contents(),
			'picture_url'           => $this->get_picture()->relative(),
			'number_view'           => $this->get_number_view(),
			'author_custom_name' 	=> $this->get_author_custom_name(),
			'author_user_id'        => $this->get_author_user()->get_id(),
			'author_name_displayed' => $this->get_author_name_displayed(),
			'published'             => $this->get_publishing_state(),
			'publishing_start_date' => $this->get_publishing_start_date() !== null ? $this->get_publishing_start_date()->get_timestamp() : 0,
			'publishing_end_date'   => $this->get_publishing_end_date() !== null ? $this->get_publishing_end_date()->get_timestamp() : 0,
			'date_created'          => $this->get_date_created()->get_timestamp(),
			'date_updated'          => $this->get_date_updated() !== null ? $this->get_date_updated()->get_timestamp() : 0,
			'sources'               => TextHelper::serialize($this->get_sources())
		);
	}

	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_category($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_rewrited_title($properties['rewrited_title']);
		$this->set_description($properties['description']);
		$this->set_contents($properties['contents']);
		$this->set_picture(new Url($properties['picture_url']));
		$this->set_number_view($properties['number_view']);
		$this->set_author_name_displayed($properties['author_name_displayed']);
		$this->set_publishing_state($properties['published']);
		$this->publishing_start_date = !empty($properties['publishing_start_date']) ? new Date($properties['publishing_start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->publishing_end_date = !empty($properties['publishing_end_date']) ? new Date($properties['publishing_end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date_enabled = !empty($properties['publishing_end_date']);
		$this->set_date_created(new Date($properties['date_created'], Timezone::SERVER_TIMEZONE));
		$this->date_updated = !empty($properties['date_updated']) ? new Date($properties['date_updated'], Timezone::SERVER_TIMEZONE) : null;
		$this->set_sources(!empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : array());

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
		$this->author_custom_name_enabled = !empty($properties['author_custom_name']);

		$notation = new Notation();
		$notation->set_module_name('articles');
		$notation->set_id_in_module($properties['id']);
		$notation->set_number_notes($properties['number_notes']);
		$notation->set_average_notes($properties['average_notes']);
		$notation->set_user_already_noted(!empty($properties['note']));
		$this->notation = $notation;
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->contents = ArticlesConfig::load()->get_default_contents();
		$this->author_name_displayed = self::AUTHOR_NAME_DISPLAYED;
		$this->author_user = AppContext::get_current_user();
		$this->published = self::PUBLISHED_NOW;
		$this->publishing_start_date = new Date();
		$this->publishing_end_date = new Date();
		$this->date_created = new Date();
		$this->sources = array();
		$this->picture_url = self::get_default_thumbnail();
		$this->number_view = 0;
		$this->author_custom_name = $this->author_user->get_display_name();
		$this->author_custom_name_enabled = false;
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

	public function get_array_tpl_vars()
	{
		$category         = $this->get_category();
		$contents	      = FormatingHelper::second_parse($this->contents);
		$description      = $this->get_real_description();
		$user             = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$sources          = $this->get_sources();
		$nbr_sources      = count($sources);

		return array_merge(
			Date::get_array_tpl_vars($this->date_created, 'date'),
			Date::get_array_tpl_vars($this->date_updated, 'date_updated'),
			Date::get_array_tpl_vars($this->publishing_start_date, 'publishing_start_date'),
			Date::get_array_tpl_vars($this->publishing_end_date, 'publishing_end_date'),
			array(
			// Conditions
			'C_CONTROLS'		   			  => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT'                          => $this->is_authorized_to_edit(),
			'C_DELETE'                        => $this->is_authorized_to_delete(),
			'C_HAS_PICTURE'                   => $this->has_picture(),
			'C_USER_GROUP_COLOR'              => !empty($user_group_color),
			'C_PUBLISHED'                     => $this->is_published(),
			'C_PUBLISHING_START_AND_END_DATE' => $this->publishing_start_date != null && $this->publishing_end_date != null,
			'C_PUBLISHING_START_DATE'         => $this->publishing_start_date != null,
			'C_PUBLISHING_END_DATE'           => $this->publishing_end_date != null,
			'C_DATE_UPDATED'                  => $this->date_updated != null,
			'C_AUTHOR_DISPLAYED'              => $this->get_author_name_displayed(),
			'C_AUTHOR_CUSTOM_NAME' 			  => $this->is_author_custom_name_enabled(),
			'C_READ_MORE'                     => !$this->get_description_enabled() && TextHelper::strlen($contents) > ArticlesConfig::load()->get_number_character_to_cut() && $description != @strip_tags($contents, '<br><br/>'),
			'C_SOURCES'                       => $nbr_sources > 0,
			'C_DIFFERED'                      => $this->published == self::PUBLISHED_DATE,
			'C_NEW_CONTENT'                   => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('articles', $this->publishing_start_date != null ? $this->publishing_start_date->get_timestamp() : $this->get_date_created()->get_timestamp()) && $this->is_published(),
			'C_ID_CARD' 					  => ContentManagementConfig::load()->module_id_card_is_enabled('articles') && $this->is_published(),

			// Articles
			'ID'                            => $this->get_id(),
			'TITLE'                         => $this->get_title(),
			'STATUS'                        => $this->get_status(),
			'L_COMMENTS'                    => CommentsService::get_number_and_lang_comments('articles', $this->get_id()),
			'COMMENTS_NUMBER'               => CommentsService::get_comments_number('articles', $this->get_id()),
			'NUMBER_VIEW'                   => $this->get_number_view(),
			'NOTE'                          => $this->get_notation()->get_number_notes() > 0 ? NotationService::display_static_image($this->get_notation()) : '&nbsp;',
			'AUTHOR_CUSTOM_NAME' 			=> $this->author_custom_name,
			'C_AUTHOR_EXIST'                => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO'                        => $user->get_display_name(),
			'DESCRIPTION'                   => $description,
			'USER_LEVEL_CLASS'              => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR'              => $user_group_color,
			'ID_CARD' 						=> IdcardService::display_idcard($user),

			// Categories
			'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE'       => $category->get_image()->rel(),
			'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? ArticlesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($category->get_id())->rel(),

			// Links
			'U_COMMENTS'       => ArticlesUrlBuilder::display_comments_article($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel(),
			'U_AUTHOR'         => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_CATEGORY'       => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_ARTICLE'        => ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $this->get_id(), $this->get_rewrited_title())->rel(),
			'U_PICTURE'        => $this->get_picture()->rel(),
			'U_EDIT_ARTICLE'   => ArticlesUrlBuilder::edit_article($this->id, AppContext::get_request()->get_getint('page', 1))->rel(),
			'U_DELETE_ARTICLE' => ArticlesUrlBuilder::delete_article($this->id)->rel(),
			'U_SYNDICATION'    => SyndicationUrlBuilder::rss('articles', $this->id_category)->rel()
			)
		);
	}

	public function get_array_tpl_source_vars($source_name)
	{
		$vars = array();
		$sources = $this->get_sources();

		if (isset($sources[$source_name]))
		{
			$vars = array(
				'C_SEPARATOR' => array_search($source_name, array_keys($sources)) < count($sources) - 1,
				'NAME' => $source_name,
				'URL' => $sources[$source_name]
			);
		}

		return $vars;
	}
}
?>
