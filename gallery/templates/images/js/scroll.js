/*##################################################
*                                scroll.js
*                            -------------------
*   begin                : August 23, 2007
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

var scroll_stopped = false; //Statut du défilement.
var timeout;

//Démarrage du défilement, dépendant du mode.
function scroll_start()
{
	if( scroll_mode == 'dynamic_scroll_v' )
		scroll_pics_v();
	else if( scroll_mode == 'dynamic_scroll_h' )
		scroll_pics_h();
	else
		switch_pics();
}

//Arrêt/redémarrage du défilement.
function temporize_scroll()
{
	if( scroll_stopped ) //Lancement du défilement.
	{
		scroll_start();
		scroll_stopped = false;
	}
	else //Arrêt
	{	
		if( timeout )
			clearTimeout(timeout);	
		scroll_stopped = true;
	}
}

//Transtypage des entiers.
function numeric(number)
{
	return isNaN(number = parseInt(number)) ? 0 : number;
}

/* Vertical scroll mode */
//pics_height et hidden_height, scroll_speed en global.
function scroll_pics_v()
{
	var regex = /(-?[0-9]+)px/;
	var thumb = document.getElementById('thumb_mini');
	if( thumb )
	{
		var get_top = regex.exec(thumb.style.top);		
		if( get_top[1] )
			get_top = numeric(get_top[1]) - 1;
		else
			get_top = 0;	
		thumb.style.top = get_top + 'px';	

		if( -get_top > sum_height )
			restart_scroll_v();
	}
	timeout = setTimeout('scroll_pics_v()', scroll_speed);
}

/* Horizontal scroll mode */
function scroll_pics_h()
{
	var regex = /(-?[0-9]+)px/;
	var thumb = document.getElementById('thumb_mini');
	if( thumb )
	{
		var get_left = regex.exec(thumb.style.left);		
		if( get_left[1] )
			get_left = numeric(get_left[1]) - 1;
		else
			get_left = 0;	
		thumb.style.left = get_left + 'px';	
		if( -get_left > sum_width )
			restart_scroll_h();
	}
	
	timeout = setTimeout('scroll_pics_h()', scroll_speed);
}

//Recommence le défilement du début.
function restart_scroll_v()
{
	var thumb = document.getElementById('thumb_mini');
	if( thumb )
		thumb.style.top = hidden_height + 'px';	
}

//Recommence le défilement du début.
function restart_scroll_h()
{
	var thumb = document.getElementById('thumb_mini');
	if( thumb )
		thumb.style.left = hidden_width + 'px';
}

/* Switch mode */	
function switch_pics()
{
	var index;

	index = Math.round(Math.random() * (array_pics_mini.length - 1));		
	if( array_pics_mini )
		document.getElementById('thumb_mini').innerHTML = '<a href="../gallery/gallery' + array_pics_mini[index]['link'] + '"><img src="../gallery/pics/thumbnails/' + array_pics_mini[index]['path'] + '" alt="" /></a>';

	timeout = setTimeout("switch_pics()", scroll_speed);
}

//Lancement.
scroll_start(); 
