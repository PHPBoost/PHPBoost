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

include_once('../kernel/framework/util/date.class.php');
include_once('../kernel/framework/template.class.php');

class Mini_calendar
{
	// Constructeur d'un calendrier : il dépend du nom qu'il aura lorsqu'on le récupèrera dans le formulaire
	function Mini_calendar($form_name)
	{
		// Feinte pour PHP 4, en PHP 5 on mettra un attribut static à la classe
		static $nbr_instances = 0;
		$this->form_name = $form_name;
		$this->nbr_instances = ++$nbr_instances;
	}
	
	//Fonction d'affichage du calendrier, qui charge automatiquement le javascript
	function Display_calendar($date = false)
	{
		global $CONFIG;
		// Feine pour PHP 4, en PHP 5 ce sera un attribut static
		static $js_inclusion_already_done = false;

		if( $date === false )
			$date = new Date(DATE_NOW);
		
		//On crée le code selon le template
		$template = new Template('framework/mini_calendar.tpl');
		
		$template->Assign_vars(array(
			'DEFAULT_DATE' => $date->Format_date(DATE_FORMAT_SHORT),
			'THEME' => $CONFIG['theme'],
			'CALENDAR_ID' => 'calendar_' . $this->nbr_instances,
			'CALENDAR_NUMBER' => (string)$this->nbr_instances,
			'DAY' => $date->Get_day(),
			'MONTH' => $date->Get_month(),
			'YEAR' => $date->Get_year(),
			'C_INCLUDE_JS' => !$js_inclusion_already_done
		));
		
		$js_inclusion_already_done = true;
		
		return $template->Tparse(TEMPLATE_STRING_MODE);
	}
	
	var $nbr_instances = 0;
	var $form_name = '';
}


?>