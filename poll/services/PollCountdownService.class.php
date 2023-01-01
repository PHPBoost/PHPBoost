<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 6.0 - 2021 01 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PollCountdownService
{
	public static function display(Item $item)
	{
		$view = new FileTemplate('poll/PollCountdown.tpl');
		$view->add_lang(LangLoader::get_all_langs('poll'));

		if ($item->is_published() && $item->end_date_enabled())
		{
			$end = $item->get_publishing_end_date();

			$view->put_all(array(
				'C_COUNTDOWN_WITH_S' => ((string) $item->get_countdown_display()) == $item::COUNTDOWN_DISPLAY_WITH_S,
				'PUBLISHING_END_DATE' => $end->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) . ' ' . $end->get_date_time()->format('H:i:s')
			));
		}

		return $view;
	}
}
?>
