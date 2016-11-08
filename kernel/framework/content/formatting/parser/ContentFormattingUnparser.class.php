<?php
/*##################################################
 *                       ContentFormattingUnparser.class.php
 *                            -------------------
 *   begin                : August 10, 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is an abstract class. It contains the common elements needed by all the unparsers of PHPBoost.
 */
abstract class ContentFormattingUnparser extends AbstractParser
{
	/**
	 * @desc Builds a ContentFormattingUnparser class.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* @desc Unparses the html code. In a first time, it pick the html tags up, and then, when you have done all the processings you wanted, you reimplant it.
	* @param bool $action self::PICK_UP if you want to pick up the html tag and self::REIMPLANT to reimplant it.
	*/
	protected function unparse_html($action)
	{
		//Prélèvement du HTML
		if ($action == self::PICK_UP)
		{
			$mask = '`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`sU';
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
				$this->array_tags['html_unparse'] = array_map(create_function('$var', 'return TextHelper::htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['html_unparse']);
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
	 * @desc Unparses the code tag. In a first time, you pick it up and you reimplant it.
	 * @param bool $action self::PICK_UP to pick the code tag up, self::REIMPLANT to reinsert them.
	 */
	protected function unparse_code($action)
	{
		//Prélèvement du HTML
		if ($action == self::PICK_UP)
		{
			$mask = '`\[\[CODE(=[A-Za-z0-9#+-_.\s]+(?:,(?:0|1)(?:,1)?)?)?\]\]' . '(.+)' . '\[\[/CODE\]\]`sU';
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
}
?>