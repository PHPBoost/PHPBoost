<?php

require_once(PATH_TO_ROOT.'/bbcode_editor/content_formatting_factory2.class.php');

/**
 * @desc Returns the HTML code of the user editor. It uses the ContentFormattingFactory class, it allows you to write less code lines.
 * @param string $field The name of the HTTP parameter which you will retrieve the value entered by the user.
 * @param string[] $forbidden_tags The list of the tags you don't want to appear in the editor.
 * @return The HTML code of the editor that you can directly display in a template.
 */
function display_editor2($field = 'contents', $forbidden_tags = array())
{
	$content_editor = new ContentFormattingFactory2();
	$editor = $content_editor->get_editor2();
	if (!empty($forbidden_tags) && is_array($forbidden_tags))
	{
		$editor->set_forbidden_tags($forbidden_tags);
	}
	$editor->set_identifier($field);

	return $editor->display();
}

?>