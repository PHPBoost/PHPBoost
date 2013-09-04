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
	
	private $progress;
	private $assigned_to_id;
	private $assigned_user;
	
	const NEW_BUG = 'new';
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
	
	public function set_submit_date($submit_date)
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
	
	public function set_progress($progress)
	{
		$this->progress = $progress;
	}
	
	public function get_progress()
	{
		return $this->progress;
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
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'title' => $this->get_title(),
			'contents' => $this->get_contents(),
			'author_id' => $this->get_author_user()->get_id(),
			'submit_date' => $this->get_submit_date() !== null ? $this->get_submit_date() : 0,
			'fix_date' => $this->get_fix_date() !== null ? $this->get_fix_date() : 0,
			'status' => $this->get_status(),
			'type' => $this->get_type(),
			'category' => $this->get_category(),
			'severity' => $this->get_severity(),
			'priority' => $this->get_priority(),
			'reproductible' => $this->get_reproductible(),
			'reproduction_method' => $this->get_reproduction_method(),
			'detected_in' => $this->get_detected_in(),
			'fixed_in' => $this->get_fixed_in(),
			'progress' => $this->get_progress(),
			'assigned_to_id' => $this->get_assigned_to_id(),
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_title($properties['title']);
		$this->set_contents($properties['contents']);
		$this->set_submit_date(!empty($properties['submit_date']) ? $properties['submit_date'] : 0);
		$this->set_fix_date(!empty($properties['fix_date']) ? $properties['fix_date'] : 0);
		$this->set_status($properties['status']);
		$this->set_type($properties['type']);
		$this->set_category($properties['category']);
		$this->set_severity($properties['severity']);
		$this->set_priority($properties['priority']);
		$this->set_reproductible($properties['reproductible']);
		$this->set_reproduction_method($properties['reproduction_method']);
		$this->set_detected_in($properties['detected_in']);
		$this->set_fixed_in($properties['fixed_in']);
		$this->set_progress($properties['progress']);
		$this->set_assigned_to_id($properties['assigned_to_id']);
		
		$user = new User();
		$user->set_properties($properties);
		$this->set_author_user($user);
	}
	
	public function init_default_properties()
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$config = BugtrackerConfig::load();
		$status_list = $config->get_status_list();
		
		$this->submit_date = $now->get_timestamp();
		$this->status = Bug::NEW_BUG;
		$this->author_user = AppContext::get_current_user();
		$this->progress = $status_list[Bug::NEW_BUG];
		$this->fixed_in = 0;
		$this->assigned_to_id = 0;
	}
	
	public function clean_fix_date()
	{
		$this->fix_date = null;
	}
}
?>
