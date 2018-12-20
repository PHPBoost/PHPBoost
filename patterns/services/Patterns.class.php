<?php
/*##################################################
 *		                         Patterns.class.php
 *                            -------------------
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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

/**
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class Patterns
{
	private $id;
	private $name;
	private $contents;

	private $approbation_type;

	private $creation_date;
	private $updated_date;

	private $author_user;
	private $author_custom_name;
	private $author_custom_name_enabled;

	const NOT_APPROVAL  = 0;
	const APPROVAL_NOW  = 1;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_approbation_type($approbation_type)
	{
		$this->approbation_type = $approbation_type;
	}

	public function get_approbation_type()
	{
		return $this->approbation_type;
	}

	public function get_status()
	{
		if ($this->approbation_type == self::APPROVAL_NOW)
				return LangLoader::get_message('status.approved.now', 'common');

		if ($this->approbation_type == self::NOT_APPROVAL)
				return LangLoader::get_message('status.approved.not', 'common');

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

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'name' => $this->get_name(),
			'contents' => $this->get_contents(),
			'approbation_type' => $this->get_approbation_type(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'updated_date' => $this->get_updated_date() !== null ? $this->get_updated_date()->get_timestamp() : 0,
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id()
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->name = $properties['name'];
		$this->contents = $properties['contents'];
		$this->approbation_type = $properties['approbation_type'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->updated_date = !empty($properties['updated_date']) ? new Date($properties['updated_date'], Timezone::SERVER_TIMEZONE) : null;

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
		$this->author_custom_name_enabled = !empty($properties['author_custom_name']);
	}

	public function init_default_properties()
	{
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->author_custom_name = $this->author_user->get_display_name();
		$this->author_custom_name_enabled = false;
	}

	public function get_array_tpl_vars()
	{
		$patterns_config = PatternsConfig::load();
		$contents = FormatingHelper::second_parse($this->contents);
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date,'date'),
			array(
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete()
		));
	}
}
?>
