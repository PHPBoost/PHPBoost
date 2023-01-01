/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 01
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
		contents += '<td class="square-cell hide-modal" style="background-color:' + color[i] + ';" onclick="insertbbcode(\'[' + type + '=' + color[i] + ']\', \'[/' + type + ']\', \'' + field + '\');" aria-label="' + color[i] + '"><a href="#"></a></td>' + br;
	}
	document.getElementById("bb-"+ type + field).innerHTML = contents + '</tr></table>';
}

function bbcode_size(field)
{
	var font_size = document.getElementById('bb_font_size' + field).value;
	if(font_size <= 10)
		font_size = 10;
	else if(font_size >= 49)
		font_size = 49;

	insertbbcode('[size=' + font_size + ']', '[/size]', field);
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

function bbcode_custom_div(field)
{
	var cd_id    = document.getElementById('bb_cd_id' + field).value,
		cd_class = document.getElementById('bb_cd_class' + field).value,
		cd_style = document.getElementById('bb_cd_style' + field).value,
		insert_id    = (cd_id != '' && cd_id != null) ? ' id="' + cd_id + '"' : '',
		insert_class = (cd_class != '' && cd_class != null) ? ' class="' + cd_class + '"' : '',
		insert_style = (cd_style != '' && cd_style != null) ? ' style="' + cd_style + '"' : '';

	insertbbcode('[container' + insert_id + insert_class + insert_style + ']', '[/container]', field);
}

function bbcode_fieldset(field)
{
	var legend = document.getElementById('bb_legend' + field).value,
		style = document.getElementById('bb_fieldset_style' + field).value,
		insert_legend = (legend != '' && legend != null) ? ' legend="' + legend + '"' : '',
		insert_style = (style != '' && style != null) ? ' style="' + style + '"' : '';

	insertbbcode('[fieldset' + insert_legend + insert_style + ']', '[/fieldset]', field);
}

function bbcode_abbr(field)
{
	var name = document.getElementById('bb_abbr_name' + field).value,
		desc = document.getElementById('bb_abbr_desc' + field).value,
		insert_name = (name != '' && name != null) ? name : '',
		insert_desc = (desc != '' && desc != null) ? '=' + desc : '';

	insertbbcode('[abbr' + insert_desc + ']' + insert_name, '[/abbr]', field);
}

function bbcode_quote(field)
{
	var author = document.getElementById('bb_quote_author' + field).value,
		quote = document.getElementById('bb_quote_extract' + field).value,
		insert_author = (author != null && author != '') ? '=' + author : '',
		insert_quote = (quote != null && quote != '') ? quote : '';

	insertbbcode('[quote' + insert_author + ']' + insert_quote, '[/quote]', field);
}

function bbcode_link(field)
{
	var link_url = document.getElementById('bb_link_url' + field).value,
		link_name = document.getElementById('bb_link_name' + field).value,
		insert_url = (link_url != '' && link_url != null) ? '=' + link_url : '',
		insert_name = (link_name != '' && link_name != null) ? link_name : '';

	insertbbcode('[url' + insert_url + ']' + insert_name, '[/url]', field);
}

function bbcode_mail(field)
{
	var mail_url = document.getElementById('bb_mail_url' + field).value,
		mail_name = document.getElementById('bb_mail_name' + field).value,
		insert_url = (mail_url != '' && mail_url != null) ? '=' + mail_url : '',
		insert_name = (mail_name != '' && mail_name != null) ? mail_name : '';

	insertbbcode('[mail' + insert_url + ']' + insert_name, '[/mail]', field);
}

function bbcode_wikipedia(field)
{
	var word = document.getElementById('bb_wikipedia_word' + field).value,
		encoded_word = '',
		lang = document.getElementById('bb_wikipedia_lang' + field).value,
		cbLang = document.getElementById('bb_wikipedia_lang_cb' + field),
		insert_lang = cbLang.checked ? ((lang != '' && lang != null) ? ' lang="' + lang + '"' : '') : '',
		insert_word = (word != '' && word != null) ? word : '',
		insert_page = '';

	if (word != '' && word != null) {
		encoded_word = url_encode_rewrite(word);
		if (encoded_word != word) {
			insert_page = ' page="' + encoded_word + '"';
		}
	}

	insertbbcode('[wikipedia' + insert_page + insert_lang + ']' + insert_word, '[/wikipedia]', field);
}

function bbcode_feed(field)
{
	var feed_module = document.getElementById('bb_feed_module_name' + field).value,
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

function bbcode_anchor(field)
{
	var name = document.getElementById('bb_anchor_id' + field).value,
		url = document.getElementById('bb_anchor_url' + field).checked,
		insert_name = (name != '' && name != null) ? '=' + url_encode_rewrite(name) : '',
		insert_url = url ? '[url=/scrollto#' + url_encode_rewrite(name) + '][/url]\n' : '';

	insertbbcode(insert_url + '[anchor' + insert_name + ']', '[/anchor]', field);
}

function bbcode_sound(field)
{
	var sound_url = document.getElementById('bb_sound_url' + field).value,
		insert_sound = (sound_url != '' && sound_url != null) ? sound_url : '';

	insertbbcode('[sound]' + insert_sound, '[/sound]', field);
}

function bbcode_movie(field)
{
	var movie_url = document.getElementById('bb_movie_url' + field).value,
		movie_host = document.getElementById('bb_movie_host' + field).value,
		movie_width = document.getElementById('bb_movie_width' + field).value,
		movie_height = document.getElementById('bb_movie_height' + field).value,
		movie_poster = document.getElementById('bb_movie_poster' + field).value
		insert_url = (movie_url != '' && movie_url != null) ? movie_url : '',
		insert_poster = (movie_poster != '' && movie_poster != null) ? ',' + movie_poster : '';

	if(movie_width <= 100) movie_width = 100;
	if(movie_height <= 100) movie_height = 100;

console.log(movie_host);
	if (movie_host == 'youtube')
		insertbbcode('[youtube='+ movie_width +',' + movie_height + insert_poster + ']' + insert_url, '[/youtube]', field);
	else if (movie_host == 'dailymotion')
		insertbbcode('[dailymotion='+ movie_width +',' + movie_height + insert_poster + ']' + insert_url, '[/dailymotion]', field);
	else if (movie_host == 'vimeo')
		insertbbcode('[vimeo='+ movie_width +',' + movie_height + insert_poster + ']' + insert_url, '[/vimeo]', field);
	else
		insertbbcode('[movie='+ movie_width +',' + movie_height + insert_poster + ']' + insert_url, '[/movie]', field);
}

function bbcode_lightbox(field)
{
	var url = document.getElementById('bb_lightbox' + field).value,
		width = document.getElementById('bb_lightbox_width' + field).value,
		insert_url = (url != '' && url != null) ? url : '',
		insert_width = (width != '' && width != null) ? ' style="width: ' + width + 'px;"' : '';

	insertbbcode('[lightbox=' + insert_url + '][img' + insert_width + ']' + insert_url, '[/img][/lightbox]', field);
}

function bbcode_figure(field)
{
	var url = document.getElementById('bb_figure_img' + field).value,
		alt = document.getElementById('bb_picture_alt' + field).value,
		caption = document.getElementById('bb_figure_desc' + field).value,
		thumb_width = document.getElementById('bb_picture_width' + field).value,
		insert_url = (url != '' && url != null) ? url : '',
		insert_alt = (alt != '' && alt != null) ? ' alt="' + alt + '"' : '',
		insert_width = (thumb_width != '' && thumb_width != null) ? ' style="width: ' + thumb_width + 'px"' : '';
		if(thumb_width < 0) thumb_width = 10;

	if(caption != '' && caption != null)
		insertbbcode('[figure=' + caption + '][img' + insert_alt + insert_width + ']' + insert_url, '[/img][/figure]', field);
	else
		insertbbcode('[img' + insert_alt + insert_width + ']' + insert_url, '[/img]', field);
}

function bbcode_code(field)
{
	var language = document.getElementById('bb_code_name' + field).value,
		file_path = document.getElementById('bb_code_custom_name' + field).value,
		inline = document.getElementById('bb_code_line' + field).checked;

	if(file_path != '' && file_path != null)
	{
		if(inline)
			insertbbcode('[code=' + file_path + ',0,1]', '[/code]', field);
		else
			insertbbcode('[code=' + file_path + ']', '[/code]', field);
	}
	else if(language != '' && language != null)
	{
		if(inline)
			insertbbcode('[code=' + language + ',0,1]', '[/code]', field);
		else
			insertbbcode('[code=' + language + ']', '[/code]', field);
	}
	else
		insertbbcode('[code]', '[/code]', field);
}

function checkbox_revealer()
{
	var checked = jQuery('.checkbox-revealer:checked').length;
	if(checked >= 1)
		jQuery('.checkbox-revealer').closest('.cell').find('.cell-hidden').removeClass('hidden');
	else
		jQuery('.checkbox-revealer').closest('.cell').find('.cell-hidden').addClass('hidden');
}
