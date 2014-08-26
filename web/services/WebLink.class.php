<?php
/*##################################################
 *                               WebLink.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class WebLink
{
	private $id;
	private $id_category;
	private $name;
	private $rewrited_name;
	private $url;
	private $contents;
	private $creation_date;
	private $approved;
	private $author_user;
	private $number_views;
	private $partner;
	private $partner_picture;
	
	private $notation;
	private $keywords;
	
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
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}
	
	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}
	
	public function get_url()
	{
		if (!$this->url instanceof Url)
			return new Url('');
		
		return $this->url;
	}
	
	public function set_url(Url $url)
	{
		$this->url = $url;
	}
	
	public function get_contents()
	{
		return $this->contents;
	}
	
	public function set_contents($contents)
	{
		$this->contents = $contents;
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
	
	public function get_number_views()
	{
		return $this->number_views;
	}
	
	public function set_number_views($number_views)
	{
		$this->number_views = $number_views;
	}
	
	public function is_partner()
	{
		return $this->partner;
	}
	
	public function set_partner($partner)
	{
		$this->partner = $partner;
	}
	
	public function get_partner_picture()
	{
		if (!$this->partner_picture instanceof Url)
			return new Url($this->partner_picture);
		
		return $this->partner_picture;
	}
	
	public function set_partner_picture(Url $partner_picture)
	{
		$this->partner_picture = $partner_picture;
	}
	
	public function has_partner_picture()
	{
		$picture = $this->partner_picture->rel();
		return !empty($picture);
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
			$this->keywords = WebService::get_keywords_manager()->get_keywords($this->id);
		}
		return $this->keywords;
	}
	
	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}
	
	public function is_authorized_to_add()
	{
		return WebAuthorizationsService::check_authorizations($this->id_category)->write() || WebAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}
	
	public function is_authorized_to_edit()
	{
		return WebAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((WebAuthorizationsService::check_authorizations($this->id_category)->write() || (WebAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->approved)) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function is_authorized_to_delete()
	{
		return WebAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((WebAuthorizationsService::check_authorizations($this->id_category)->write() || (WebAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->approved)) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'name' => TextHelper::htmlspecialchars($this->get_name()),
			'url' => TextHelper::htmlspecialchars($this->get_url()->absolute()),
			'contents' => $this->get_contents(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'approved' => $this->is_approved() ? 1 : 0,
			'author_user_id' => $this->get_author_user()->get_id(),
			'number_views' => $this->get_number_views(),
			'partner' => $this->is_partner() ? 1 : 0,
			'partner_picture' => TextHelper::htmlspecialchars($this->get_partner_picture()->relative())
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->name = $properties['name'];
		$this->rewrited_name = Url::encode_rewrite($properties['name']);
		$this->url = new Url($properties['url']);
		$this->contents = $properties['contents'];
		$this->creation_date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $properties['creation_date']);
		
		if ($properties['approved'])
			$this->approve();
		else
			$this->unapprove();
		
		$this->number_views = $properties['number_views'];
		$this->partner = (bool)$properties['partner'];
		$this->partner_picture = new Url($properties['partner_picture']);
		
		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();
		
		$this->set_author_user($user);
		
		$notation = new Notation();
		$notation->set_module_name('web');
		$notation->set_notation_scale(WebConfig::load()->get_notation_scale());
		$notation->set_id_in_module($properties['id']);
		$notation->set_number_notes($properties['number_notes']);
		$notation->set_average_notes($properties['average_notes']);
		$notation->set_user_already_noted(!empty($properties['note']));
		$this->notation = $notation;
	}
	
	public function init_default_properties()
	{
		$this->id_category = Category::ROOT_CATEGORY;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->url = new Url('');
		$this->partner_picture = new Url('');
		$this->number_views = 0;
		
		if (WebAuthorizationsService::check_authorizations()->write())
			$this->approve();
		else
			$this->unapprove();
	}
	
	public function get_array_tpl_vars()
	{
		$category = WebService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$number_comments = CommentsService::get_number_comments('web', $this->id);
		
		return array(
			'C_APPROVED' => $this->is_approved(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_IS_PARTNER' => $this->is_partner(),
			'C_HAS_PARTNER_PICTURE' => $this->has_partner_picture(),
			
			//Weblink
			'ID' => $this->id,
			'NAME' => $this->name,
			'URL' => $this->url->absolute(),
			'CONTENTS' => FormatingHelper::second_parse($this->contents),
			'DATE' => $this->creation_date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'DATE_ISO8601' => $this->creation_date->format(Date::FORMAT_ISO8601),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			'NUMBER_VIEWS' => $this->number_views,
			'L_VISITED_TIMES' => StringVars::replace_vars(LangLoader::get_message('visited_times', 'common', 'web'), array('number_visits' => $this->number_views)),
			'STATIC_NOTATION' => NotationService::display_static_image($this->get_notation()),
			'NOTATION' => NotationService::display_active_image($this->get_notation()),
			
			'C_COMMENTS' => !empty($number_comments),
			'L_COMMENTS' => CommentsService::get_lang_comments('web', $this->id),
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('web', $this->id),
			
			//Category
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image(),
			
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_VISIT' => WebUrlBuilder::visit($this->id)->rel(),
			'U_DEADLINK' => WebUrlBuilder::dead_link($this->id)->rel(),
			'U_CATEGORY' => WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => WebUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => WebUrlBuilder::delete($this->id)->rel(),
			'U_PARTNER_PICTURE' => $this->partner_picture->rel(),
			'U_COMMENTS' => WebUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel()
		);
	}
}
?>
