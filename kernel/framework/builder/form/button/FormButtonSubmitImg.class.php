<?php
/*##################################################
 *                        FormButtonSubmitImg.class.php
 *                            -------------------
 *   begin                : October 03, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * 
 * @author Loic Rouchon <horn@phpboost.com>
 * @package {@package}
 */
class FormButtonSubmitImg extends FormButtonSubmit
{
	private $img;
	private $css_class;
	
    public function __construct($img, $title, $name, $onclick_action = '', $css_class = 'img_submit')
    {
        $this->img = $img;
        $this->css_class = $css_class;
        parent::__construct($title, $name, $onclick_action);
    }
    
    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = parent::display();
        $template->put('IMG', $this->img);
        $template->put('CSS_CLASS', $this->css_class);
        return $template;
    }

    public function has_been_submited()
    {
        $request = AppContext::get_request();
        $button_attribute_x = $request->get_string($this->get_name() . '_x', '');
        $button_attribute_y = $request->get_string($this->get_name() . '_y', '');
        return !empty($button_attribute_x) && !empty($button_attribute_y);
    }
    
    protected function get_template()
    {
        return new StringTemplate('<input type="image" src="${IMG}" name="${BUTTON_NAME}" title="${escape(VALUE)}" class="${escape(CSS_CLASS)}" onclick="${escape(ONCLICK_ACTION)}" />');
    }
}
?>