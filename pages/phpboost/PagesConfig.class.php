<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 22
 * @since       PHPBoost 3.0 - 2012 03 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesConfig extends DefaultModuleConfig
{
	const DEFAULT_CONTENT           = 'default_content';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const DEFERRED_OPERATIONS       = 'deferred_operations';
	const LEFT_COLUMN_DISABLED      = 'left_column_disabled';
	const RIGHT_COLUMN_DISABLED     = 'right_column_disabled';
	const VIEWS_NUMBER 				= 'views_number';

	public static function __static()
	{
		self::$module_id = 'pages';
	}

	public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function is_left_column_disabled()
	{
		return $this->get_property(self::LEFT_COLUMN_DISABLED);
	}

	public function set_left_column($value)
	{
		$this->set_property(self::LEFT_COLUMN_DISABLED, $value);
	}

	public function is_right_column_disabled()
	{
		return $this->get_property(self::RIGHT_COLUMN_DISABLED);
	}

	public function set_right_column($value)
	{
		$this->set_property(self::RIGHT_COLUMN_DISABLED, $value);
	}

	public function get_views_number()
	{
		return $this->get_property(self::VIEWS_NUMBER);
	}

	public function set_views_number($views_number)
	{
		$this->set_property(self::VIEWS_NUMBER, $views_number);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::DEFAULT_CONTENT => '',
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('pages'),
			self::AUTHORIZATIONS => array('r-1' => 33, 'r0' => 37, 'r1' => 61),
			self::DEFERRED_OPERATIONS => array(),
			self::LEFT_COLUMN_DISABLED => false,
			self::RIGHT_COLUMN_DISABLED => false,
			self::VIEWS_NUMBER => true,
		);
	}
}
?>
