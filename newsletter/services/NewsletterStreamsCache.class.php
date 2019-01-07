<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 4.0 - 2014 05 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterStreamsCache extends CategoriesCache
{
	public function get_table_name()
	{
		return NewsletterSetup::$newsletter_table_streams;
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
