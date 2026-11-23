<?php
/**
 * A LinksMenuElement contains a Title, an url, and an image url and/or an fonticon
 * <div class="message-helper bgc notice">Abstract class : Do not instanciate it
 * LinksMenuLink and LinksMenuLink classes are based on this class
 * use, on of these</div>
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

abstract class LinksMenuElement extends Menu
{
    const LINKS_MENU_ELEMENT__CLASS = 'LinksMenuElement';
    const LINKS_MENU_ELEMENT__FULL_DISPLAYING = true;
    const LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING = false;

    /**
     * @access protected
     * @var string the LinksMenuElement url
     */
    public string $url = '';
    /**
     * @access protected
     * @var string the image url. Could be relative to the website root or absolute
     */
    public string $image = '';
    /**
     * @access protected
     * @var string the icon class
     */
    public string $icon = '';
    /**
     * @access protected
     * @var bool open the link in a new window
     */
    public bool $target;
    /**
     * @access protected
     * @var int Menu's uid
     */
    public ?int $uid = null;
    /**
     * @access protected
     * @var int Menu's depth
     */
    public int $depth = 0;

    /**
     * Build a LinksMenuElement object
     * @param int $id The Menu's id in the database
     * @param $title
     * @param $url
     * @param $image
     * @param $icon
     * @param $target
     */
    public function __construct(string $title, string $url, bool $target, string $image = '', string $icon = '')
    {
        parent::__construct($title);
        $this->uid = AppContext::get_uid();
        $this->set_url($url);
        $this->set_image($image);
        $this->set_icon($icon);
        $this->set_target($target);
    }

    /**
     * Assign tpl vars
     * @access protected
     * @param Template $view the template on which we gonna assign vars
     * @param bool $mode in LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING mode, the links menu is
     * displayed. With the LinksMenuElement::LINKS_MENU_ELEMENT__FULL_DISPLAYING mode, the authorization form is
     * also shown.
     */
    protected function _assign(Template $view, bool $mode = self::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING): void
    {
        if ($this->image)
        {
            if ($this->image instanceof Url)
            {
                $url = $this->image;
                $file = new File($this->image);
            }
            else
            {
                $url = new Url($this->image);
                $file = new File(PATH_TO_ROOT . $this->image);
            }

            if (!$url->is_relative() || $file->exists())
            {
                if (!$url->is_relative())
                {
                    $image = new Image($url->absolute());
                }
                else
                {
                    $image = new Image(PATH_TO_ROOT . $this->image);
                }

                $view->put_all([
                    'C_IMG'        => !empty($this->image),
                    'ABSOLUTE_IMG' => $url->absolute(),
                    'RELATIVE_IMG' => $url->relative(),
                    'REL_IMG'      => $url->rel(),
                    'IMG_HEIGHT'   => $image->get_height(),
                    'IMG_WIDTH'    => $image->get_width()
                ]);
            }
        }

        parent::_assign($view);

        if ($this->url instanceof Url)
        {
            $url = $this->url;
        }
        else
        {
            $url = new Url($this->url);
        }

        $view->put_all([
            'C_MENU'         => false,
            'C_DISPLAY_AUTH' => AppContext::get_current_user()->check_auth($this->get_auth(), Menu::MENU_AUTH_BIT),
            'C_URL'          => $this->url,
            'C_ICON'      	 => !empty($this->icon),
            'C_FA_ICON'    	 => TextHelper::strpos($this->icon, 'fa-') !== false,
            'C_TARGET'    	 => $this->target,

            'TITLE'        => $this->title,
            'ICON'         => $this->icon,
            'DEPTH'        => $this->depth,
            'PARENT_DEPTH' => $this->depth - 1,
            'ID'           => $this->get_uid(),
            'ID_VAR'       => $this->get_uid(),

            'ABSOLUTE_URL' => $url->absolute(),
            'RELATIVE_URL' => $url->relative(),
            'REL_URL'      => $url->rel(),

            'L_TITLE' => LangLoader::get_message('menu.link.to', 'menu-lang') . $this->title,
        ]);

        //Full displaying: we also show the authorization formulary
        if ($mode)
        {
            $view->put_all([
                'C_AUTH_MENU_HIDDEN' => $this->get_auth() == ['r-1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r1' => Menu::MENU_AUTH_BIT],
                'AUTH_FORM' => Authorizations::generate_select(Menu::MENU_AUTH_BIT, $this->get_auth(), [], 'menu_element_' . $this->uid . '_auth')
            ]);
        }
    }

    /**
     * @param string $string_url the url to relativize / absolutize
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the $string_url url
     */
    private function _get_url(string $string_url, bool $compute_relative_url = true): string
    {
        if ($string_url instanceof Url)
        {
            $url = $string_url;
        }
        else
        {
            $url = new Url($string_url);
        }

        if ($compute_relative_url)
        {
            return $url->relative();
        }
        else
        {
            return $url->absolute();
        }
    }

    /**
     * Increase the Menu Depth and set the menu type to its parent one
     * @access protected
     */
    protected function _parent(string $type): void
    {
        $this->depth++;
    }

    ## Setters ##
    /**
     * @param string $image the value to set
     */
    public function set_image(string $image): void
    {
        $this->image = $image;
    }
    /**
     * @param string $url the value to set
     */
    public function set_url(string $url): void
    {
        $this->url = $url;
    }
    /**
     * @param string $icon the value to set
     */
    public function set_icon(string $icon): void
    {
        $this->icon = $icon;
    }
    /**
     * @param bool $target the value to set
     */
    public function set_target(bool $target): void
    {
        $this->target = $target;
    }

    ## Getters ##
    /**
     * Returns the menu uid
     * @return int the menu uid
     */
    public function get_uid(): int
    {
        return $this->uid;
    }
    /**
     * Update the menu uid
     */
    public function update_uid(): void
    {
        $this->uid = AppContext::get_uid();
    }
    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the link $url
     */
    public function get_url(bool $compute_relative_url = true): string
    {
        return $this->_get_url($this->url, $compute_relative_url);
    }

    /**
     * @param bool $compute_relative_url If true, computes relative urls to the website root
     * @return string the $image url
     */
    public function get_image(bool $compute_relative_url = true): string
    {
        return $this->_get_url($this->image, $compute_relative_url);
    }

    /**
     * @return string the $icon url
     */
    public function get_icon(): string
    {
        return $this->icon;
    }

    /**
     * @return bool true if the link is set to open in a new window, false otherwise
     */
    public function get_target(): bool
    {
        return $this->target;
    }
}
?>
