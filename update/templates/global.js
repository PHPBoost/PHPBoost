/*##################################################
 *                                global.js
 *                            -------------------
 *   begin                : March 15 2008
 *   copyright            : (C) 2008 Viarre Régis
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

//Barre de progression, 
var timeout_progress_bar = null;
var max_percent = 0;
var info_progress_tmp = '';
var speed_progress = 20; //Vitesse de la progression.
var progressbar_size = 55; //Taille de la barre de progression.
var idbar = ''; //Identifiant de la barre de progression.
var restart_progress = false;

//Configuration de la barre de progression.
function load_progress_bar(progressbar_speed_tmp, progressbar_id_tmp)
{
	progressbar_speed = progressbar_speed_tmp;
	restart_progress = true;
	progressbar_id = progressbar_id_tmp;
	if( arguments.length == 3 ) //Argument optionnel.
		progressbar_size = arguments[2];
}

//Barre de progression.
function progress_bar(percent_progress, info_progress)
{
	bar_progress = (percent_progress * progressbar_size) / 100;
	if( arguments.length == 4 )
	{
		result_id = arguments[2];
		result_msg = arguments[3];
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
			document.getElementById('progress_bar' + progressbar_id).innerHTML += '<img src="templates/images/progress.png" alt="" />';
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

//Supprime les espaces en début et fin de chaîne.
function trim(myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
} 

//Vérifie une adresse email
function check_mail_validity(mail)
{
	regex = new RegExp("^[a-z0-9._!#$%&\'*+/=?^|~-]+@([a-z0-9._-]{2,}\.)+[a-z]{2,4}$", "i");
	return regex.test(trim(mail));
}