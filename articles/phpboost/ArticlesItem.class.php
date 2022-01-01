<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 11
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class ArticlesItem extends RichItem
{
	public function get_real_summary($parsed_content = '')
	{
		$summary = $this->get_additional_property('summary');

		if (!empty($summary))
		{
			return FormatingHelper::second_parse($summary);
		}
		else
		{
			$clean_content = preg_split('`\[page\].+\[/page\](.*)`usU', $this->content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			return TextHelper::cut_string(@strip_tags($clean_content[0], '<br><br/>'), (int)ArticlesConfig::load()->get_auto_cut_characters_number());
		}
	}
}
?>
