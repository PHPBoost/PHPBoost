/**
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 11 09
 * @since       PHPBoost 1.2 - 2005 08 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

function textarea_resize(id, px, type)
{
	var textarea = document.getElementById(id);
	if( type == 'height' )
	{
		var current_height = parseInt(textarea.style.height) ? parseInt(textarea.style.height) : 300;
		var new_height = current_height + px;

		if( new_height > 40 )
			textarea.style.height = new_height + "px";
	}
	else
	{
		var current_width = parseInt(textarea.style.width) ? parseInt(textarea.style.width) : 150;
		var new_width = current_width + px;

		if( new_width > 40 )
			textarea.style.width = new_width + "px";
	}
	return false;
}

// Insert BBCode code in textarea
function simple_insert(open_balise, close_balise, field)
{
	var textarea = document.getElementById(field);
	var scroll = textarea.scrollTop;

	if( close_balise != "" && close_balise != "smile" )
		textarea.value += '[' + open_balise + '][/' + close_balise + ']';
	else if( close_balise == "smile" )
		textarea.value += ' ' + open_balise + ' ';

	textarea.focus();
	textarea.scrollTop = scroll;
	return;
}

// Insert BBCode code in textarea, taking into account the used browser
function insertbbcode(open_balise, close_balise, field)
{
	var area = document.getElementById(field);
	var nav = navigator.appName; //Recupère le nom du navigateur

	area.focus();

	if( nav == 'Microsoft Internet Explorer' ) // Internet Explorer
		ie_sel(area, open_balise, close_balise);
	else if( nav == 'Netscape' || nav == 'Opera' ) //Netscape ou opera
		netscape_sel(area, open_balise, close_balise);
	else //insertion normale (autres navigateurs)
		simple_insert(open_balise, close_balise, field);

	return;
}

// Get the selected part on netscape, add tags around
function netscape_sel(target, open_balise, close_balise)
{
	var sel_length = target.textLength;
	var sel_start = target.selectionStart;
	var sel_end = target.selectionEnd;
	var scroll = target.scrollTop; //Position verticale.

	if( sel_end == 1 || sel_end == 2 )
	{
		sel_end = sel_length;
	}

	var string_start = (target.value).substring(0, sel_start);
	var selection = (target.value).substring(sel_start, sel_end);
	var string_end = (target.value).substring(sel_end, sel_length);

	if( close_balise != "" && selection == "" && close_balise != "smile" )
	{
		target.value = string_start + open_balise + close_balise + string_end;
		target.setSelectionRange(string_start.length + open_balise.length, target.value.length - string_end.length - close_balise.length);
		target.focus();
	}
	else if( close_balise == "smile" )
	{
		target.value = string_start + selection + ' ' + open_balise + ' ' + string_end;
		target.setSelectionRange(string_start.length + open_balise.length + 2, target.value.length - string_end.length);
		target.focus();
	}
	else
	{
		target.value = string_start + open_balise + selection + close_balise + string_end;
		target.setSelectionRange(string_start.length + open_balise.length, target.value.length - string_end.length - close_balise.length);
		target.focus();
	}

	target.scrollTop = scroll; //Remet à la bonne position le textarea.

	return;
}

// Get the selected part on IE, add tags around
function ie_sel(target, open_balise, close_balise)
{
	selText = false;
	var scroll = target.scrollTop; //Position verticale.

	selection = document.selection.createRange().text; // Sélection

	if( close_balise != "" && selection == "" && close_balise != "smile" )
		document.selection.createRange().text = open_balise + close_balise;
	else if( close_balise == "smile" )
		document.selection.createRange().text = selection + open_balise + ' ';
	else
		document.selection.createRange().text = open_balise + selection + close_balise;

	target.scrollTop = scroll; //Remet à la bonne position le textarea.
	selText = '';

	return;
}

// Replace special characters
function url_encode_rewrite(link_name)
{
	link_name = link_name.toLowerCase(link_name);

	var chars_special = new Array(/ /g, /é/g, /è/g, /ê/g, /à/g, /â/g, /ù/g, /ü/g, /û/g, /ï/g, /î/g, /ô/g, /ç/g);
	var chars_replace = new Array("-", "e", "e", "e", "a", "a", "u", "u", "u", "i", "i", "o", "c");
	var nbr_chars = chars_special.length;
	for( var i = 0; i < nbr_chars; i++)
	{
		link_name = link_name.replace(chars_special[i], chars_replace[i]);
	}

	link_name = link_name.replace(/([^a-z0-9]|[\s])/g, '-');
	link_name = link_name.replace(/([-]{2,})/g, '-');
	return link_name.replace(/(^\s*)|(\s*$)/g,'').replace(/(^-)|(-$)/g,'');
}

function bbcode_color(divID, field, type)
{
	var i;
	var br;
	var contents;
	var color = new Array(
	'#000000', '#433026', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
	'#800000', '#FFA500', '#808000', '#008000', '#008080', '#0000FF', '#666699', '#808080',
	'#F04343', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
	'#FFC0CB', '#FFCC00', '#FFFF00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#E3007B', '#FFFFFF');

	contents = '<table><tr>';
	for(i = 0; i < 40; i++)
	{
		br = (i+1) % 8;
		br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
		contents += '<td><a href="" aria-label="' + color[i] + '" style="background-color:' + color[i] + ';" onclick="insertbbcode(\'[' + type + '=' + color[i] + ']\', \'[/' + type + ']\', \'' + field + '\');bb_hide_block(\'' + divID + '\', \'' + field + '\', 0);return false;"></a></td>' + br;
	}
	document.getElementById("bb-"+ type + field).innerHTML = contents + '</tr></table>';
}

function bbcode_table(field)
{
	var cols = document.getElementById('bb-cols' + field).value;
	var lines = document.getElementById('bb-lines' + field).value;
	var head = document.getElementById('bb-head' + field).checked;
	var code = '';

	if( cols >= 0 && lines >= 0 )
	{
		var colspan = cols > 1 ? ' colspan="' + cols + '"' : '';
		var pointor = head ? (59 + colspan.length) : 22;
		code = head ? '[table]\n\t[row]\n\t\t[head' + colspan + '][/head]\n\t[/row]\n' : '[table]\n';

		for(var i = 0; i < lines; i++)
		{
			code += '\t[row]\n';
			for(var j = 0; j < cols; j++)
				code += '\t\t[col][/col]\n';
			code += '\t[/row]\n';
		}
		code += '[/table]';

		insertbbcode(code.substring(0, pointor), code.substring(pointor, code.length), field);
	}
}

function bbcode_list(field)
{
	var elements = document.getElementById('bb_list' + field).value;
	var ordered_list = document.getElementById('bb_ordered_list' + field).checked;
	if( elements <= 0 )
		elements = 1;

	var pointor = ordered_list ? 19 : 11;

	code = '[list' + (ordered_list ? '=ordered' : '') + ']\n';
	for(var j = 0; j < elements; j++)
		code += '\t[*]\n';
	code += '[/list]';
	insertbbcode(code.substring(0, pointor), code.substring(pointor, code.length), field);
}

function bbcode_figure(field)
{
	var figure_img = document.getElementById('bb_figure_img' + field).value,
		picture_alt = document.getElementById('bb_picture_alt' + field).value,
		figure_desc = document.getElementById('bb_figure_desc' + field).value,
		picture_width = document.getElementById('bb_picture_width' + field).value,
		img_width = '';

	if (picture_width != '' && picture_width != null)
		img_width = ' style="max-width:' + picture_width + 'px"';
	else
		img_width = '';

	if (picture_alt != '' && picture_alt != null)
		img_tag = '[img alt="' + picture_alt + '"' + img_width + ']';
	else
		img_tag = '[img' + img_width + ']';

	if(figure_desc != '' && figure_desc != null)
		insertbbcode('[figure=' + figure_desc + ']' + img_tag + figure_img, '[/img][/figure]', field);
	else
		insertbbcode(img_tag + figure_img, '[/img]', field);
}

function bbcode_link(field)
{
	var link_url = document.getElementById('bb_link_url' + field).value,
		link_name = document.getElementById('bb_link_name' + field).value;
	if(link_url != '' && link_url != null)
	{
		if(link_name != '' && link_url != null)
			insertbbcode('[url=' + link_url + ']' + link_name, '[/url]', field);
		else
			insertbbcode('[url=' + link_url + ']', '[/url]', field);
	}

}

function bbcode_quote(field)
{
	var quote_author = document.getElementById('bb_quote_author' + field).value,
		quote_extract = document.getElementById('bb_quote_extract' + field).value;
	if(quote_author != null)
	{
		if(quote_author != '')
			insertbbcode('[quote=' + quote_author + ']' + quote_extract, '[/quote]', field);
		else
			insertbbcode('[quote]' + quote_extract, '[/quote]', field);
	}
}

function bbcode_lightbox(field)
{
	var picture_url = document.getElementById('bb_lightbox' + field).value,
		picture_width = document.getElementById('bb_lightbox_width' + field).value;
	if(picture_url != '' && picture_url != null)
		insertbbcode('[lightbox=' + picture_url + '][img style="max-width: '+picture_width+'px;"]' + picture_url, '[/img][/lightbox]', field);
}

function bbcode_sound(field)
{
	var sound_url = document.getElementById('bb_sound_url' + field).value;
	if(sound_url != '' && sound_url != null)
		insertbbcode('[sound]' + sound_url, '[/sound]', field);
}

function bbcode_anchor(field, prompt_text)
{
	var anchor = prompt(prompt_text, '');
	if(anchor != '' && anchor != null)
		insertbbcode('[anchor=' + url_encode_rewrite(anchor) + ']', '[/anchor]', field);
	else
		insertbbcode('[anchor]', '[/anchor]', field);
}

function bbcode_abbr(field)
{
	var desc = document.getElementById('bb_abbr_desc' + field).value;
	if(desc != '' && desc != null)
		insertbbcode('[abbr=' + desc + ']', '[/abbr]', field);
	else
		insertbbcode('[abbr]', '[/abbr]', field);
}

function bbcode_fieldset(field)
{
	var legend = document.getElementById('bb_legend' + field).value;
	if(legend != '' && legend != null)
		insertbbcode('[fieldset legend="' + legend + '"]', '[/fieldset]', field);
	else
		insertbbcode('[fieldset]', '[/fieldset]', field);
}

function bbcode_mail(field)
{
	var mail_url = document.getElementById('bb_mail_url' + field).value,
		mail_name = document.getElementById('bb_mail_name' + field).value;
	if(mail_url != '' && mail_url != null)
	{
		if(mail_name != '' && mail_name != null)
			insertbbcode('[mail=' + mail_url + ']' + mail_name, '[/mail]', field);
		else
			insertbbcode('[mail=' + mail_url + ']', '[/mail]', field);
	}

}

function bbcode_feed(field)
{
	var feed_module = document.getElementById('bb_module_name' + field).value,
		feed_cat = document.getElementById('bb_feed_category' + field).value,
		feed_number = document.getElementById('bb_feed_number' + field).value;

	if(feed_cat <= 0 || feed_cat == '' || feed_cat == null)
		feed_cat = 0;

	if(feed_number <= 0 || feed_number == '' || feed_number == null)
		feed_number = 1;
	else if(feed_number >= 10)
		feed_number = 10;

	if(feed_module != '' && feed_module != null)
		insertbbcode('[feed cat="' + feed_cat + '" number="' + feed_number + '"]' + feed_module.toLowerCase(), '[/feed]', field);
}

function bbcode_code(field)
{
	var code_name = document.getElementById('bb_code_name' + field).value,
		code_custom_name = document.getElementById('bb_code_custom_name' + field).value,
		code_line = document.getElementById('bb_code_line' + field).checked;

		if(code_custom_name != '' && code_custom_name != null)
		{
			if(code_line)
				insertbbcode('[code=' + code_custom_name + ',0,1]', '[/code]', field);
			else
				insertbbcode('[code=' + code_custom_name + ']', '[/code]', field);
		}
		else if(code_name != '' && code_name != null)
		{
			if(code_line)
				insertbbcode('[code=' + code_name + ',0,1]', '[/code]', field);
			else
				insertbbcode('[code=' + code_name + ']', '[/code]', field);
		}
		else
			insertbbcode('[code]', '[/code]', field);



}
