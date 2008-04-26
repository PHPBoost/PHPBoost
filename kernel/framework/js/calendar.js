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
var displayed = false;

//Affiche le bloc.
function display_calendar(id)
{
	var i;
	
	if( timeout )
		clearTimeout(timeout);
	
	for(i = 1; i <= 3; i++)
	{
		var block = document.getElementById('calendar' + i);
		if( block )
		{
			if( id == i )
			{				
				if( block.style.display == 'none' )
				{
					block.style.display = 'block';
					displayed = true;
				}
				else
				{
					block.style.display = 'none';
					displayed = false;
				}
			}
			else
				block.style.display = 'none';	
		}
	}	
}

//Cache le bloc.
function hide_calendar(id, stop)
{
	if( stop && timeout )
	{	
		clearTimeout(timeout);
	}
	else if( displayed )
	{
		clearTimeout(timeout);
		timeout = setTimeout('display_calendar(\'' + id + '\')', delay);
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
	var filename = '../kernel/calendar.php' + vars;
	
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