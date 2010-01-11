/*##################################################
 *                                calendar.js
 *                            -------------------
 *   begin                : April 18, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

var delay_calendar = 1000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
var timeout_calendar;
var displayed = false;
var previous_calendar = '';
var association_name_id = new Array();

//Affiche le bloc.
function display_calendar(divID)
{
	if (timeout_calendar)
		clearTimeout(timeout_calendar);
	
	var block = document.getElementById('calendar' + divID );
	if (block.style.display == 'none')
	{
		if( previous_calendar != '' && document.getElementById(previous_calendar) )
			document.getElementById(previous_calendar).style.display = 'none';
		block.style.display = 'block';
		displayed = true;
		previous_calendar = 'calendar' + divID;
	}
	else
	{
		block.style.display = 'none';
		displayed = false;
	}
}

//Cache le bloc.
function hide_calendar(id, stop)
{
	if (stop && timeout_calendar)
	{	
		clearTimeout(timeout_calendar);
	}
	else if (displayed)
	{
		clearTimeout(timeout_calendar);
		timeout_calendar = setTimeout('display_calendar(\'' + id + '\')', delay_calendar);
	}	
}

//Insertion de la date.
function insert_date(field, date) 
{
	if (document.getElementById(field))
		document.getElementById(field).value = date;
}

//Fonction Ajax d'affichage du calendrier.
function xmlhttprequest_calendar(field, vars)
{
	filename = PATH_TO_ROOT + '/kernel/framework/ajax/mini_calendar_xmlhttprequest.php' + vars;
	default_image = PATH_TO_ROOT + '/templates/' + THEME + '/images/calendar.png';
	
	if ($('img' + field))
	{
		default_image = $('img' + field).src;
		$('img' + field).src = PATH_TO_ROOT + '/templates/' + theme + '/images/loading_mini.gif';
	}
	
	new Ajax.Request(filename,
			{
			method: 'get',
			parameters: {},
			onSuccess: function(response)
			{
				$(field).innerHTML = response.responseText;
				show_div(field, true);
				$('img' + field).src = default_image;
			}
		}
	);
}

function check_mini_calendar_form(name)
{
	reg_exp = new RegExp("[0-9]{2}/[0-9]{2}/[0-9]{2,4}", "g");
	form_id = association_name_id[name];
	return document.getElementById(form_id).value.match(reg_exp);
}
