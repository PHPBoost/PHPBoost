/*##################################################
 *                                global.js
 *                            -------------------
 *   begin                : Februar 06 2007
 *   copyright            : (C) 2007 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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

//Javascript frame breaker necessary to the CSRF attack protection
if (top != self)
{
	top.location = self.location;
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
    var i = haystack.indexOf(needle, 0); // returns -1
    return i >= 0 ? i : false;
}

//Supprime les espaces en début et fin de chaîne.
function trim(myString)
{
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
} 

//Affichage/Masquage de la balise hide.
function bb_hide(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if (div3.style.visibility == 'visible')
	{
		div3.style.visibility = 'hidden';
		div2.style.height = '10px';
		div2.style.overflow = 'hidden';
	}
	else
	{	
		div3.style.visibility = 'visible';
		div2.style.height = 'auto';
		div2.style.overflow = 'auto';
	}
	
	return true;
}

//Masque un bloc.
function hide_div(divID, useEffects)
{
    var use_effects = false
    if (arguments.length > 1)
        use_effects = useEffects;
    
    if ($(divID))
    {
        if (useEffects) Effect.SwitchOff(divID);
        $(divID).style.display = 'none';
    }
}

//Affiche un bloc
function show_div(divID, useEffects)
{
    var use_effects = false
    if (arguments.length > 1)
    {
        use_effects = useEffects;
    }
    
    if ($(divID))
    {
        if (useEffects)
    	{
			$(divID).appear({duration: 0.5});
    	}
        else
        {
        	$(divID).style.display = 'block';
        }
    }
}

//Masque un bloc.
function hide_inline(divID)
{
	if ($(divID))
	{
		Effect.SwitchOff(divID);
		$(divID).style.visibility = 'hidden';
	}
}

//Affiche un bloc
function show_inline(divID)
{
	if ($(divID))
	{	
		$(divID).appear({duration: 0.5});
		$(divID).style.visibility = 'visible';
	}
}

//Switch entre deux classes CSS.
function switch_className(id, class1, class2)
{
	if ($(id))
	{	
		if ($(id).className == class1)
			$(id).className = class2;
		else
			$(id).className = class1;
	}
}

//Afffiche/masque automatiquement un bloc.
function display_div_auto(divID, type)
{
	if ($(divID))
	{	
		if ($(divID).style.display == 'none')
			$(divID).appear();
		else
			$(divID).fade({duration: 0.5});
	}
}

//Popup
function popup(page,name)
{
   var screen_height = screen.height;
   var screen_width = screen.width;

	if (screen_height == 600 && screen_width == 800)
		window.open(page, name, "width=680, height=540,location=no,status=no,toolbar=no,scrollbars=yes");
	else if (screen_height == 768 && screen_width == 1024)
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else if (screen_height == 864 && screen_width == 1152)
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
	else
		window.open(page, name, "width=672, height=620,location=no,status=no,toolbar=no,scrollbars=yes");
}

//Teste la présence d'une valeur dans un tableau
function inArray(aValue, anArray)
{
    for( var i = 0; i < anArray.length; i++)
    {
        if (anArray[i] == aValue)
            return true;
    }
    return false;
}

//Barre de progression, 
function change_progressbar(id_element, value, informations) {
	var progress_bar_el = $(id_element).select('.progressbar').first();
	progress_bar_el.setStyle({width: value + '%'});

	if (informations) {
		var progress_bar_infos_el = $(id_element).select('.progressbar-infos').first();
		progress_bar_infos_el.update(informations);
	}
	else {
		var progress_bar_infos_el = $(id_element).select('.progressbar-infos').first();
		progress_bar_infos_el.update(value + '%');
	}
}

//Fonction de préparation de l'ajax.
function xmlhttprequest_init(filename)
{
	var xhr_object = null;
	if (window.XMLHttpRequest) //Firefox
	   xhr_object = new XMLHttpRequest();
	else if (window.ActiveXObject) //Internet Explorer
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
    if ( window.XMLHttpRequest || window.ActiveXObject)
        return true;
    else
        return false;
}

//Fonction de recherche des membres.
function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
{
	var login = $('login' + searchid).value;
	if (login != '')
	{
		if ($('search_img' + searchid))
			$('search_img' + searchid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		var xhr_object = xmlhttprequest_init(PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?token=' + TOKEN + '&' + insert_mode + '=1');
		data = 'login=' + login + '&divid=' + searchid;
		xhr_object.onreadystatechange = function() 
		{
			if (xhr_object.readyState == 4 && xhr_object.status == 200) 
			{
				if ($('search_img' + searchid))
					$('search_img' + searchid).innerHTML = '';
				if ($("xmlhttprequest-result-search" + searchid))
					$("xmlhttprequest-result-search" + searchid).innerHTML = xhr_object.responseText;
				Effect.BlindDown('xmlhttprequest-result-search' + searchid, { duration: 0.5 });
			}
			else if (xhr_object.readyState == 4) 
			{
				if ($('search_img' + searchid))
					$('search_img' + searchid).innerHTML = '';
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
    var selectid = $('members_auth' + searchid);
    for(var i = 0; i < selectid.length; i++) //Vérifie que le membre n'est pas déjà dans la liste.
    {
        if (selectid[i].value == user_id)
        {
            alert(alert_already_auth);
            return;
        }
    }
    var oOption = new Option(login, user_id);
    oOption.id = searchid + 'm' + (selectid.length - 1);
        oOption.selected = true;

    if ($('members_auth' + searchid)) //Ajout du membre.
        $('members_auth' + searchid).options[selectid.length] = oOption;
}

//Sélection des formulaires.
function check_select_multiple(id, status)
{
	var i;	

	//Sélection des groupes.
	var selectidgroups = $('groups_auth' + id);
	for(i = 0; i < selectidgroups.length; i++)
	{	
		if (selectidgroups[i])
			selectidgroups[i].selected = status;
	}
	
	//Sélection des membres.
	var selectidmember = $('members_auth' + id);
	for(i = 0; i < selectidmember.length; i++)
	{	
		if (selectidmember[i])
			selectidmember[i].selected = status;
	}	
}

//Sélection auto des rangs supérieur à celui cliqué.
function check_select_multiple_ranks(id, start)
{
	var i;			
	for(i = start; i <= 2; i++)
	{	
		if ($(id + i))
			$(id + i).selected = true;
	}
}

// Crée un lien de pagination javascript
function writePagin(fctName, fctArgs, isCurrentPage, textPagin, i)
{
    pagin = '<span class="pagination';
    if ( isCurrentPage)
        pagin += ' pagination_current_page text-strong';
    pagin += '">';
    pagin += '<a href="javascript:' + fctName + '(' + i + fctArgs + ')">' + textPagin + '</a>';
    pagin += '</span>&nbsp;';
    
    return pagin;
}

// Crée la pagination à partir du nom du bloc de page, du bloc de pagination, du nombre de résultats
// du nombre de résultats par page ...
function ChangePagination(page, nbPages, blocPagin, blocName, nbPagesBefore, nbPagesAfter)
{
    var pagin = '';
    if ( nbPages > 1)
    {
        if (arguments.length < 5)
        {
            nbPagesBefore = 3;
            nbPagesAfter = 3;
        }
        
        var before = Math.max(0, page - nbPagesBefore);
        var after = Math.min(nbPages, page + nbPagesAfter + 1);
        
        var fctName = 'ChangePagination';
        var fctArgs = ', '  + nbPages + ', \'' + blocPagin + '\', \'' + blocName + '\', ' + nbPagesBefore + ', ' + nbPagesAfter;
        
        // Début
        if (page != 0)
            pagin += writePagin(fctName, fctArgs, false, '&laquo;', 0);
        
        // Before
        for ( var i = before; i < page; i++)
            pagin += writePagin(fctName, fctArgs, false, i + 1, i);
        
        // Page courante
        pagin += writePagin(fctName, fctArgs, true, page + 1, page);
        
        // After
        for ( var i = page + 1; i < after; i++)
            pagin += writePagin(fctName, fctArgs, false, i + 1, i);
        
        // Fin
        if (page != nbPages - 1)
            pagin += writePagin(fctName, fctArgs, false, '&raquo;', nbPages - 1);
    }
    
    // On cache tous les autre résultats du module
    for ( var i = 0; i < nbPages; i++)
        hide_div(blocName + '_' + i);
        
    // On montre la page demandée
    show_div(blocName + '_' + page);
    
    // Mise à jour de la pagination
    $(blocPagin).innerHTML = pagin;
}

// Teste si une chaine est un entier
function isInteger(number)
{
    var numbers = "0123456789";
    for ( var i = 0; i < number.length && numbers.indexOf(number[i]) != -1; i++);
    return i == number.length ;
}


/*#######Feeds menu gestion######*/
var feed_menu_timeout_in = null;
var feed_menu_timeout_out = null;
var feed_menu_elt = null;
var feed_menu_delay = 800; //Durée après laquelle le menu est caché lors du départ de la souris.

