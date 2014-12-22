<?php
/*##################################################
 *                       FormFieldsetHTML.class.php
 *                            -------------------
 *   begin                : May 01, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Régis Viarre <crowkait@phpboost.com>
 */
class FormFieldsetHTML extends AbstractFormFieldset
{
    private $title = '';

    /**
     * @desc constructor
     * @param string $name The name of the fieldset
     */
    public function __construct($id, $name = '', $options = array())
    {
    	parent::__construct($id, $options);
        $this->title = $name;
    }

    /**
     * @desc Return the form
     * @param Template $template Optionnal template
     * @return string
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $template->put_all(array(
        	'C_TITLE' => !empty($this->title),
			'L_TITLE' => $this->title
        ));

        $this->assign_template_fields($template);

        return $template;
    }

    /**
     * @param string $title The fieldset title
     */
    public function set_title($title)
    {
        $this->title = $title;
    }

    /**
     * @return string The fieldset title
     */
    public function get_title()
    {
        return $this->title;
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormFieldset.tpl');
    }
}

?>