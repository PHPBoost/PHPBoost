<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 11
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Article extends RichItem
{
	const AUTHOR_NAME_NOTDISPLAYED = 0;
	const AUTHOR_NAME_DISPLAYED = 1;

	public static function __static()
	{
		parent::__static();
		self::add_additional_attribute('author_name_displayed', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => self::AUTHOR_NAME_DISPLAYED));
	}

	public function get_author_name_displayed()
	{
		return $this->get_additional_property('author_name_displayed');
	}

	public function set_author_name_displayed($value)
	{
		$this->set_additional_property('author_name_displayed', $value);
	}

	public function get_real_summary($parsed_content = '')
	{
		$summary = $this->get_additional_property('summary');
		
		if (!empty($summary))
		{
			return FormatingHelper::second_parse($summary);
		}
		else
		{
			$clean_content = preg_split('`\[page\].+\[/page\](.*)`usU', $parsed_content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			return TextHelper::cut_string(@strip_tags($clean_content[0], '<br><br/>'), (int)ArticlesConfig::load()->get_auto_cut_characters_number());
		}
	}

	protected function default_properties()
	{
		$this->content = ArticlesConfig::load()->get_default_content();
		$this->set_additional_property('author_name_displayed', self::AUTHOR_NAME_DISPLAYED);
	}
}
?>
