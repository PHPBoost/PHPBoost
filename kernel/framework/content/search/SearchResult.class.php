<?php
/**
 * @package     Content
 * @subpackage  Search
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 10
 * @since       PHPBoost 3.0 - 2010 02 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SearchResult
{
	private int $id = 0;
	private string $title = '';
	private string $link = '';
	private float $relevance = 0;

	/**
	 * Builds a <code>SearchResult</code> object
	 * @param int $id the element identifier in its own module
	 * @param string $title the element title
	 * @param string $link a link to a page that will display the element
	 * @param float $relevance the relevance of the element within the current search
	 */
	public function __construct(int $id, string $title, string $link, float $relevance)
	{
		$this->id = $id;
		$this->title = $title;
		$this->link = $link;
		$this->relevance = $relevance;
	}

	public function get_id(): int
	{
		return $this->id;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function get_link(): string
	{
		return Url::to_rel($this->link);
	}

	public function get_relevance(): float
	{
		return $this->relevance;
	}
}
?>
