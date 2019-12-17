<?php
/**
 * @package     Content
 * @subpackage  Keyword
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 07 07
 * @since       PHPBoost 4.0 - 2013 08 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AjaxKeywordsAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();

		try {
			$result = PersistenceContext::get_querier()->select("SELECT name, rewrited_name FROM " . DB_TABLE_KEYWORDS . " WHERE name LIKE '" . $request->get_value('value', '') . "%'");

			while($row = $result->fetch())
			{
				$suggestions[] = $row['name'];
			}
			$result->dispose();
		} catch (Exception $e) {
		}

		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>
