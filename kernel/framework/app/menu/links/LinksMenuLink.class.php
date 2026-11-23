<?php
/**
 * A Simple menu link
 * @package     PHPBoost
 * @subpackage  Menu\links
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 22
 * @since       PHPBoost 2.0 - 2008 07 08
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LinksMenuLink extends LinksMenuElement
{
	const LINKS_MENU_LINK__CLASS = 'LinksMenuLink';

	/**
	* Constructor
	* @param string $title Menu title
	* @param string $url Destination url
	* @param string $image Menu's image url relative to the website root or absolute
	* @param string $icon Menu's icon class
	* @param bool $target If true, opens the link in a new window
	* @param int $id The Menu's id in the database
	*/
	public function __construct(string $title, Url|string $url, bool $target, string $image = '', string $icon = '')
	{
		parent::__construct($title, $url, $target, $image, $icon);
	}

	public function get_default_template(): string
	{
		return '';
	}

	/**
	 * Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	public function display(Template|bool $template = false, bool $mode = LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING): string
	{
		// Stop if the user isn't authorised
		if (!$this->check_auth())
		{
			return '';
		}

		parent::_assign($template, $mode);
		$template->put_all([
			'C_DISPLAY_AUTH' => AppContext::get_current_user()->check_auth($this->get_auth(), Menu::MENU_AUTH_BIT),
			'C_LINK' => true
		]);

		return $template->render();
	}
}
?>
