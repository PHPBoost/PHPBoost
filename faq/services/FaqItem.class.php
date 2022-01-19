<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 19
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqItem
{
	private $id;
	private $id_category;
	private $q_order;
	private $title;
	private $rewrited_title;
	private $content;

	private $creation_date;
	private $author_user;
	private $approved;

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function get_q_order()
	{
		return $this->q_order;
	}

	public function set_q_order($q_order)
	{
		$this->q_order = $q_order;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function get_rewrited_title()
	{
		return $this->rewrited_title;
	}

	public function set_rewrited_title($rewrited_title)
	{
		$this->rewrited_title = $rewrited_title;
	}

	public function get_content()
	{
		return $this->content;
	}

	public function set_content($content)
	{
		$this->content = $content;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function approve()
	{
		$this->approved = true;
	}

	public function unapprove()
	{
		$this->approved = false;
	}

	public function is_approved()
	{
		return $this->approved;
	}

	public function is_authorized_to_add()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CategoriesAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CategoriesAuthorizationsService::check_authorizations($this->id_category)->write() || (CategoriesAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_approved())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'q_order' => $this->get_q_order(),
			'title' => $this->get_title(),
			'content' => $this->get_content(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'approved' => (int)$this->is_approved()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->q_order = $properties['q_order'];
		$this->title = $properties['title'];
		$this->rewrited_title = Url::encode_rewrite($properties['title']);
		$this->content = $properties['content'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->approved = (bool)$properties['approved'];

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();

		if (CategoriesAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();
	}

	public function get_item_url()
	{
		$category = $this->get_category();
		return FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id)->rel();
	}

	public function get_template_vars()
	{
		$category = $this->get_category();
		$content = FormatingHelper::second_parse($this->content);
		$rich_content = HooksService::execute_hook_display_action('faq', $content, $this->get_properties());
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
			// Conditions
			'C_APPROVED'         => $this->is_approved(),
			'C_ACTION_USER'      => !$this->is_authorized_to_edit() && !$this->is_authorized_to_delete(),
			'C_EDIT'             => $this->is_authorized_to_edit(),
			'C_DELETE'           => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_NEW_CONTENT'      => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('faq', $this->get_creation_date()->get_timestamp()),
			'C_AUTHOR_EXISTS'    => $user->get_id() !== User::VISITOR_LEVEL,

			// Item
			'ID'               => $this->id,
			'TITLE'            => $this->title,
			'CONTENT'          => $rich_content,
			'PSEUDO'           => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			'ITEM_DATE'		   => $this->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR),

			// Category
			'C_ROOT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID'          => $category->get_id(),
			'CATEGORY_NAME'        => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'U_CATEGORY_THUMBNAIL' => $category->get_thumbnail()->rel(),
			'U_EDIT_CATEGORY'      => $category->get_id() == Category::ROOT_CATEGORY ? FaqUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'faq')->rel(),

			'U_SYNDICATION'    => SyndicationUrlBuilder::rss('faq', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_ITEM'           => $this->get_item_url(),
			'U_ABSOLUTE_LINK'  => FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id)->absolute(),
			'U_CATEGORY'       => FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT'           => FaqUrlBuilder::edit($this->id)->rel(),
			'U_DELETE'         => FaqUrlBuilder::delete($this->id)->rel()
		));
	}
}
?>
