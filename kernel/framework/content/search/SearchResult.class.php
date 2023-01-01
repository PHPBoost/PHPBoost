<?php
/**
 * @package     Content
 * @subpackage  Search
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 08
*/

class SearchResult
{
	private $id;
	private $title;
	private $link;
	private $relevance;

	/**
	 * Builds a <code>SearchResult</code> object
	 * @param int $id the element identifier in its own module
	 * @param string $title the element title
	 * @param string $link a link to a page that will display the element
	 * @param float $relevance the relevance of the element within the current search
	 */
	public function __construct($id, $title, $link, $relevance)
	{
		$this->id = $id;
		$this->title = $title;
		$this->link = $link;
		$this->relevance = $relevance;
	}

	public function get_id()
	{
		return $this->module_id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function get_link()
	{
		return Url::to_rel($this->link);
	}

	public function get_relevance()
	{
		return $this->relevance;
	}
}
?>
