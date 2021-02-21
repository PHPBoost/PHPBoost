<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 22
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesItem extends Item
{
	protected $author_display;
	protected $author_custom_name;
	protected $author_custom_name_enabled;
	protected $views_number;

	protected $thumbnail_url;
	
	const THUMBNAIL_URL = '/templates/__default__/images/default_item_thumbnail.png';

	public function __construct()
	{
		parent::__construct('pages');
	}
	
	public function get_category()
	{
		return CategoriesService::get_categories_manager('pages')->get_categories_cache()->get_category($this->id_category);
	}

	public function get_author_display()
	{
		return $this->author_display;
	}

	public function set_author_display($author_display)
	{
		$this->author_display = $author_display;
	}

	public function is_author_displayed()
	{
		return $this->author_display;
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

	public function get_thumbnail()
	{
		if (!$this->thumbnail_url instanceof Url)
			return  new Url($this->thumbnail_url == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->thumbnail_url);

		return $this->thumbnail_url;
	}

	public function set_thumbnail($thumbnail)
	{
		$this->thumbnail_url = $thumbnail;
	}

	public function has_thumbnail()
	{
		$thumbnail = ($this->thumbnail_url instanceof Url) ? $this->thumbnail_url->rel() : $this->thumbnail_url;
		return !empty($thumbnail);
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->write() || (CategoriesAuthorizationsService::check_authorizations($this->get_id_category())->contribution() && !$this->is_published())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array_merge(parent::get_properties(), array(
			'author_display' => $this->get_author_display(),
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'views_number' => $this->get_views_number(),
			'thumbnail' => $this->get_thumbnail()->relative()
		));
	}

	public function set_properties(array $properties)
	{
		parent::set_properties($properties);
		$this->views_number = $properties['views_number'];
		$this->author_display = $properties['author_display'];
		$this->thumbnail_url = $properties['thumbnail'];
		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
		$this->author_custom_name_enabled = !empty($properties['author_custom_name']);
	}

	public function default_properties()
	{
		$this->content = PagesConfig::load()->get_default_content();
		$this->author_display = false;
		$this->views_number = 0;
		$this->thumbnail_url = FormFieldThumbnail::DEFAULT_VALUE;
		$this->author_custom_name = $this->author_user->get_display_name();
		$this->author_custom_name_enabled = false;
	}

	public function get_template_vars()
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$comments_number = CommentsService::get_comments_number('pages', $this->id);
		$config = PagesConfig::load();

		return array_merge(
			parent::get_template_vars(),
			array(
				// Conditions
	 			'C_VISIBLE'              => $this->is_published(),
				'C_CONTROLS'			 => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
				'C_EDIT'                 => $this->is_authorized_to_edit(),
				'C_DELETE'               => $this->is_authorized_to_delete(),
				'C_HAS_THUMBNAIL'        => $this->has_thumbnail(),
				'C_AUTHOR_DISPLAYED'     => $this->is_author_displayed(),
				'C_AUTHOR_CUSTOM_NAME'   => $this->is_author_custom_name_enabled(),
				'C_USER_GROUP_COLOR'     => !empty($user_group_color),
				'C_SEVERAL_VIEWS'		 => $this->get_views_number() > 1,
				'C_DIFFERED'             => $this->published == self::DEFERRED_PUBLICATION,
				
				// Item
				'STATUS'             => $this->get_status(),
				'AUTHOR_CUSTOM_NAME' => $this->author_custom_name,
				'C_AUTHOR_EXIST'     => $user->get_id() !== User::VISITOR_LEVEL,
				'PSEUDO'             => $user->get_display_name(),
				'USER_LEVEL_CLASS'   => UserService::get_level_class($user->get_level()),
				'USER_GROUP_COLOR'   => $user_group_color,
				'C_VIEWS_NUMBER'     => $config->get_views_number(),
				'VIEWS_NUMBER'       => $this->get_views_number(),

				'C_COMMENTS'      => !empty($comments_number),
				'L_COMMENTS'      => CommentsService::get_lang_comments('pages', $this->id),
				'COMMENTS_NUMBER' => $comments_number,

				// Links
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
				'U_ITEM'           => PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel(),
				'U_EDIT'           => PagesUrlBuilder::edit_item($this->id)->rel(),
				'U_DELETE'         => PagesUrlBuilder::delete_item($this->id)->rel(),
				'U_THUMBNAIL'      => $this->get_thumbnail()->rel(),
				'U_COMMENTS'       => PagesUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_title)->rel()
			)
		);
	}
}
?>
