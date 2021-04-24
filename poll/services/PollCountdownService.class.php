<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 01 04
 * @since       PHPBoost 6.0 - 2021 01 02
*/

class PollCountdownService
{
	public static function display(Item $item)
	{
		$tpl = new FileTemplate('poll/PollCountdown.tpl');
		$lang = array_merge(LangLoader::get('date-common'), LangLoader::get('common', 'poll'));
		$tpl->add_lang($lang);
		
		if ($item->is_published() && $item->end_date_enabled())
		{
			$end = $item->get_publishing_end_date();

			$tpl->put_all(array(
				'PUBLISHING_END_DATE' 		=> $end->format(Date::FORMAT_ISO_DAY_MONTH_YEAR) . ' ' . $end->get_date_time()->format('H:i:s'),
				'C_COUNTDOWN_WITH_S' 		=> ((string) $item->get_countdown_display()) == $item::COUNTDOWN_DISPLAY_WITH_S		
			));
		}

		return $tpl;
	}
}
?>
