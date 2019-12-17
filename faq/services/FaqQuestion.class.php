<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FaqQuestion
{
	private $id;
	private $id_category;
	private $q_order;
	private $question;
	private $rewrited_question;
	private $answer;
	private $lang;

	private $creation_date;
	private $author_user;
	private $approved;

	const SORT_ALPHABETIC = 'question';
	const SORT_DATE = 'creation_date';

	const SORT_FIELDS_URL_VALUES = array(
		self::SORT_ALPHABETIC => 'question',
		self::SORT_DATE => 'date'
	);

	const ASC = 'ASC';
	const DESC = 'DESC';

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

	public function get_question()
	{
		return $this->question;
	}

	public function set_question($question)
	{
		$this->question = $question;
	}

	public function get_rewrited_question()
	{
		return $this->rewrited_question;
	}

	public function set_rewrited_question($rewrited_question)
	{
		$this->rewrited_question = $rewrited_question;
	}

	public function get_answer()
	{
		return $this->answer;
	}

	public function set_answer($answer)
	{
		$this->answer = $answer;
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
			'question' => $this->get_question(),
			'answer' => $this->get_answer(),
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
		$this->question = $properties['question'];
		$this->rewrited_question = Url::encode_rewrite($properties['question']);
		$this->answer = $properties['answer'];
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

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$this->lang = LangLoader::get('common', 'faq');

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
			'C_APPROVED' => $this->is_approved(),
			'C_ACTION_USER' => !$this->is_authorized_to_edit() && !$this->is_authorized_to_delete(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('faq', $this->get_creation_date()->get_timestamp()),

			//Question
			'ID' => $this->id,
			'QUESTION' => $this->question,
			'ANSWER' => FormatingHelper::second_parse($this->answer),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			'L_SHOW_ANSWER' => $this->lang['faq.message.show.answer'],
			'L_LINK_QUESTION' => $this->lang['faq.message.link.question'],

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? FaqUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('faq', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id)->rel(),
			'U_ABSOLUTE_LINK' => FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id)->absolute(),
			'U_CATEGORY' => FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => FaqUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => FaqUrlBuilder::delete($this->id)->rel()
		));
	}
}
?>
