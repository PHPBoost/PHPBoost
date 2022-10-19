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

function insert_wiki_paragraph_1()
{
	var title = document.getElementById('bb_wiki_paragraph-1').value;
	title = title == '' ? title_paragraph : title;

	if (tinymce_editor)
		insertTinyMceContent('-- ' + title + ' --<br />'); //insertion pour tinymce.
	else
		insertbbcode('-- ' + title + ' --\n', '', 'content');
}

function insert_wiki_paragraph_2()
{
	var title = document.getElementById('bb_wiki_paragraph-2').value;
	title = title == '' ? title_paragraph : title;

	if (tinymce_editor)
		insertTinyMceContent('--- ' + title + ' ---<br />'); //insertion pour tinymce.
	else
		insertbbcode('--- ' + title + ' ---\n', '', 'content');
}

function insert_wiki_paragraph_3()
{
	var title = document.getElementById('bb_wiki_paragraph-3').value;
	title = title == '' ? title_paragraph : title;

	if (tinymce_editor)
		insertTinyMceContent('---- ' + title + ' ----<br />'); //insertion pour tinymce.
	else
		insertbbcode('---- ' + title + ' ----\n', '', 'content');
}

function insert_wiki_paragraph_4()
{
	var title = document.getElementById('bb_wiki_paragraph-4').value;
	title = title == '' ? title_paragraph : title;

	if (tinymce_editor)
		insertTinyMceContent('----- ' + title + ' -----<br />'); //insertion pour tinymce.
	else
		insertbbcode('----- ' + title + ' -----\n', '', 'content');
}

function insert_wiki_paragraph_5()
{
	var title = document.getElementById('bb_wiki_paragraph-5').value;
	title = title == '' ? title_paragraph : title;

	if (tinymce_editor)
		insertTinyMceContent('------ ' + title + ' ------<br />'); //insertion pour tinymce.
	else
		insertbbcode('------ ' + title + ' ------\n', '', 'content');
}