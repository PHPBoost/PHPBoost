<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 4.0 - 2013 10 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class CalendarEventContent
{
	private $id;
	private $category_id;
	private $title;
	private $rewrited_title;
	private $contents;
	private $picture_url;

	private $location;
	private $map_displayed;

	private $cancelled;
	private $approved;

	private $creation_date;
	private $author_user;

	private $registration_authorized;
	private $max_registered_members;
	private $last_registration_date_enabled;
	private $last_registration_date;
	private $register_authorizations;

	private $repeat_number;
	private $repeat_type;

	const NEVER = 'never';
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';

	const DISPLAY_REGISTERED_USERS_AUTHORIZATION = 1;
	const REGISTER_AUTHORIZATION = 2;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_category_id($category_id)
	{
		$this->category_id = $category_id;
	}

	public function get_category_id()
	{
		return $this->category_id;
	}

	public function get_category()
	{
		return CategoriesService::get_categories_manager()->get_categories_cache()->get_category($this->category_id);
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

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function get_real_short_contents()
	{
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->contents), '<br><br/>'), (int)CalendarConfig::NUMBER_CARACTERS_BEFORE_CUT);
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

	public function set_location($location)
	{
		$this->location = $location;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function display_map()
	{
		$this->map_displayed = true;
	}

	public function hide_map()
	{
		$this->map_displayed = false;
	}

	public function is_map_displayed()
	{
		return $this->map_displayed;
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

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_author_user(User $author)
	{
		$this->author_user = $author;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function authorize_registration()
	{
		$this->registration_authorized = true;
	}

	public function unauthorize_registration()
	{
		$this->registration_authorized = false;
	}

	public function is_registration_authorized()
	{
		return $this->registration_authorized;
	}

	public function set_max_registered_members($max_registered_members)
	{
		$this->max_registered_members = $max_registered_members;
	}

	public function get_max_registered_members()
	{
		return $this->max_registered_members;
	}

	public function enable_last_registration_date()
	{
		$this->last_registration_date_enabled = true;
	}

	public function disable_last_registration_date()
	{
		$this->last_registration_date_enabled = false;
	}

	public function is_last_registration_date_enabled()
	{
		return $this->last_registration_date_enabled;
	}

	public function set_last_registration_date($last_registration_date)
	{
		$this->last_registration_date = $last_registration_date;
	}

	public function get_last_registration_date()
	{
		return $this->last_registration_date;
	}

	public function set_register_authorizations(array $authorizations)
	{
		$this->register_authorizations = $authorizations;
	}

	public function get_register_authorizations()
	{
		return $this->register_authorizations;
	}

	public function is_authorized_to_display_registered_users()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::DISPLAY_REGISTERED_USERS_AUTHORIZATION);
	}

	public function is_authorized_to_register()
	{
		return $this->registration_authorized && AppContext::get_current_user()->check_auth($this->register_authorizations, self::REGISTER_AUTHORIZATION);
	}

	public function set_repeat_number($number)
	{
		$this->repeat_number = $number;
	}

	public function get_repeat_number()
	{
		return $this->repeat_number;
	}

	public function set_repeat_type($type)
	{
		$this->repeat_type = $type;
	}

	public function get_repeat_type()
	{
		return $this->repeat_type;
	}

	public function is_repeatable()
	{
		return $this->repeat_type != self::NEVER;
	}

	public function cancel()
	{
		$this->cancelled = true;
	}

	public function uncancel()
	{
		$this->cancelled = false;
	}

	public function is_cancelled()
	{
		return $this->cancelled;
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_category_id(),
			'title' => $this->get_title(),
			'rewrited_title' => $this->get_rewrited_title(),
			'contents' => $this->get_contents(),
			'picture_url' => $this->get_picture()->relative(),
			'location' => $this->get_location(),
			'cancelled' => (int)$this->is_cancelled(),
			'approved' => (int)$this->is_approved(),
			'map_displayed' => (int)$this->is_map_displayed(),
			'creation_date' => (int)$this->get_creation_date()->get_timestamp(),
			'author_id' => $this->get_author_user()->get_id(),
			'registration_authorized' => (int)$this->is_registration_authorized(),
			'max_registered_members' => $this->get_max_registered_members(),
			'last_registration_date' => (int)($this->get_last_registration_date() !== null ? $this->get_last_registration_date()->get_timestamp() : ''),
			'register_authorizations' => TextHelper::serialize($this->get_register_authorizations()),
			'repeat_number' => $this->get_repeat_number(),
			'repeat_type' => $this->get_repeat_type()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->category_id = $properties['id_category'];
		$this->title = $properties['title'];
		$this->rewrited_title = $properties['rewrited_title'];
		$this->contents = $properties['contents'];
		$this->set_picture(new Url($properties['picture_url']));
		$this->location = $properties['location'];

		if ($properties['map_displayed'])
			$this->display_map();
		else
			$this->hide_map();

		if ($properties['approved'])
			$this->approve();
		else
			$this->unapprove();

		if ($properties['cancelled'])
			$this->cancel();
		else
			$this->uncancel();

		if ($properties['registration_authorized'])
			$this->authorize_registration();
		else
			$this->unauthorize_registration();

		$this->max_registered_members = $properties['max_registered_members'];
		$this->last_registration_date_enabled = !empty($properties['last_registration_date']);
		$this->last_registration_date = !empty($properties['last_registration_date']) ? new Date($properties['last_registration_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->register_authorizations = TextHelper::unserialize($properties['register_authorizations']);

		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);

		$this->repeat_number = $properties['repeat_number'];
		$this->repeat_type = $properties['repeat_type'];

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($category_id = Category::ROOT_CATEGORY)
	{
		$this->category_id = $category_id;
                $this->contents = CalendarConfig::load()->get_default_contents();
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->picture_url = self::get_default_thumbnail();

		$this->registration_authorized = false;
		$this->max_registered_members = 0;
		$this->last_registration_date_enabled = false;
		$this->register_authorizations = array('r0' => 3, 'r1' => 3);

		$this->hide_map();

		if (CategoriesAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();

		$this->repeat_number = 1;
		$this->repeat_type = self::NEVER;
		$this->cancelled = false;
	}
}
?>
