<?php
/**
 * Contains meta-informations and informations about a feed item enclosure
 * @package     Content
 * @subpackage  Feed
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 4.0 - 2013 04 16
*/

class FeedItemEnclosure
{
	private $lenght;
	private $type;
	private $url;

	/**
	 * Defines the lenght (in bytes) of the media file
	 * @param int $lenght
	 */
	public function set_lenght($lenght)
	{
		$this->lenght = $lenght;
	}

	public function get_lenght()
	{
		return $this->lenght;
	}

	/**
	 * Defines the type of media file
	 * @param string $type
	 */
	public function set_type($type)
	{
		$this->type = $type;
	}

	public function get_type()
	{
		return $this->type;
	}

	/**
	 * Defines the URL to the media file
	 * @param mixed $url a string url or an Url object
	 */
	public function set_url($url)
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url->rel();
	}

	public function get_url()
	{
		return $this->url;
	}
}
?>
