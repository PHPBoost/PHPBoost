<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 6.0 - 2020 05 03
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('articles-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if (class_exists('ArticlesConfig') && !empty($old_config))
		{
			$config = ArticlesConfig::load();
			$number_articles_per_page = $number_categories_per_page = $number_cols_display_per_line = $number_character_to_cut = $items_default_sort_field = $items_default_sort_mode = $descriptions_displayed_to_guests = $date_updated_displayed = $display_type = '';

			try {
				$number_articles_per_page = $old_config->get_property('number_articles_per_page');
			} catch (PropertyNotFoundException $e) {}
			if ($number_articles_per_page)
				$config->set_items_per_page($number_articles_per_page);

			try {
				$number_categories_per_page = $old_config->get_property('number_categories_per_page');
			} catch (PropertyNotFoundException $e) {}
			if ($number_categories_per_page)
				$config->set_categories_per_page($number_categories_per_page);

			try {
				$number_cols_display_per_line = $old_config->get_property('number_cols_display_per_line');
			} catch (PropertyNotFoundException $e) {}
			if ($number_cols_display_per_line)
				$config->set_items_per_row($number_cols_display_per_line);

			try {
				$number_character_to_cut = $old_config->get_property('number_character_to_cut');
			} catch (PropertyNotFoundException $e) {}
			if ($number_character_to_cut)
				$config->set_auto_cut_characters_number($number_character_to_cut);

			try {
				$items_default_sort_field = $old_config->get_property('items_default_sort_field');
			} catch (PropertyNotFoundException $e) {}
			if ($items_default_sort_field)
			{
				switch ($items_default_sort_field)
				{
					case 'date_created':
						$config->set_items_default_sort_field('date');
					break;
					case 'display_name':
						$config->set_items_default_sort_field('author');
					break;
					case 'number_view':
						$config->set_items_default_sort_field('views');
					break;
					case 'average_notes':
						$config->set_items_default_sort_field('notes');
					break;
					case 'comments_number':
						$config->set_items_default_sort_field('comments');
					break;
					default:
						$config->set_items_default_sort_field($items_default_sort_field);
					break;
				}
			}

			try {
				$items_default_sort_mode = $old_config->get_property('items_default_sort_mode');
			} catch (PropertyNotFoundException $e) {}
			if ($items_default_sort_mode)
			$config->set_items_default_sort_mode(in_array(TextHelper::strtoupper($items_default_sort_mode), array(Item::ASC, Item::DESC)) ? TextHelper::strtolower($items_default_sort_mode) : TextHelper::strtolower(Item::DESC));

			try {
				$descriptions_displayed_to_guests = $old_config->get_property('descriptions_displayed_to_guests');
			} catch (PropertyNotFoundException $e) {}
			if ($descriptions_displayed_to_guests)
				$config->set_summary_displayed_to_guests($descriptions_displayed_to_guests);

			try {
				$date_updated_displayed = $old_config->get_property('date_updated_displayed');
			} catch (PropertyNotFoundException $e) {}
			if ($date_updated_displayed)
				$config->set_update_date_displayed($date_updated_displayed);

			try {
				$display_type = $old_config->get_property('display_type');
			} catch (PropertyNotFoundException $e) {}
			if ($display_type)
			{
				switch ($display_type)
				{
					case 'mosaic':
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

			ArticlesConfig::save();

			return true;
		}
		return false;
	}
}
?>
