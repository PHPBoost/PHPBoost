function insert_wiki_link()
{
	var link_name = document.getElementById('bb_wiki_link').value;
	if( link_name == "" )
	{
		alert(enter_text);
		return false;
	}

	if (tinymce_editor)
		insertTinyMceContent('[link=' + url_encode_rewrite(link_name) + '][/link]'); //insertion pour tinymce.
	else
		insertbbcode('[link=' + url_encode_rewrite(link_name) + ']', '[/link]', 'content');
}

function insert_wiki_paragraph(level)
{
	var string = '-';
	if( level > 5 || level < 1 )
		return false;
	for( var i = 1; i <= level; i++)
		string += "-";
	insert_wiki_paragraph_title('paragraph', string, string, 'content');
}

//Insertion dans le champs.
function simple_insert_wiki_paragraph(id, open_balise, close_balise, field)
{
	var textarea = document.getElementById(field);
	var scroll = textarea.scrollTop;

	var title = prompt(enter_paragraph_name, title_paragraph);

	if (tinymce_editor) {
		insertTinyMceContent('<br/>' + open_balise + ' ' + title + ' ' + close_balise + '<br/>'); //insertion pour tinymce.
	} else {
		if( close_balise != "" && title != null && title != enter_paragraph_name )
			textarea.value += "\n" + open_balise + " " + title + " " + close_balise + "\n";

		textarea.focus();
		textarea.scrollTop = scroll;
	}
	return;
}

//Récupération de la sélection sur netscape, ajout des balises autour.
function netscape_sel_wiki_paragraph(id, target, open_balise, close_balise)
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

	var title = selection != "" ? selection : prompt(enter_paragraph_name, title_paragraph);

	if (tinymce_editor) {
		insertTinyMceContent('<br/>' + open_balise + ' ' + title + ' ' + close_balise + '<br/>'); //insertion pour tinymce.
	} else {
		if( title != null )
		{
			if( close_balise != "" && selection == "" )
			{
				target.value = string_start + "\n" + open_balise + " " + title + " " + close_balise + "\n" + string_end;
				target.setSelectionRange(string_start.length + (open_balise.length + 2), target.value.length - string_end.length - (close_balise.length+2));
				target.focus();
			}
			else
			{
				target.value = string_start + "\n" + open_balise + ' ' + selection + ' ' + close_balise + "\n" + string_end;
				target.setSelectionRange(string_start.length + (open_balise.length + 2), target.value.length - string_end.length - (close_balise.length+2));
				target.focus();
			}
		}

		target.scrollTop = scroll; //Remet à la bonne position le textarea.
	}

	return;
}

//Récupération de la sélection sur IE, ajout des balises autour.
function ie_sel_wiki_paragraph(id, target, open_balise, close_balise)
{
	selText = false;
	var scroll = target.scrollTop; //Position verticale.

	selection = document.selection.createRange().text; // Sélection

	var title = selection != "" ? selection : prompt(enter_paragraph_name, title_paragraph);

	if (tinymce_editor) {
		insertTinyMceContent('<br/>' + open_balise + ' ' + title + ' ' + close_balise + '<br/>'); //insertion pour tinymce.
	} else {
		if( title != null )
		{
			if( close_balise != "" && selection == "" )
				document.selection.createRange().text = "\n" + open_balise + " " + title + " " + close_balise + "\n";
			else
				document.selection.createRange().text = "\n" + open_balise + ' ' + selection + ' ' + close_balise + "\n";
		}

		target.scrollTop = scroll; //Remet à la bonne position le textarea.
		selText = '';
	}

	return;
}

//Fonction d'insertion du BBcode dans le champs, tient compte du navigateur utilisé.
function insert_wiki_paragraph_title(id, open_balise, close_balise, field)
{
	var area = document.getElementById(field);
	var nav = navigator.appName; //Recupère le nom du navigateur

	area.focus();

	if( nav == 'Microsoft Internet Explorer' ) // Internet Explorer
		ie_sel_wiki_paragraph(id, area, open_balise, close_balise);
	else if( nav == 'Netscape' || nav == 'Opera' ) //Netscape ou opera
		netscape_sel_wiki_paragraph(id, area, open_balise, close_balise);
	else //insertion normale (autres navigateurs)
		simple_insert_wiki_paragraph(id, open_balise, close_balise, field);

	return;
}
