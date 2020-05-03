<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 03
 * @since       PHPBoost 5.3 - 2020 05 03
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
			switch ($old_config->get_items_default_sort_field())
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
				case 'number_comments':
					$config->set_items_default_sort_field('comments');
				break;
				default:
					$config->set_items_default_sort_field($old_config->get_items_default_sort_field());
				break;
			}
			ArticlesConfig::save();
			
			return true;
		}
		return false;
	}
}
?>
