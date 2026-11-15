<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2010 10 31
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class JSONResponse implements Response
{
	private string|false $json;

	public function __construct(array $json_object)
	{
		AppContext::get_session()->no_session_location();

		$this->json = json_encode($json_object);
	}

	public function send(): void
	{
		$response = AppContext::get_response();
		$response->set_header('Content-type', 'application/json; charset=UTF-8');
		echo $this->json;
	}
}

?>
