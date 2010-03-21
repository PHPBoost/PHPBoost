<?php

class ContentFormattingFactory2 extends ContentFormattingFactory
{
    function ContentFormattingFactory2($language_type = false)
    {
		parent::ContentFormattingFactory($language_type);
    }

    /**
     * @desc Returns an editor object which will display the editor corresponding to the language you chose.
     * @return ContentParser The editor to use.
     */
    function get_editor2()
    {
        switch ($this->language_type)
        {
            case BBCODE_LANGUAGE:
                require_once('bbcode_editor2.class.php');
                return new BBcode_editor2();
            case TINYMCE_LANGUAGE:
                import('content/editor/tinymce_editor');
                return new TinyMCEEditor();
            default:
                if ($this->get_user_editor() == TINYMCE_LANGUAGE)
                {
                    import('content/editor/tinymce_editor');
                    return new TinyMCEEditor();
                }
                else
                {
					require_once('bbcode_editor2.class.php');
                    return new BBcode_editor2();
                }
        }
    }

}

?>
