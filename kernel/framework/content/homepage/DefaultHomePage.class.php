<?php
/**
 * @package     Content
 * @subpackage  Homepage
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 10 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultHomePage implements HomePage
{
	private string $title = '';
	private ?View $view = null;

	public function __construct(string $title, View $view)
	{
		$this->title = $title;
		$this->view = $view;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function get_view(): View
	{
		return $this->view;
	}
}
?>
