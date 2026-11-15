<?php
/**
 * Contains meta-informations and informations about a feed item enclosure
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 4.0 - 2013 04 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedItemEnclosure
{
	private int $lenght = 0;
	private string $type = '';
	private mixed $url = null;

	/**
	 * Defines the lenght (in bytes) of the media file
	 * @param int $lenght
	 */
	public function set_lenght(int $lenght): void
	{
		$this->lenght = $lenght;
	}

	public function get_lenght(): int
	{
		return $this->lenght;
	}

	/**
	 * Defines the type of media file
	 * @param string $type
	 */
	public function set_type(string $type): void
	{
		$this->type = $type;
	}

	public function get_type(): string
	{
		return $this->type;
	}

	/**
	 * Defines the URL to the media file
	 * @param mixed $url a string url or an Url object
	 */
	public function set_url(mixed $url): void
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url->rel();
	}

	public function get_url(): mixed
	{
		return $this->url;
	}
}
?>
