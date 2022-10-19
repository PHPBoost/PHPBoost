<?php
/**
 * This class is used to represent the bread crumb displayed on each page of the website.
 * It enables the user to locate himself in the whole site.
 * A bread crumb can look like this: Home >> My module >> First level category >> Second level category >>
 * Third level category >> .. >> My page >> Edition
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 23
 * @since       PHPBoost 1.6 - 2007 02 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class BreadCrumb
{
	/**
	 * @var string[string] List of the links
	 */
	private $array_links = array();
	/**
	 * @var SiteDisplayGraphicalEnvironment The graphical environment in which the breadcrumb is
	 */
	private $graphical_environment;

	/**
	 * Adds a link in the bread crumb. This link will be put at the end of the list.
	 * @param string $text Name of the page
	 * @param string $target Link whose target is the page
	 */
	public function add($text, $target = '')
	{
		if (!empty($text))
		{
			$url = $target instanceof Url ? $target->rel() : $target;
			$this->array_links[] = array($text, $url);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Reverses the whole list of the links. It's very useful when it's easier for you to make the list in the reverse way, at the
	 * end, you only need to reverse the list and it will be ok.
	 */
	public function reverse()
	{
		$this->array_links = array_reverse($this->array_links);
	}

	/**
	 * Removes the last link of the list
	 */
	public function remove_last()
	{
		array_pop($this->array_links);
	}

	/**
	 * Displays the bread crumb.
	 */
	public function display(Template $tpl)
	{
		if (empty($this->array_links))
		{
			$this->add($this->graphical_environment->get_page_title(), REWRITED_SCRIPT);
		}

		$tpl->put('START_PAGE', TPL_PATH_TO_ROOT . '/');

		$output = array_slice($this->array_links, -1, 1);
		$position = 2;
		foreach ($this->array_links as $key => $array)
		{
			$tpl->assign_block_vars('link_bread_crumb', array(
				'C_CURRENT' => $output[0] == $array,
				'URL'       => $array[1],
				'TITLE'     => $array[0],
				'POSITION'  => $position
			));
			$position++;
		}
	}

	/**
	 * Removes all the existing links.
	 */
	public function clean()
	{
		$this->array_links = array();
	}

	/**
	 * Get all links of the list
	 */
	public function get_links()
	{
		return $this->array_links;
	}

	/**
	 * Sets the reference to the parent graphical environment
	 * @param SiteDisplayGraphicalEnvironment $env The parent environment
	 */
	public function set_graphical_environment(SiteDisplayGraphicalEnvironment $env)
	{
		$this->graphical_environment = $env;
	}
}
?>
