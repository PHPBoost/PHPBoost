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
include_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');

class Mini_calendar
{
	# Public #
	// Constructeur d'un calendrier : il dépend du nom qu'il aura lorsqu'on le récupèrera dans le formulaire
	function Mini_calendar($form_name)
	{
		// Feinte pour PHP 4, en PHP 5 on mettra un attribut static à la classe
		static $num_instance = 0;
		$this->form_name = $form_name;
		$this->num_instance = ++$num_instance;
		$this->date = new Date(DATE_NOW);
	}
	
	//Fonction d'assignation de la date
	function Set_date($date)
	{
		$this->date = $date;
	}
	
	//Fonction de renvoi de la date
	function Get_date()
	{
		return $this->date;
	}
	
	//Fonction d'affichage du calendrier, qui charge automatiquement le javascript
	function Display()
	{
		global $CONFIG;
		// Feine pour PHP 4, en PHP 5 ce sera un attribut static
		static $js_inclusion_already_done = false;
		
		//On crée le code selon le template
		$template = new Template('framework/mini_calendar.tpl');
		
		$template->Assign_vars(array(
			'DEFAULT_DATE' => $this->date->Format_date(DATE_FORMAT_SHORT),
			'THEME' => $CONFIG['theme'],
			'CALENDAR_ID' => 'calendar_' . $this->num_instance,
			'CALENDAR_NUMBER' => (string)$this->num_instance,
			'DAY' => $this->date->Get_day(),
			'MONTH' => $this->date->Get_month(),
			'YEAR' => $this->date->Get_year(),
			'FORM_NAME' => $this->form_name,
			'C_INCLUDE_JS' => !$js_inclusion_already_done
		));
		
		$js_inclusion_already_done = true;
		
		return $template->Tparse(TEMPLATE_STRING_MODE);
	}
	
	# Private #
	
	var $num_instance = 0;
	var $form_name = '';
	var $date;
}


?>