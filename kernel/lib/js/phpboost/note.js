/*##################################################
 *                                global.js
 *                            -------------------
 *   begin                : April 09 2008
 *   copyright            : (C) 2008 Régis Viarre
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

var note_timeout = null;
var on_img = 0;
		
function select_stars(divid, note)
{
	var star_img;
	var decimal;
	for(var i = 1; i <= note_max; i++)
	{
		star_img = 'stars.png';
		if( note < i )
		{							
			decimal = i - note;
			if( decimal >= 1 )
				star_img = 'stars0.png';
			else if( decimal >= 0.75 )
				star_img = 'stars1.png';
			else if( decimal >= 0.50 )
				star_img = 'stars2.png';
			else
				star_img = 'stars3.png';
		}
		
		if( document.getElementById('n' + divid + '_stars' + i) )
			document.getElementById('n' + divid + '_stars' + i).src = PATH_TO_ROOT + '/templates/' + theme + '/images/' + star_img;
	}
}
function out_div(divid, note)
{
	if( note_timeout == null )
		note_timeout = setTimeout('select_stars(' + divid + ', ' + note + ');on_img = 0;', '50');
}		
function over_div()
{
	if( on_img == 0 )
		on_img = 1;
	clearTimeout(note_timeout);
	note_timeout = null;
}
function send_note(id, note, token)
{
	var regex = /\/|\\|\||\?|<|>|\"/;
	var get_nbrnote;
	var get_note;
	
	document.getElementById('noteloading' + id).innerHTML = '<img src="' + PATH_TO_ROOT + '/templates/' + theme + '/images/loading_mini.gif" alt="" class="valign_middle" />';
	
	data = "id=" + id + "&note=" + note;
	var xhr_object = xmlhttprequest_init('xmlhttprequest.php?note=1&token=' + token);
	xhr_object.onreadystatechange = function() 
	{
		if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
		{	
			document.getElementById('noteloading' + id).innerHTML = '';
			if( xhr_object.responseText == '-1' )
				alert(l_already_voted);
			else if( xhr_object.responseText == '-2' )
				alert(l_auth_error);
			else
			{	
				eval(xhr_object.responseText);
				array_note[id] = get_note;
				select_stars(id, get_note);
				if( document.getElementById('nbrnote' + id) )
					document.getElementById('nbrnote' + id).innerHTML = '(' + get_nbrnote + ' ' + ((get_nbrnote > 1) ? l_notes : l_note) + ')';
			}				
		}
		else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
			document.getElementById('noteloading' + id).innerHTML = '';
	}
	xmlhttprequest_sender(xhr_object, data);
}
