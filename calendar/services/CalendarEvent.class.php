<?php
/*##################################################
 *                        CalendarEvent.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class CalendarEvent
{
	private $id;
	private $id_cat;
	private $title;
	private $contents;
	
	private $location;
	
	private $start_date;
	private $end_date;
	
	private $author_id;
	
	private $registration_authorized;
	private $max_registred_members;
	
	private $repeat_number;
	private $repeat_type;
	
	const NEVER = 'never';
	const DAILY = 'daily';
	const DAILY_NOT_WEEKEND = 'daily_not_weekend';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_cat($id_cat)
	{
		$this->id_cat = $id_cat;
	}
	
	public function get_id_cat()
	{
		return $this->id_cat;
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
	
	public function set_location($location)
	{
		$this->location = $location;
	}
	
	public function get_location()
	{
		return $this->location;
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
	}
	
	public function get_end_date()
	{
		return $this->end_date;
	}
	
	public function set_author_id($author_id)
	{
		$this->author_id = $author_id;
	}
	
	public function get_author_id()
	{
		return $this->author_id;
	}
	
	public function set_registration_authorized($registration_authorized)
	{
		$this->registration_authorized = $registration_authorized;
	}
	
	public function get_registration_authorized()
	{
		return $this->registration_authorized;
	}
	
	public function is_registration_authorized()
	{
		return $this->get_registration_authorized() ? true : false;
	}
	
	public function set_max_registred_members($max_registred_members)
	{
		$this->max_registred_members = $max_registred_members;
	}
	
	public function get_max_registred_members()
	{
		return $this->max_registred_members;
	}
	
	public function set_repeat_number($repeat_number)
	{
		$this->repeat_number = $repeat_number;
	}
	
	public function get_repeat_number()
	{
		return $this->repeat_number;
	}
	
	public function set_repeat_type($repeat_type)
	{
		$this->repeat_type = $repeat_type;
	}
	
	public function get_repeat_type()
	{
		return $this->repeat_type;
	}
	
	public function is_repeatable()
	{
		return $this->get_repeat_type() != 'never' ? true : false;
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_cat(),
			'title' => $this->get_title(),
			'contents' => $this->get_contents(),
			'location' => $this->get_location(),
			'start_date' => $this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : '',
			'end_date' => $this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : '',
			'author_id' => $this->get_author_id(),
			'registration_authorized' => $this->get_registration_authorized(),
			'max_registred_members' => $this->get_max_registred_members(),
			'repeat_number' => $this->get_repeat_number(),
			'repeat_type' => $this->get_repeat_type()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_id_cat($properties['id_category']);
		$this->set_title($properties['title']);
		$this->set_contents($properties['contents']);
		$this->set_location($properties['location']);
		$this->set_start_date(!empty($properties['start_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['start_date']) : new Date(DATE_NOW, TIMEZONE_AUTO));
		$this->set_end_date(!empty($properties['end_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $properties['end_date']) : new Date(DATE_NOW, TIMEZONE_AUTO));
		$this->set_author_id($properties['author_id']);
		$this->set_registration_authorized($properties['registration_authorized']);
		$this->set_max_registred_members($properties['max_registred_members']);
		$this->set_repeat_number($properties['repeat_number']);
		$this->set_repeat_type($properties['repeat_type']);
	}
	
	public function init_default_properties()
	{
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$this->set_id_cat(Category::ROOT_CATEGORY);
		$this->set_start_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $now->get_timestamp()));
		$this->set_end_date(new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, ($now->get_timestamp() + 3600)));
		$this->set_registration_authorized(false);
		$this->set_max_registred_members(0);
		$this->set_repeat_number(0);
		$this->set_repeat_type('never');
	}
	
	public function clean_start_and_end_date()
	{
		$this->start_date = null;
		$this->end_date = null;
	}
}
?>