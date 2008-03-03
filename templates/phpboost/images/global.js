/*##################################################
 *                                global.js
 *                            -------------------
 *   begin                : Februar 06 2007
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

var menu_delay = 800; //Durée après laquelle le menu est cachée lors du départ de la souris.
var menu_delay_onmouseover = 180; //Durée après laquelle la menu est affiché lors du passage de la souris dessus.
var menu_previous = new Array('', '', '');
var menu_timeout = new Array(null, null, null);
var menu_timeout_tmp = new Array(null, null, null);
var menu_started = new Array(false, false, false);
var max_level = 3;

//Fonction de temporisation, permet d'éviter que le menu déroulant perturbe la navigation lors du survol rapide de la souris.
function show_menu(idmenu, level)
{
	if( !menu_started[level] )
		menu_timeout_tmp[level] = setTimeout('temporise_menu(\'' + idmenu + '\', ' + level + ')', menu_delay_onmouseover);
	else if( menu_previous[level] != idmenu )
		temporise_menu(idmenu, level);
	else
	{
		if( menu_timeout[level] )
			clearTimeout(menu_timeout[level]);
		if( menu_timeout_tmp[level] )
			clearTimeout(menu_timeout_tmp[level]);
	}
}

//Fonction d'affichage du menu déroulant.
function temporise_menu(idmenu, level) 
{
	var divID = str_repeat('s', level) + 'smenu';
	var	id = document.getElementById(divID + idmenu);

	//Destruction du timeout.
	if( menu_timeout[level] )
		clearTimeout(menu_timeout[level]);
	if( menu_timeout_tmp[level] )
		clearTimeout(menu_timeout_tmp[level]);
	
	//Masque les menus
	if( document.getElementById(divID + menu_previous[level]) ) 
	{
		document.getElementById(divID + menu_previous[level]).style.visibility = 'hidden';
		menu_started[level] = false;
		
		for(var i = level; i < max_level; i++) //Masque le sous menus.
		{
			var divID2 = str_repeat('s', i) + 'smenu';
			if( document.getElementById(divID2 + menu_previous[i]) )
				document.getElementById(divID2 + menu_previous[i]).style.visibility = 'hidden';
		}
	}
	
	//Affichage du menu, et enregistrement dans le tableau de gestion.
	if( id ) 
	{	
		id.style.visibility = 'visible';
		menu_previous[level] = idmenu;
		menu_started[level] = true;
	}
}	

//Cache le menu déroulant lorsque le curseur de la souris n'y est plus pendant delay_menu millisecondes.
function hide_menu(level)
{			
	//Destruction du timeout lors du départ de la souris.
	for(var i = 0; i < max_level; i++)
	{
		if( menu_timeout_tmp[i] && !menu_started[i] )
			clearTimeout(menu_timeout_tmp[i]);
	}
	
	//Masque le menu, après le délai défini.
	if( menu_started[level] )
		menu_timeout[level] = setTimeout('temporise_menu(\'\', ' + level + ')', menu_delay);
}

function str_repeat(charrepeat, nbr)
{
	var string = '';
	for(var i = 0; i < nbr; i++)
		string += charrepeat;
	return string;
}

//Affichage/Masquage de la balise hide.
function bb_hide(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if( div3.style.visibility == 'visible' )
	{
		div3.style.visibility = 'hidden';
		div2.style.height = '10px';
	}
	else
	{	
		div3.style.visibility = 'visible';
		div2.style.height = 'auto';
	}
	
	return true;
}

//Masque un bloc.
function hide_div(divID)
{
	if( document.getElementById(divID) )
		document.getElementById(divID).style.display = 'none';
}

//Affiche un bloc
function show_div(divID)
{
	if( document.getElementById(divID) )
		document.getElementById(divID).style.display = 'block';
}

//Afffiche/masque automatiquement un bloc.
function display_div_auto(divID, type)
{
	if( document.getElementById(divID) )
	{	
		if( type == '')
			type = 'block';
			
		if( document.getElementById(divID).style.display == type )
			document.getElementById(divID).style.display = 'none';
		else if( document.getElementById(divID).style.display == 'none' )
			document.getElementById(divID).style.display = type;
	}
}

//Popup
function popup(page,name)
{
   var screen_height = screen.height;
   var screen_width = screen.width;

	if( screen_height == 600 && screen_width == 800 )
		window.open(page, name, "width=680, height=540,location=no,status=no,toolbar=no,scrollbars=yes");
	else if( screen_height == 768 && screen_width == 1024 )
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else if( screen_height == 864 && screen_width == 1152 )
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
}

//Teste la présence d'une valeur dans un tableau
function inArray(aValue, anArray)
{
    for ( var i = 0; i < anArray.length; i++)
    {
        if ( anArray[i] == aValue )
            return true;
    }
    return false;
}

//Fonction de préparation de l'ajax.
function xmlhttprequest_init(filename)
{
	var xhr_object = null;

	if(window.XMLHttpRequest) //Firefox
	   xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) //Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

	xhr_object.open('POST', filename, true);

	return xhr_object;
}

//Fonction ajax d'envoi.
function xmlhttprequest_sender(xhr_object, data)
{
	xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr_object.send(data);
}

//Echape les variables de type chaînes dans les requêtes xmlhttprequest.
function escape_xmlhttprequest(contents)
{
	contents = contents.replace(/\+/g, '%2B');
	contents = contents.replace(/&/g, '%26');
	
	return contents;
}

//Informe sur la capacité du navigateur à supporter AJAX
function isBrowserAJAXFriendly()
{
    if ( window.XMLHttpRequest || window.ActiveXObject )
        return true;
    else
        return false;
}
