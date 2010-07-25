<?php
/*##################################################
 *                           AdminErrorsDisplayResponse.class.php
 *                            -------------------
 *   begin                : December 13 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 */
class InstallDisplayResponse extends AbstractResponse
{	
    private $full_view;

    public function __construct($page_title, Template $view, $step_title, $step_explanation)
    {
        $this->full_view = new FileTemplate('install/main.tpl');
        $this->full_view->add_subtemplate('step', $view);
        
        $lang = LangLoader::get('install', 'install');
        $view->add_lang($lang);
        $this->full_view->add_lang($lang);

        $env = new InstallDisplayGraphicalEnvironment();
        $env->set_page_title($page_title);
        
        $this->full_view->assign_vars(array(
            'STEP_TITLE' => $step_title,
            'STEP_EXPLANATION' => $step_explanation,
            'C_HAS_PREVIOUS_STEP' => false,
            'C_HAS_NEXT_STEP' => false
        ));
        
        parent::__construct($env, $this->full_view);
    }

    public function set_previous_step($name, $url)
    {
        $this->full_view->assign_vars(array(
            'C_HAS_PREVIOUS_STEP' => true,
            'PREVIOUS_STEP_URL' => $url,
            'L_PREVIOUS_STEP_TITLE' => $name,
        ));
    }

    public function set_next_step($name, $url)
    {
        $this->full_view->assign_vars(array(
            'C_HAS_NEXT_STEP' => true,
            'NEXT_STEP_URL' => $url,
            'L_NEXT_STEP_TITLE' => $name,
        ));
    }
}
?>