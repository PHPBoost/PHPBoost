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

//Répétition d'un caractère.
function str_repeat(charrepeat, nbr)
{
	var string = '';
	for(var i = 0; i < nbr; i++)
		string += charrepeat;
	return string;
}

//Recherche d'une chaîne dans une autre.
function strpos(haystack, needle)
{
    var i = haystack.indexOf(needle, 1); // returns -1
    return i >= 0 ? i : false;
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

//Masque un bloc.
function hide_inline(divID)
{
	if( document.getElementById(divID) )
		document.getElementById(divID).style.visibility = 'hidden';
}

//Affiche un bloc
function show_inline(divID)
{
	if( document.getElementById(divID) )
		document.getElementById(divID).style.visibility = 'visible';
}

//Change l'adresse d'une image
function change_img_path(id, path)
{
	if( document.getElementById(id) )
		document.getElementById(id).src = path;
}

//Switch entre deux images.
function switch_img(id, path, path2)
{
	if( document.getElementById(id) )
	{	
		if( strpos(document.getElementById(id).src, path.replace(/\.\./g, '')) != false )	
			document.getElementById(id).src = path2;
		else
			document.getElementById(id).src = path;
	}
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
    for( var i = 0; i < anArray.length; i++)
    {
        if( anArray[i] == aValue )
            return true;
    }
    return false;
}

//Barre de progression, 
var timeout_progress_bar = null;
var max_percent = 0;
var info_progress_tmp = '';
var progressbar_speed = 20; //Vitesse de la progression.
var progressbar_size = 55; //Taille de la barre de progression.
var progressbar_id = 'test'; //Identifiant de la barre de progression.
var restart_progress = false;
var theme = '';

//Configuration de la barre de progression.
function load_progress_bar(progressbar_speed_tmp, theme_tmp, progressbar_id_tmp)
{
	progressbar_speed = progressbar_speed_tmp;
	restart_progress = true;
	theme = theme_tmp;
	progressbar_id = progressbar_id_tmp;
	if( arguments.length == 4 ) //Argument optionnel.
		progressbar_size = arguments[3];
}

//Barre de progression.
function progress_bar(percent_progress, info_progress)
{
	bar_progress = (percent_progress * progressbar_size) / 100;
	if( arguments.length == 4 )
	{
		result_id = arguments[3];
		result_msg = arguments[4];
	}
	else
	{
		result_id = "";
		result_msg = "";
	}	
	// Déclaration et initialisation d'une variable statique
	if( restart_progress )
	{	
		clearTimeout(timeout_progress_bar);
		this.percent_begin = 0;
		max_percent = 0;
		if( document.getElementById('progress_bar' + progressbar_id) )
			document.getElementById('progress_bar' + progressbar_id).innerHTML = '';
		restart_progress = false;
	}

	if( this.percent_begin <= bar_progress )
	{
		if( document.getElementById('progress_bar' + progressbar_id) )
			document.getElementById('progress_bar' + progressbar_id).innerHTML += '<img src="../templates/' + theme + '/images/progress.png" alt="" />';
		if( document.getElementById('progress_percent' + progressbar_id) )
			document.getElementById('progress_percent' + progressbar_id).innerHTML = Math.round((this.percent_begin * 100) / progressbar_size);
		if( document.getElementById('progress_info' + progressbar_id) )
		{	
			if( percent_progress > max_percent )
			{	
				max_percent = percent_progress;
				info_progress_tmp = info_progress;
			}
			document.getElementById('progress_info' + progressbar_id).innerHTML = info_progress_tmp;
		}
		//Message de fin
		if( this.percent_begin == progressbar_size && result_id != "" && result_msg != "" )
			document.getElementById(result_id).innerHTML = result_msg;
		timeout_progress_bar = setTimeout('progress_bar(' + percent_progress + ', "' + info_progress + '", "' + result_id + '", "' + result_msg.replace(/"/g, "\\\"") + '")', progressbar_speed);
	}
	else
		this.percent_begin = this.percent_begin - 1;
	this.percent_begin++;
}

//Fonction de préparation de l'ajax.
function xmlhttprequest_init(filename)
{
	var xhr_object = null;
	if( window.XMLHttpRequest ) //Firefox
	   xhr_object = new XMLHttpRequest();
	else if( window.ActiveXObject ) //Internet Explorer
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
function browserAJAXFriendly()
{
    if ( window.XMLHttpRequest || window.ActiveXObject )
        return true;
    else
        return false;
}

//Fonction de recherche des membres.
function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
{
	var login = document.getElementById('login' + searchid).value;
	if( login != '' )
	{
		if( document.getElementById('search_img' + searchid) )
			document.getElementById('search_img' + searchid).innerHTML = '<img src="../templates/' + theme +'/images/loading_mini.gif" alt="" class="valign_middle" />';
		var xhr_object = xmlhttprequest_init('../includes/xmlhttprequest.php?' + insert_mode + '=1');
		data = 'login=' + login + '&divid=' + searchid;
		xhr_object.onreadystatechange = function() 
		{
			if( xhr_object.readyState == 4 && xhr_object.status == 200 ) 
			{
				if( document.getElementById('search_img' + searchid) )
					document.getElementById('search_img' + searchid).innerHTML = '';
				if( document.getElementById("xmlhttprequest_result_search" + searchid) )
					document.getElementById("xmlhttprequest_result_search" + searchid).innerHTML = xhr_object.responseText;
				show_div("xmlhttprequest_result_search" + searchid);
			}
			else if( xhr_object.readyState == 4 ) 
			{
				if( document.getElementById('search_img' + searchid) )
					document.getElementById('search_img' + searchid).innerHTML = '';
			}
		}
		xmlhttprequest_sender(xhr_object, data);
	}	
	else
		alert(alert_empty_login);
}

//Fonction d'ajout de membre dans les autorisations.
function XMLHttpRequest_add_member_auth(searchid, user_id, login, alert_already_auth)
{
	var selectid = document.getElementById('members_auth' + searchid);
	for(var i = 0; i < selectid.length; i++) 
	{
		if( selectid[i].value == user_id )
		{
			alert(alert_already_auth);
			return;
		}
	}

	if( document.getElementById('advanced_auth3' + searchid) )
		document.getElementById('advanced_auth3' + searchid).innerHTML += '<option value="' + user_id + '" selected="selected">' + login + '</option>';
}