// Print the syndication's choice menu
function ShowSyndication(element)
{
    if (feed_menu_elt)
        feed_menu_elt.style.visibility = 'hidden';
    feed_menu_elt = null;
    var elts = null;
    elts = element.parentNode.getElementsByTagName('div');
    for( var i = 0; i < elts.length; i++) {
        if (elts[i].title == 'L_SYNDICATION_CHOICES') {
            feed_menu_elt = elts[i];
            break;
        }
    }
	feed_menu_elt.style.visibility = 'visible';
    clearTimeout(feed_menu_timeout_out);
}
function ShowSyndicationMenu(element)
{
	element.style.visibility = 'visible';
    clearTimeout(feed_menu_timeout_out);
}
function HideSyndication(element)
{
    feed_menu_elt = element;
    feed_menu_timeout_out = setTimeout('feed_menu_elt.style.visibility = \'hidden\'', feed_menu_delay);
    clearTimeout(feed_menu_timeout_in);
}

//Pour savoir si une fonction existe
function functionExists(function_name)
{
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Steve Clay
    // +   improved by: Legaev Andrey
    // *     example 1: function_exists('isFinite');
    // *     returns 1: true 
    if (typeof function_name == 'string')
    {
        return (typeof window[function_name] == 'function');
    }
    else
    {
        return (function_name instanceof Function);
    }
}

