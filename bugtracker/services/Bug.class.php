<?php
/*##################################################
 *                        Bug.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class Bug
{
	private $id;
	private $title;
	private $rewrited_title;
	private $contents;
	
	private $author_user;
	
	private $submit_date;
	private $fix_date;
	
	private $status;
	private $type;
	private $category;
	private $severity;
	private $priority;
	
	private $reproductible;
	private $reproduction_method;
	
	private $detected_in;
	private $fixed_in;
	
	private $assigned_to_id;
	private $assigned_user;
	
	const NEW_BUG = 'new';
	const PENDING = 'pending';
	const ASSIGNED = 'assigned';
	const IN_PROGRESS = 'in_progress';
	const REJECTED = 'rejected';
	const REOPEN = 'reopen';
	const FIXED = 'fixed';
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
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
	
	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}
	
	public function get_author_user()
	{
		return $this->author_user;
	}
	
	public function set_submit_date(Date $submit_date)
	{
		$this->submit_date = $submit_date;
	}
	
	public function get_submit_date()
	{
		return $this->submit_date;
	}
	
	public function set_fix_date($fix_date)
	{
		$this->fix_date = $fix_date;
	}
	
	public function get_fix_date()
	{
		return $this->fix_date;
	}
	
	public function set_status($status)
	{
		$this->status = $status;
	}
	
	public function get_status()
	{
		return $this->status;
	}
	
	public function is_new()
	{
		return $this->status == Bug::NEW_BUG;
	}
	
	public function is_pending()
	{
		return $this->status == Bug::PENDING;
	}
	
	public function is_assigned()
	{
		return $this->status == Bug::ASSIGNED;
	}
	
	public function is_in_progress()
	{
		return $this->status == Bug::IN_PROGRESS;
	}
	
	public function is_rejected()
	{
		return $this->status == Bug::REJECTED;
	}
	
	public function is_reopen()
	{
		return $this->status == Bug::REOPEN;
	}
	
	public function is_fixed()
	{
		return $this->status == Bug::FIXED;
	}
	
	public function set_type($type)
	{
		$this->type = $type;
	}
	
	public function get_type()
	{
		return $this->type;
	}
	
	public function set_category($category)
	{
		$this->category = $category;
	}
	
	public function get_category()
	{
		return $this->category;
	}
	
	public function set_severity($severity)
	{
		$this->severity = $severity;
	}
	
	public function get_severity()
	{
		return $this->severity;
	}
	
	public function set_priority($priority)
	{
		$this->priority = $priority;
	}
	
	public function get_priority()
	{
		return $this->priority;
	}
	
	public function set_reproductible($reproductible)
	{
		$this->reproductible = $reproductible;
	}
	
	public function get_reproductible()
	{
		return $this->reproductible;
	}
	
	public function is_reproductible()
	{
		return $this->reproductible;
	}
	
	public function set_reproduction_method($reproduction_method)
	{
		$this->reproduction_method = $reproduction_method;
	}
	
	public function get_reproduction_method()
	{
		return $this->reproduction_method;
	}
	
	public function set_detected_in($detected_in)
	{
		$this->detected_in = $detected_in;
	}
	
	public function get_detected_in()
	{
		return $this->detected_in;
	}
	
	public function set_fixed_in($fixed_in)
	{
		$this->fixed_in = $fixed_in;
	}
	
	public function get_fixed_in()
	{
		return $this->fixed_in;
	}
	
	public function set_assigned_to_id($assigned_to_id)
	{
		$this->assigned_to_id = $assigned_to_id;
	}
	
	public function get_assigned_to_id()
	{
		return $this->assigned_to_id;
	}
	
	public function get_assigned_user()
	{
		return $this->assigned_user;
	}
	
	public function is_authorized_to_add()
	{
		return BugtrackerAuthorizationsService::check_authorizations()->write();
	}
	
	public function is_authorized_to_edit()
	{
		return BugtrackerAuthorizationsService::check_authorizations()->moderation() || (BugtrackerAuthorizationsService::check_authorizations()->write() && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id());
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'title' => $this->get_title(),
			'contents' => $this->get_contents(),
			'author_id' => $this->get_author_user()->get_id(),
			'submit_date' => $this->get_submit_date() ? $this->get_submit_date()->get_timestamp() : '',
			'fix_date' => $this->get_fix_date() ? $this->get_fix_date()->get_timestamp() : '',
			'status' => $this->get_status(),
			'type' => $this->get_type(),
			'category' => $this->get_category(),
			'severity' => $this->get_severity(),
			'priority' => $this->get_priority(),
			'reproductible' => $this->get_reproductible(),
			'reproduction_method' => $this->get_reproduction_method(),
			'detected_in' => $this->get_detected_in(),
			'fixed_in' => $this->get_fixed_in(),
			'assigned_to_id' => $this->get_assigned_to_id(),
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->title = $properties['title'];
		$this->rewrited_title = Url::encode_rewrite($properties['title']);
		$this->contents = $properties['contents'];
		$this->submit_date = !empty($properties['submit_date']) ? new Date( $properties['submit_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->fix_date = !empty($properties['fix_date']) ? new Date($properties['fix_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->status = $properties['status'];
		$this->type = $properties['type'];
		$this->category = $properties['category'];
		$this->severity = $properties['severity'];
		$this->priority = $properties['priority'];
		$this->reproductible = $properties['reproductible'];
		$this->reproduction_method = $properties['reproduction_method'];
		$this->detected_in = $properties['detected_in'];
		$this->fixed_in = $properties['fixed_in'];
		$this->assigned_to_id = $properties['assigned_to_id'];
		
		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();
		
		$this->set_author_user($user);
	}
	
	public function init_default_properties()
	{
		$config = BugtrackerConfig::load();
		
		$this->submit_date = new Date();
		$this->fix_date = new Date();
		$this->status = Bug::NEW_BUG;
		$this->author_user = AppContext::get_current_user();
		$this->contents = $config->get_contents_value();
		$this->type = $config->get_default_type();
		$this->category = $config->get_default_category();
		$this->severity = $config->get_default_severity();
		$this->priority = $config->get_default_priority();
		$this->detected_in = $config->get_default_version();
		$this->fixed_in = 0;
		$this->assigned_to_id = 0;
		$this->reproductible = true;
	}
	
	public function clean_fix_date()
	{
		$this->fix_date = null;
	}
	
	public function get_array_tpl_vars()
	{
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$priorities = $config->get_priorities();
		$versions = $config->get_versions();
		$status_list = $config->get_status_list();
		
		$lang = LangLoader::get('common', 'bugtracker');
		
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$number_comments = CommentsService::get_number_comments('bugtracker', $this->id);
		
		return array(
			'C_PROGRESS' => $config->is_progress_bar_displayed(),
			'C_FIX_DATE' => $this->fix_date != null,
			'C_FIXED_IN' => $this->detected_in,
			'C_FIXED' => $this->is_fixed(),
			'C_PENDING' => $this->is_pending(),
			'C_REPRODUCTIBLE' => $this->is_reproductible(),
			'C_REPRODUCTION_METHOD' => $this->reproduction_method,
			'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'C_MORE_THAN_ONE_COMMENT'=> $number_comments > 1,
			
			//Bug
			'ID' => $this->id,
			'TITLE' => $this->title,
			'CONTENTS' => FormatingHelper::second_parse($this->contents),
			'SUBMIT_DATE_SHORT' => $this->submit_date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'SUBMIT_DATE' => $this->submit_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'FIX_DATE_SHORT' => $this->fix_date !== null ? $this->fix_date->format(Date::FORMAT_DAY_MONTH_YEAR) : '',
			'FIX_DATE' => $this->fix_date !== null ? $this->fix_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) : '',
			'TYPE' => (isset($types[$this->type])) ? stripslashes($types[$this->type]) : $lang['notice.none'],
			'CATEGORY' => (isset($categories[$this->category])) ? stripslashes($categories[$this->category]) : $lang['notice.none_e'],
			'SEVERITY' => (isset($severities[$this->severity])) ? stripslashes($severities[$this->severity]['name']) : $lang['notice.none'],
			'PRIORITY' => (isset($priorities[$this->priority])) ? stripslashes($priorities[$this->priority]) : $lang['notice.none_e'],
			'DETECTED_IN' => (isset($versions[$this->detected_in])) ? stripslashes($versions[$this->detected_in]['name']) : $lang['notice.not_defined'],
			'FIXED_IN' => (isset($versions[$this->fixed_in])) ? stripslashes($versions[$this->fixed_in]['name']) : $lang['notice.not_defined'],
			'PROGRESS' => $status_list[$this->status],
			'STATUS' => LangLoader::get_message('status.' . $this->status, 'common', 'bugtracker'),
			'REPRODUCTION_METHOD' => FormatingHelper::second_parse($this->reproduction_method),
			'AUTHOR' => $user->get_display_name(),
			'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'AUTHOR_GROUP_COLOR' => $user_group_color,
			'NUMBER_COMMENTS' => $number_comments,
			
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
			'U_LINK' => BugtrackerUrlBuilder::detail($this->id . '-' . $this->rewrited_title)->rel(),
			'U_HISTORY' => BugtrackerUrlBuilder::history($this->id)->rel(),
			'U_COMMENTS' => BugtrackerUrlBuilder::detail($this->id . '-' . $this->rewrited_title . '#comments_list')->rel()
		);
	}
}
?>
