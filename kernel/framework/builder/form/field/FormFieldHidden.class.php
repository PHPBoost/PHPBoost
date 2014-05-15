<?php
/*##################################################
 *                          FormFieldHidden.class.php
 *                            -------------------
 *   begin                : April 28, 2009
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage hidden input fields.
 * @package {@package}
 */
class FormFieldHidden extends AbstractFormField
{
    private static $tpl_src = '<input type="hidden" id="${escape(HTML_ID)}" name="${escape(HTML_ID)}" value="${escape(VALUE)}">';

    public function __construct($id, $value)
    {
        parent::__construct($id, '', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_common_template_variables($template);

        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
        $request = AppContext::get_request();
        $this->set_value($request->get_value($this->get_html_id(), ''));
    }

    protected function get_default_template()
    {
        return new StringTemplate(self::$tpl_src);
    }
}
?>