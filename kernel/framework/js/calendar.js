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

var delay = 1000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
var timeout;
var calendars_num = 0;
var displayed_calendars = new Array();
var association_name_id = new Array();

//Affiche le bloc.
function display_calendar(id)
{
	for( var i = 1; i <=calendars_num; i++ )
	{
		if( i != id )
			hide_calendar(i, 2);
	}
	
	if( timeout )
		clearTimeout(timeout);
	
	if( !displayed_calendars[id] )
	{
		document.getElementById('calendar' + id).style.display = 'block';
		displayed_calendars[id] = true;
	}
}

//Cache le bloc.
//Mode : 
// * 1 désarme le timeout
// * 2 cache immédiatement le bloc
// * Autrement on arme le timeout de fermeture
function hide_calendar(id, mode)
{
	if( mode == 1 && timeout )
	{	
		clearTimeout(timeout);
	}
	else if( mode == 2 && displayed_calendars[id] )
	{
		document.getElementById('calendar' + id).style.display = 'none';
		displayed_calendars[id] = false;
		if( timeout )
			clearTimeout(timeout);
	}
	else if( displayed_calendars[id] )
	{
		timeout = setTimeout('hide_calendar(\'' + id + '\', 2)', delay);
	}	
}

//Insertion de la date.
function insert_date(field, date) 
{
	if( document.getElementById(field) )
		document.getElementById(field).value = date;
}

//Fonction Ajax d'affichage du calendrier.
function xmlhttprequest_calendar(field, vars)
{
	var xhr_object = null;
	var data = null;
	var filename = PATH_TO_ROOT + '/kernel/framework/ajax/mini_calendar_xmlhttprequest.php' + vars;
	
	if(window.XMLHttpRequest) // Firefox
	   xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else // XMLHttpRequest non supporté par le navigateur
		return;
	
	xhr_object.open('POST', filename, true);
	xhr_object.onreadystatechange = function() 
	{
		if( xhr_object.readyState == 4 ) 
		{
			document.getElementById(field).innerHTML = xhr_object.responseText;
			show_div(field);
		}
	}

	xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_object.send(data);		
}

function check_mini_calendar_form(name)
{
	reg_exp = new RegExp("[0-9]{2}/[0-9]{2}/[0-9]{2,4}", "g");
	form_id = association_name_id[name];
	return document.getElementById(form_id).value.match(reg_exp);
}