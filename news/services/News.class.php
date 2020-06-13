<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 13
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class News
{
	private $id;
	private $id_category;
	private $title;
	private $rewrited_title;
	private $contents;
	private $summary;

	private $publication;
	private $start_date;
	private $end_date;
	private $end_date_enabled;
	private $top_list_enabled;

	private $creation_date;
	private $updated_date;
	private $author_user;
	private $views_number;
	private $author_custom_name;
	private $author_custom_name_enabled;

	private $thumbnail_url;
	private $sources;
	private $keywords;

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;
	const APPROVAL_DATE = 2;

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

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_summary($summary)
	{
		$this->summary = $summary;
	}

	public function get_summary()
	{
		return $this->summary;
	}

	public function get_real_summary()
	{
		if ($this->get_summary_enabled())
		{
			return FormatingHelper::second_parse($this->summary);
		}
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->contents), '<br><br/>'), (int)NewsConfig::load()->get_characters_number_to_cut());
	}

	public function get_summary_enabled()
	{
		return !empty($this->summary);
	}

	public function set_publication($publication)
	{
		$this->publication = $publication;
	}

	public function get_publication()
	{
		return $this->publication;
	}

	public function is_visible()
	{
		$now = new Date();
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_publication() == self::APPROVAL_NOW || ($this->get_publication() == self::APPROVAL_DATE && $this->get_start_date()->is_anterior_to($now) && ($this->end_date_enabled ? $this->get_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->publication) {
			case self::APPROVAL_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::APPROVAL_DATE:
				return LangLoader::get_message('status.approved.date', 'common');
			break;
			case self::NOT_APPROVAL:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
		}
	}

	public function set_start_date(Date $start_date)
	{
		$this->start_date = $start_date;
	}

	public function get_start_date()
	{
		return $this->start_date;
	}

	public function set_end_date(Date $end_date)
	{
		$this->end_date = $end_date;
		$this->end_date_enabled = true;
	}

	public function get_end_date()
	{
		return $this->end_date;
	}

	public function end_date_enabled()
	{
		return $this->end_date_enabled;
	}

	public function set_top_list_enabled($top_list_enabled)
	{
		$this->top_list_enabled = $top_list_enabled;
	}

	public function top_list_enabled()
	{
		return $this->top_list_enabled;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_updated_date(Date $updated_date)
	{
		$this->updated_date = $updated_date;
	}

	public function get_updated_date()
	{
		return $this->updated_date;
	}

	public function has_updated_date()
	{
		return $this->updated_date !== null;
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

	public function set_views_number($views_number)
	{
		$this->views_number = $views_number;
	}

	public function get_views_number()
	{
		return $this->views_number;
	}

	public function set_thumbnail(Url $thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function get_thumbnail()
	{
		if (!$this->thumbnail_url instanceof Url)
			return $this->get_default_thumbnail();

		return $this->thumbnail_url;
	}

	public function has_thumbnail()
	{
		$thumbnail = $this->thumbnail_url->rel();
		return !empty($thumbnail);
	}

	public function get_default_thumbnail()
	{
		$file = new File(PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_item_thumbnail.png');
		if ($file->exists())
			return new Url('/templates/' . AppContext::get_current_user()->get_theme() . '/images/default_item_thumbnail.png');
		else
			return new Url('/templates/default/images/default_item_thumbnail.png');
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
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'title' => $this->get_title(),
			'rewrited_title' => $this->get_rewrited_title(),
			'content' => $this->get_contents(),
			'summary' => $this->get_summary(),
			'publication' => $this->get_publication(),
			'start_date' => $this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : 0,
			'end_date' => $this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : 0,
			'top_list_enabled' => (int)$this->top_list_enabled(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'updated_date' => $this->get_updated_date() !== null ? $this->get_updated_date()->get_timestamp() : 0,
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'views_number' => $this->get_views_number(),
			'thumbnail_url' => $this->get_thumbnail()->relative(),
			'sources' => TextHelper::serialize($this->get_sources())
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->title = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->contents = $properties['content'];
		$this->summary = $properties['summary'];
		$this->views_number = $properties['views_number'];
		$this->publication = $properties['publication'];
		$this->start_date = !empty($properties['start_date']) ? new Date($properties['start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date($properties['end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date_enabled = !empty($properties['end_date']);
		$this->top_list_enabled = (bool)$properties['top_list_enabled'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->updated_date = !empty($properties['updated_date']) ? new Date($properties['updated_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->thumbnail_url = new Url($properties['thumbnail_url']);
		$this->sources = !empty($properties['sources']) ? TextHelper::unserialize($properties['sources']) : array();

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
		$this->author_custom_name_enabled = !empty($properties['author_custom_name']);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
        $this->contents = NewsConfig::load()->get_default_contents();
		$this->publication = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->start_date = new Date();
		$this->end_date = new Date();
		$this->creation_date = new Date();
		$this->sources = array();
		$this->thumbnail_url = self::get_default_thumbnail();
		$this->end_date_enabled = false;
		$this->views_number = 0;
		$this->author_custom_name = $this->author_user->get_display_name();
		$this->author_custom_name_enabled = false;
	}

	public function clean_start_and_end_date()
	{
		$this->start_date = null;
		$this->end_date = null;
		$this->end_date_enabled = false;
	}

	public function clean_end_date()
	{
		$this->end_date = null;
		$this->end_date_enabled = false;
	}

	public function get_array_tpl_vars()
	{
		$news_config = NewsConfig::load();
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$description = $this->get_real_summary();
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$comments_number = CommentsService::get_comments_number('news', $this->id);
		$sources = $this->get_sources();
		$nbr_sources = count($sources);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date,'date'),
			Date::get_array_tpl_vars($this->start_date,'differed_start_date'),
			array(
			'C_VISIBLE'            => $this->is_visible(),
			'C_CONTROLS'		   => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT'               => $this->is_authorized_to_edit(),
			'C_DELETE'             => $this->is_authorized_to_delete(),
			'C_HAS_THUMBNAIL'      => $this->has_thumbnail(),
			'C_USER_GROUP_COLOR'   => !empty($user_group_color),
			'C_AUTHOR_DISPLAYED'   => $news_config->get_author_displayed(),
			'C_AUTHOR_CUSTOM_NAME' => $this->is_author_custom_name_enabled(),
			'C_VIEWS_NUMBER'       => $news_config->get_views_number(),
			'C_SEVERAL_VIEWS'      => $this->get_views_number() > 1,
			'C_READ_MORE'          => !$this->get_summary_enabled() && TextHelper::strlen($contents) > $news_config->get_characters_number_to_cut() && $description != @strip_tags($contents, '<br><br/>'),
			'C_SOURCES'            => $nbr_sources > 0,
			'C_DIFFERED'           => $this->publication == self::APPROVAL_DATE,
			'C_TOP_LIST'           => $this->top_list_enabled(),
			'C_NEW_CONTENT'        => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('news', $this->get_start_date() != null ? $this->get_start_date()->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_visible(),
			'C_ID_CARD'            => ContentManagementConfig::load()->module_id_card_is_enabled('news') && $this->is_visible(),

			// News
			'ID'                 => $this->id,
			'TITLE'              => $this->title,
			'CONTENTS'           => $contents,
			'SUMMARY'       	 => $description,
			'STATUS'             => $this->get_status(),
			'AUTHOR_CUSTOM_NAME' => $this->author_custom_name,
			'C_AUTHOR_EXIST'     => $user->get_id() !== User::VISITOR_LEVEL,
			'AUTHOR_DISPLAY_NAME'             => $user->get_display_name(),
			'USER_LEVEL_CLASS'   => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR'   => $user_group_color,
			'ID_CARD'            => IdcardService::display_idcard($user),

			'C_COMMENTS'      => !empty($comments_number),
			'L_COMMENTS'      => CommentsService::get_lang_comments('news', $this->id),
			'COMMENTS_NUMBER' => $comments_number,
			'VIEWS_NUMBER'    => $this->get_views_number(),

			// Category
			'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? NewsUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION'    => SyndicationUrlBuilder::rss('news', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_ITEM'           => NewsUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel(),
			'U_CATEGORY'       => NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT'           => NewsUrlBuilder::edit_item($this->id)->rel(),
			'U_DELETE'         => NewsUrlBuilder::delete_item($this->id)->rel(),
			'U_THUMBNAIL'      => $this->get_thumbnail()->rel(),
			'U_COMMENTS'       => NewsUrlBuilder::display_item_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel()
		));
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
