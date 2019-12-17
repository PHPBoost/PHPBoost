<?php
/**
 * @package     PHPBoost
 * @subpackage  Update
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 26
 * @since       PHPBoost 2.0 - 2008 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class Repository
{
	private $url = '';
	private $xml = null;

	/**
	 * constructor of the class
	 * @param $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
		if (function_exists('simplexml_load_file'))
		{
			$this->xml = @simplexml_load_file($this->url);
			if ($this->xml == false)
			{
				$this->xml = null;
			}
		}
	}

	/**
	 * Check Application
	 * @param $app
	 */
	public function check($app)
	{
		$xpath_query = '//app[@id=\'' . $app->get_id() . '\' and @type=\'' .  $app->get_type() . '\']/version[@language=\'' . $app->get_language() . '\']';
		// can't compare strings with XPath, so we check the version number with PHP.
		if ($this->xml != null)
		{
			$newerVersions = array();
			$versions = $this->xml->xpath($xpath_query);
			$nbVersions = $versions != false ? count($versions) : 0;
			// Retrieves all the available updates for the current application
			for ($i = 0; $i < $nbVersions; $i++)
			{
				$rep_app = clone($app);
				$rep_app->load($versions[$i]);

				if (version_compare($app->get_version(), $rep_app->get_version(), '<') > 0)
				{
					if ($rep_app->check_compatibility())
					{
						$newerVersions[$rep_app->get_version()] = $i;
					}
				}
			}

			$server_configuration = new ServerConfiguration();
			
			// Keep only the first applyable update
			if ($server_configuration->has_curl_library() && $app->get_type() == 'kernel')
				$NewVersion = count($newerVersions) > 0 ? max(array_keys($newerVersions)) : '';
			else
				$NewVersion = count($newerVersions) > 0 ? min(array_keys($newerVersions)) : '';
			
			if (!empty($NewVersion))
			{
				$app->load($versions[$newerVersions[$NewVersion]]);
				return $app;
			}
		}
		return null;
	}

	/**
	 * Accessor of url
	 */
	public function get_url() { return $this->url; }
}
?>
