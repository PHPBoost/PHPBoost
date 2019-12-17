<?php
/**
 * Redirect to the an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2010 11 06
 * @contributor mipel <mipel@phpboost.com>
*/

class UrlRedirectMapper extends AbstractUrlMapper
{
	private $redirect_url;

	/**
	 * build a new UrlDispatcherItem
	 * @param string $redirect_url the url on which the redirection will be done
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters. By default, match the empty url <code>/</code>
	 * @throws NoSuchControllerException
	 */
	public function __construct($redirect_url, $capture_regex = '`^/?$`u')
	{
		$this->redirect_url = $redirect_url;
		parent::__construct($capture_regex);
	}

	/**
	 * Call the controller method if the url match and if the method exists
	 */
	public function call()
	{
		AppContext::get_response()->redirect($this->redirect_url);
	}
}
?>
