<?php
/*##################################################
 *                                mini_calendar.class.php
 *                            -------------------
 *   begin                : June 3rd, 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Mini_calendar 1.0
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

include_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
include_once(PATH_TO_ROOT . '/kernel/framework/io/template.class.php');

class MiniCalendar
{
	# Public #
	// Constructeur d'un calendrier : il dépend du nom qu'il aura lorsqu'on le récupèrera dans le formulaire
	function MiniCalendar($form_name)
	{
		// Feinte pour PHP 4, en PHP 5 on mettra un attribut static à la classe
		static $num_instance = 0;
		$this->form_name = $form_name;
		$this->num_instance = ++$num_instance;
		$this->date = new Date(DATE_NOW);
	}
	
	//Fonction d'assignation de la date
	function set_date($date)
	{
		$this->date = $date;
	}
	
	//Fonction d'assignation de style css au conteneur du calendrier.
	function set_style($style)
	{
		$this->style = $style;
	}
	
	//Fonction de renvoi de la date
	function get_date()
	{
		return $this->date;
	}
	
	//Fonction d'affichage du calendrier, qui charge automatiquement le javascript
	function display()
	{
		global $CONFIG;
		
		// Feinte pour PHP 4, en PHP 5 ce sera un attribut static
		static $js_inclusion_already_done = false;
		
		//On crée le code selon le template
		$template = new Template('framework/mini_calendar.tpl');
		
		$template->assign_vars(array(
			'DEFAULT_DATE' => $this->date->format(DATE_FORMAT_SHORT),
			'CALENDAR_ID' => 'calendar_' . $this->num_instance,
			'CALENDAR_NUMBER' => (string)$this->num_instance,
			'DAY' => $this->date->get_day(),
			'MONTH' => $this->date->get_month(),
			'YEAR' => $this->date->get_year(),
			'FORM_NAME' => $this->form_name,
			'CALENDAR_STYLE' => $this->style,
			'C_INCLUDE_JS' => !$js_inclusion_already_done
		));
		
		$js_inclusion_already_done = true;
		
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	# Private #	
	var $num_instance = 0;
	var $style = '';
	var $form_name = '';
	var $date;
}


?>