//Includes synchronously a js file
function include(file)
{
	if (window.document.getElementsByTagName)
	{
		script = window.document.createElement("script");
		script.type = "text/javascript";
		script.src = file;
		document.documentElement.firstChild.appendChild(script);
	}
}

//Affiche le lecteur vidéo avec la bonne URL, largeur et hauteur
playerflowPlayerRequired = false;
function insertMoviePlayer(id)
{
	if (!playerflowPlayerRequired)
	{
		include(PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.js');
		playerflowPlayerRequired = true;
	}
	flowPlayerDisplay(id);
}

//Construit le lecteur à partir du moment où son code a été interprété par l'interpréteur javascript
function flowPlayerDisplay(id)
{
	//Construit et affiche un lecteur vidéo de type flowplayer
	//Si la fonction n'existe pas, on attend qu'elle soit interprétée
	if (!functionExists('flowplayer'))
	{
		setTimeout('flowPlayerDisplay(\'' + id + '\')', 100);
		return;
	}
	//On lance le flowplayer
	flowplayer(id, PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.swf', { 
		    clip: { 
		        url: $(id).href,
		        autoPlay: false 
		    }
	    }
	);
}

var delay = 1000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
var timeout;
var displayed = false;
var previous_bblock;

//Affiche le bloc.
function bb_display_block(divID, field)
{
	var i;
	
	if( timeout )
		clearTimeout(timeout);
	
	var block = document.getElementById('bb-block' + divID + field);
	if( block.style.display == 'none' )
	{
		if( document.getElementById(previous_bblock) )
			document.getElementById(previous_bblock).style.display = 'none';
		block.style.display = 'block';
		displayed = true;
		previous_bblock = 'bb-block' + divID + field;
	}
	else
	{
		block.style.display = 'none';
		displayed = false;
	}
}

//Cache le bloc.
function bb_hide_block(bbfield, field, stop)
{
	var nav = navigator.appName; //Recupère le nom du navigateur
	if( nav == 'Microsoft Internet Explorer' ) // Internet Explorer
	{
		if (window.event.toElement == null) //Hack pour ie... encore une fois!
			return;
	}
	
	if( stop && timeout )
	{	
		clearTimeout(timeout);
	}
	else if( displayed )
	{
		clearTimeout(timeout);
		timeout = setTimeout('bb_display_block(\'' + bbfield + '\',  \'' + field + '\')', delay);
	}	
}