<?php
/**
 * A Simple menu link
 * @package     PHPBoost
 * @subpackage  Menu\links
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 04
 * @since       PHPBoost 2.0 - 2008 07 08
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
	* @param int $id The Menu's id in the database
	*/
	public function __construct($title, $url, $image = '', $icon = '')
	{
		parent::__construct($title, $url, $image, $icon);
	}

	/**
	 * Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	public function display($template = false, $mode = LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
		// Stop if the user isn't authorised
		if (!$this->check_auth())
		{
			return '';
		}

		parent::_assign($template, $mode);
		$template->put_all(array(
			'C_DISPLAY_AUTH' => AppContext::get_current_user()->check_auth($this->get_auth(), Menu::MENU_AUTH_BIT),
			'C_LINK' => true
		));

		return $template->render();
	}
}
?>
