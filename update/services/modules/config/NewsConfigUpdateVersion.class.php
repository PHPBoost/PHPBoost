<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 02
 * @since       PHPBoost 6.0 - 2021 02 02
*/

class NewsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		if (class_exists('NewsConfig') && !empty($old_config))
		{
			$config = NewsConfig::load();
			$number_news_per_page = $number_columns_display_news = $display_condensed_enabled = $descriptions_displayed_to_guests = $number_character_to_cut = $news_suggestions_enabled = $nb_view_enabled = $display_type = '';
			
			try {
				$number_news_per_page = $old_config->get_property('number_news_per_page');
			} catch (PropertyNotFoundException $e) {}
			if ($number_news_per_page)
				$config->set_items_per_page($number_news_per_page);
			
			try {
				$number_columns_display_news = $old_config->get_property('number_columns_display_news');
			} catch (PropertyNotFoundException $e) {}
			if ($number_columns_display_news)
				$config->set_items_per_row($number_columns_display_news);
			
			try {
				$display_condensed_enabled = $old_config->get_property('display_condensed_enabled');
			} catch (PropertyNotFoundException $e) {}
			if ($display_condensed_enabled)
				$config->set_full_item_display($display_condensed_enabled);
			
			try {
				$descriptions_displayed_to_guests = $old_config->get_property('descriptions_displayed_to_guests');
			} catch (PropertyNotFoundException $e) {}
			if ($descriptions_displayed_to_guests)
				$config->display_summary_to_guests();
			else
				$config->hide_summary_to_guests();
			
			try {
				$number_character_to_cut = $old_config->get_property('number_character_to_cut');
			} catch (PropertyNotFoundException $e) {}
			if ($number_character_to_cut)
				$config->set_characters_number_to_cut($number_character_to_cut);
			
			try {
				$news_suggestions_enabled = $old_config->get_property('news_suggestions_enabled');
			} catch (PropertyNotFoundException $e) {}
			if ($news_suggestions_enabled)
				$config->set_item_suggestions_enabled($news_suggestions_enabled);
			
			try {
				$nb_view_enabled = $old_config->get_property('nb_view_enabled');
			} catch (PropertyNotFoundException $e) {}
			if ($nb_view_enabled)
				$config->set_views_number_enabled($nb_view_enabled);
				
			try {
				$display_type = $old_config->get_property('display_type');
			} catch (PropertyNotFoundException $e) {}
			if ($display_type)
			{
				switch ($display_type)
				{
					case 'block':
						$config->set_display_type('grid_view');
					break;
					case 'list':
						$config->set_display_type('list_view');
					break;
					default:
						$config->set_display_type($display_type);
					break;
				}
			}
			
			NewsConfig::save();
			
			return true;
		}
		return false;
	}
}
?>
