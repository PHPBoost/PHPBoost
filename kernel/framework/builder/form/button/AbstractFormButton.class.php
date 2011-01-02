<?php
/*##################################################
 *                        AbstractFormButton.class.php
 *                            -------------------
 *   begin                : February 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
abstract class AbstractFormButton extends AbstractFormField implements FormButton
{
    private $type = '';
    private $name = '';
    private $onclick_action = '';

    public function __construct($type, $value, $name, $onclick_action = '', $field_options = array())
    {
        parent::__construct($name, '', $value, $field_options, array());
    	
        if ($this->get_css_field_class() == '') {
        	$this->set_css_field_class('inline');
        }
        $this->name = $name;
        $this->type = $type;
        $this->onclick_action = $onclick_action;
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = $this->get_default_template();
        $this->assign_common_template_variables($template);
        
        $template->put_all(array(
			'VALUE' => $this->get_value(),
			'BUTTON_NAME' => $this->name,
			'TYPE' => $this->type,
			'ONCLICK_ACTION' => $this->onclick_action
        ));
        return $template;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_label()
    {
        return $this->label;
    }

    public function set_label($label)
    {
        $this->label = $label;
    }

    protected function get_default_template()
    {
    	return new StringTemplate('<dl# IF C_HAS_FIELD_CLASS # class="{FIELD_CLASS}"# ENDIF #>
    		<button type="${TYPE}" name="${BUTTON_NAME}" class="submit" onclick="${escape(ONCLICK_ACTION)}" value="true">{VALUE}</button>
    	</dl>');
    }
}
?>