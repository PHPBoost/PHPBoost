<?php
/**
* This class is an abstract class. It contains the common elements needed by all the unparsers of PHPBoost.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 2.0 - 2008 08 10
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class ContentFormattingUnparser extends AbstractParser
{
	/**
	 * Builds a ContentFormattingUnparser class.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Unparses the html code. In a first time, it pick the html tags up, and then, when you have done all the processings you wanted, you reimplant it.
	* @param bool $action self::PICK_UP if you want to pick up the html tag and self::REIMPLANT to reimplant it.
	*/
	protected function unparse_html($action)
	{
		//Prélèvement du HTML
		if ($action == self::PICK_UP)
		{
			$mask = '`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`suU';
			$content_split = preg_split($mask, str_replace('&nbsp;', '&amp;nbsp;', $this->content), -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;

			if ($content_length > 1)
			{
				$this->content = '';
				for ($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if ($i % 2 == 0)
					{
						$this->content .= $content_split[$i];
						//Ajout du tag de remplacement
						if ($i < $content_length - 1)
						{
							$this->content .= '[HTML_UNPARSE_TAG_' . $id_tag++ . ']';
						}
					}
					else
					{
						$this->array_tags['html_unparse'][] = $content_split[$i];
					}
				}

				//On protège le code HTML à l'affichage qui vient non protégé de la base de données
				$this->array_tags['html_unparse'] = array_map(function($var) {return TextHelper::htmlspecialchars($var, ENT_NOQUOTES);}, $this->array_tags['html_unparse']);
			}
			return true;
		}
		//Réinsertion du HTML
		else
		{
			if (!array_key_exists('html_unparse', $this->array_tags))
			{
				return false;
			}

			$content_length = count($this->array_tags['html_unparse']);

			if ($content_length > 0)
			{
				for ($i = 0; $i < $content_length; $i++)
				{
					$this->content = str_replace('[HTML_UNPARSE_TAG_' . $i . ']', '[html]' . $this->array_tags['html_unparse'][$i] . '[/html]', $this->content);
				}
				$this->array_tags['html_unparse'] = array();
			}
			return true;
		}
	}

	/**
	 * Unparses the code tag. In a first time, you pick it up and you reimplant it.
	 * @param bool $action self::PICK_UP to pick the code tag up, self::REIMPLANT to reinsert them.
	 */
	protected function unparse_code($action)
	{
		//Prélèvement du HTML
		if ($action == self::PICK_UP)
		{
			$mask = '`\[\[CODE(=[^,\s]+(?:,(?:0|1)(?:,1)?)?)?\]\]' . '(.+)' . '\[\[/CODE\]\]`suU';
			$content_split = preg_split($mask, str_replace('&nbsp;', '&amp;nbsp;', $this->content), -1, PREG_SPLIT_DELIM_CAPTURE);

			$content_length = count($content_split);
			$id_tag = 0;

			if ($content_length > 1)
			{
				$this->content = '';
				for ($i = 0; $i < $content_length; $i++)
				{
					//contenu
					if ($i % 3 == 0)
					{
						$this->content .= $content_split[$i];
						//Ajout du tag de remplacement
						if ($i < $content_length - 1)
						{
							$this->content .= '[CODE_UNPARSE_TAG_' . $id_tag++ . ']';
						}
					}
					elseif ($i % 3 == 2)
					{
						$this->array_tags['code_unparse'][] = '[code' . $content_split[$i - 1] . ']' . $content_split[$i] . '[/code]';
					}
				}
			}
			return true;
		}
		//Réinsertion du HTML
		else
		{
			if (!array_key_exists('code_unparse', $this->array_tags))
			{
				return false;
			}

			$content_length = count($this->array_tags['code_unparse']);

			if ($content_length > 0)
			{
				for ($i = 0; $i < $content_length; $i++)
				$this->content = str_replace('[CODE_UNPARSE_TAG_' . $i . ']', $this->array_tags['code_unparse'][$i], $this->content);
				$this->array_tags['code_unparse'] = array();
			}
			return true;
		}
	}

	/**
	 * @desc Unparsed module special tags if any.
	 * The special tags are [link] for module pages or wiki for example.
	 */
	protected function unparse_module_special_tags()
	{
		foreach ($this->get_module_special_tags() as $pattern => $replacement)
			$this->content = preg_replace($pattern, $replacement, $this->content);
	}
	
	/**
	 * @desc Callback which allows to unparse the font awasome icons tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the fa tag are parsed
	 */
	protected function unparse_fa_tag($matches)
	{
		$fa_code = '';
		$special_fa = in_array($matches[1], array('b', 'l', 'r', 's', 'd'));
		$options_list = isset($matches[3]) ? $matches[3] : '';
		$style = !empty($matches[4]) ? ' style="' . $matches[4] . '"' : '';

		if ( !empty($options_list) ) {
			$options = explode(' ', $options_list);
			foreach ($options as $index => $option) {
				if (!empty($option)) {
					if ( $index == 1 && empty($fa_code) ) {
						$fa_code = "=" . ($special_fa ? 'fa' . $matches[1] . ',' : '') . $option;
					} else {
						if ( !empty($fa_code) ) { $fa_code = $fa_code . ","; }
						$fa_code = $fa_code . $option;
					}
				}
			}
		}
		else
		{
			if ($special_fa)
				$fa_code = "=" . 'fa' . $matches[1];
		}

		return '[fa' . $fa_code . $style . ']' . $matches[2] . '[/fa]';
	}
	
	/**
	 * @desc Callback which allows to unparse the Wikipedia tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the wikipedia tag are parsed
	 */
	protected function unparse_wikipedia_tag($matches)
	{
		$lang = ($matches[1] == LangLoader::get_message('wikipedia_subdomain', 'editor-common')) ? '' : $matches[1];
		$page_name = ($matches[2] != $matches[3]) ? $matches[2] : '';

		return '[wikipedia' . (!empty($page_name) ? ' page="' . $page_name . '"' : '') . (!empty($lang) ? ' lang="' . $lang . '"' : '') . ']' . $matches[3] . '[/wikipedia]';
	}
}
?>
