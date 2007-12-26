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

var delay_menu = 800; //Durée après laquelle le menu est cachée lors du départ de la souris.
var delay_onmouseover = 180; //Durée après laquelle la menu est affiché lors du passage de la souris dessus.
var previous_menu;
var timeout;
var timeout_tmp;
var started = false;

//Fonction de temporisation, permet d'éviter que le menu déroulant perturbe la navigation lors du survol rapide de la souris.
function show_menu(num)
{
	if( !started )
		timeout_tmp = setTimeout('temporise_menu(' + num + ')', delay_onmouseover);
	else
		temporise_menu(num);
}

//Fonction d'affichage du menu déroulant.
function temporise_menu(num) 
{
	var id = 'smenu' + num;
	var id = document.getElementById(id);
	var i;
	
	//Destruction du timeout.
	if( timeout )
		clearTimeout(timeout);
	if( timeout_tmp )
		clearTimeout(timeout_tmp);
	
	//Masque les menus
	if( document.getElementById(previous_menu) ) 
	{
		document.getElementById(previous_menu).style.display = 'none';
		started = false;
	}
	
	//Affichage du menu, et enregistrement dans le tableau de gestion.
	if( id ) 
	{	
		id.style.display = 'block';
		previous_menu = 'smenu' + num;
		started = true;
	}
}	

//Cache le menu déroulant lorsque le curseur de la souris n'y est plus pendant delay_menu millisecondes.
function hide_menu()
{			
	//Destruction du timeout lors du départ de la souris.
	if( timeout_tmp && !started )
		clearTimeout(timeout_tmp);
	
	//Masque le menu, après le délai défini.
	if( started )
		timeout = setTimeout('temporise_menu()', delay_menu);	
}

//Affichage/Masquage de la balise hide.
function bb_hide(div2)
{

	var divs = div2.getElementsByTagName('div');
var div3 = divs[0];
	if (div3.style.visibility == 'visible')
	div3.style.visibility = 'hidden';
	else
	div3.style.visibility = 'visible';
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

function popup(page, name)
{
	var testhauteur=screen.height;
	var testlargeur=screen.width;
	
	if(testhauteur==600 && testlargeur==800)
		window.open(page, name, "width=680, height=540,location=no,status=no,toolbar=no,scrollbars=yes");
	else if(testhauteur == 768 && testlargeur == 1024)
		window.open(page, name, "width=660, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else if(testhauteur == 864 && testlargeur == 1152)
		window.open(page, name, "width=660, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else
		window.open(page, name, "width=660, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
}
