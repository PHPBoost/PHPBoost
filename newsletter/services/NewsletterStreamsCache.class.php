<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 07
 * @since       PHPBoost 4.0 - 2014 05 21
*/

class NewsletterStreamsCache extends CategoriesCache
{
	public function get_table_name()
	{
		return NewsletterSetup::$newsletter_table_streams;
	}

	public function get_table_name_containing_items()
	{
		return NewsletterSetup::$newsletter_table_archives;
	}

	public function get_category_class()
	{
		return 'NewsletterStream';
	}

	public function get_module_identifier()
	{
		return 'newsletter';
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(NewsletterConfig::load()->get_authorizations());
		return $root;
	}

	public function get_streams()
	{
		return $this->get_categories();
	}

	public function stream_exists($id)
	{
		return array_key_exists($id, $this->get_categories());
	}

	public function get_stream($id)
	{
		return $this->get_category($id);
	}
}
?>
