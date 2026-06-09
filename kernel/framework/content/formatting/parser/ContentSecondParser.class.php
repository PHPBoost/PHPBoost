<?php
/**
* This class ensures the real time processing of the content. The major part of the processing is saved in the database to minimize as much as possible the treatment
* when the content is displayed. However, some tags cannot be cached, because we cannot have return to the original code. It's for instance the case of the code tag
* which replaces the code by a lot of html code which formats the code.
* This kind of tag is treated in real time by this class.
* The content you put in that parser must come from a ContentFormattingParser class (BbcodeParser or TinyMCEParser) (it can have been saved in a database between the first parsing and the real time parsing).
* @package     Content
* @subpackage  Formatting\parser
* @copyright   &copy; 2005-2026 PHPBoost
* @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
* @author      Benoit SAUTEL <ben.popeye@phpboost.com>
* @version     PHPBoost 6.1 - last update: 2026 05 19
* @since       PHPBoost 2.0 - 2008 08 10
* @author      Julien BRISWALTER <j1.seth@phpboost.com>
* @author      Arnaud GENET <elenwii@phpboost.com>
* @author      mipel <mipel@phpboost.com>
* @author      janus57 <janus57@janus57.fr>
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
* @author      xela <xela@phpboost.com>
*/

class ContentSecondParser extends AbstractParser
{
    /**
     * Maximal number of characters that can be inserted in the [code] tag.
     */
    const MAX_CODE_LENGTH = 40000;

    /**
     * Maps GeSHi language names to Prism.js language names.
     */
    private static $language_map = [
        'actionscript' => 'actionscript',
        'apache'       => 'apacheconf',
        'asm'          => 'nasm',
        'asp'          => 'aspnet',
        'bash'         => 'bash',
        'c'            => 'c',
        'cpp'          => 'cpp',
        'cpp-qt'       => 'cpp',
        'csharp'       => 'csharp',
        'css'          => 'css',
        'd'            => 'd',
        'delphi'       => 'pascal',
        'fortran'      => 'fortran',
        'go'           => 'go',
        'html'         => 'markup',
        'java'         => 'java',
        'java5'        => 'java',
        'javascript'   => 'javascript',
        'jquery'       => 'javascript',
        'js'           => 'javascript',
        'latex'        => 'latex',
        'lisp'         => 'lisp',
        'lua'          => 'lua',
        'matlab'       => 'matlab',
        'mysql'        => 'sql',
        'ocaml'        => 'ocaml',
        'oracle'       => 'sql',
        'pascal'       => 'pascal',
        'perl'         => 'perl',
        'php'          => 'php',
        'plsql'        => 'plsql',
        'postgresql'   => 'sql',
        'prolog'       => 'prolog',
        'python'       => 'python',
        'rails'        => 'ruby',
        'ruby'         => 'ruby',
        'sql'          => 'sql',
        'text'         => 'plain',
        'vb'           => 'visual-basic',
        'xml'          => 'xml',
        'tpl'          => 'markup',
        'template'     => 'markup',
    ];
    /**
     * Builds a ContentSecondParser object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Parses the content of the parser. The result will be ready to be displayed.
     */
    public function parse()
    {
        /* For PHP8.1 see : https://wiki.php.net/rfc/deprecate_null_to_scalar_internal_arg */
        if ($this->content === null)
            $this->content = '';
        //Relative url parsing
        $this->content = preg_replace_callback('`(src|href)="/([A-Za-z0-9#+-_\./]+)"`suU', [$this, 'callbackrelative_url'], $this->content);

        //Balise code
        if (TextHelper::strpos($this->content, '[[CODE') !== false)
        {
            $this->content = preg_replace_callback('`\[\[CODE(?:=([^,\s]+))?(?:,(0|1)(?:,(0|1))?)?\]\](.+)\[\[/CODE\]\]`suU', [$this, 'callbackhighlight_code'], $this->content);
        }

        //Balise member
        if (stripos($this->content, '[[MEMBER]]') !== false)
        {
            $this->content = preg_replace_callback('`\[\[MEMBER\]\](.+)\[\[/MEMBER\]\]`suU', [$this, 'callback_member_tag'], $this->content);
        }

        // Teaser tag
        if (stripos($this->content, '[[TEASER]]') !== false)
        {
            $this->content = preg_replace_callback('`\[\[TEASER\]\](.+)\[\[/TEASER\]\]`suU', [$this, 'callback_teaser_tag'], $this->content);
        }

        //Balise moderator
        if (stripos($this->content, '[[MODERATOR]]') !== false)
        {
            $this->content = preg_replace_callback('`\[\[MODERATOR\]\](.+)\[\[/MODERATOR\]\]`suU', [$this, 'callback_moderator_tag'], $this->content);
        }

        //Media
        if (TextHelper::strpos($this->content, '[[MEDIA]]') !== false)
        {
            $this->process_media_insertion();
        }

        //Balise latex.
        if (TextHelper::strpos($this->content, '[[MATH]]') !== false)
        {
            $server_config = new ServerConfiguration();
            if ($server_config->has_gd_extension())
            {
                require_once PATH_TO_ROOT . '/kernel/lib/php/mathpublisher/mathpublisher.php';
                $this->content = preg_replace_callback('`\[\[MATH\]\](.+)\[\[/MATH\]\]`suU', [$this, 'math_code'], $this->content);
            }
        }

        $this->parse_feed_tag();
    }

    /**
     * Transforms a PHPBoost HTML content to make it exportable and usable every where in the web.
     * @param string $html Content to transform
     * @return string The exportable content
     */
    public static function export_html_text($html_content)
    {
        return Url::html_convert_root_relative2absolute(FormatingHelper::second_parse($html_content));
    }

    /**
     * Highlights a content in a supported language using Prism.js (client-side).
     * Generates semantic HTML that Prism.js will highlight in the browser.
     * Supported: actionscript, asm, asp, bash, c, cpp, csharp, css, d, delphi, fortran, html,
     * java, javascript, latex, lua, matlab, mysql, pascal, perl, php, python, rails, ruby, sql, text, vb, xml,
     * PHPBoost templates and PHPBoost Bbcode.
     * @param string $contents Content to highlight
     * @param string $language Language name
     * @param bool $line_number Indicate whether or not the line number must be added to the code.
     * @param bool $inline_code Indicate if the code is multi line.
     */
    private static function highlight_code($contents, $language, $line_number, $inline_code)
    {
        $contents = TextHelper::htmlspecialchars_decode($contents);

        $lang_lower = TextHelper::strtolower($language);

        //Bbcode PHPBoost — uses its own inline highlighter
        if ($lang_lower == 'bbcode')
        {
            $bbcode_highlighter = new BbcodeHighlighter();
            $bbcode_highlighter->set_content($contents);
            $bbcode_highlighter->parse($inline_code);
            return $bbcode_highlighter->get_content();
        }

        //Plain text — no syntax highlighting, just escape
        if ($lang_lower == 'plain' || $lang_lower == 'text')
        {
            $plain_code_highlighter = new PlainCodeHighlighter();
            $plain_code_highlighter->set_content($contents);
            $plain_code_highlighter->parse();
            $contents = $plain_code_highlighter->get_content();
            $pre_class = $inline_code ? 'precode precode-inline' : 'precode';
            return '<pre class="' . $pre_class . '"><code>' . TextHelper::htmlspecialchars($contents) . '</code></pre>';
        }

        // Map language name to Prism language identifier
        $prism_lang = isset(self::$language_map[$lang_lower]) ? self::$language_map[$lang_lower] : ($lang_lower !== '' ? $lang_lower : '');

        // Escape HTML entities for safe display
        $escaped = TextHelper::htmlspecialchars($contents);

        if ($prism_lang !== '')
        {
            $pre_class = 'precode';
            if ($inline_code)
                $pre_class .= ' precode-inline';
            if ($line_number)
                $pre_class .= ' line-numbers';

            return '<pre class="' . $pre_class . '"><code class="language-' . TextHelper::htmlspecialchars($prism_lang) . '">' . $escaped . '</code></pre>';
        }
        else
        {
            // No language specified: use PHP's built-in highlighter
            $highlight = highlight_string($contents, true);
            $font_replace = str_replace(['<font ', '</font>'], ['<span ', '</span>'], $highlight);
            return preg_replace('`color="(.*?)"`', 'style="color:$1"', $font_replace);
        }
    }

    /**
     * @static
     * Displays the url correctly when PHPBoost is installed in a sub-folder
     * @return string the relative url
     */
    private function callbackrelative_url($matches)
    {
        return $matches[1] . '="' . Url::to_rel('/' . $matches[2]) . '"';
    }

    /**
     * @static
     * Handler which highlights a string matched by the preg_replace_callback function.
     * @param array $matches The matched contents: 0 => the whole string, 1 => the language, 2 => number count?,
     * 3 => multi line?, 4 => the code to highlight.
     * @return string the colored content
     */
    private function callbackhighlight_code($matches)
    {
        $extension = "";
        $line_number = !empty($matches[2]);
        $inline_code = !empty($matches[3]);

        $content_to_highlight = $matches[4];

        if (TextHelper::strlen($content_to_highlight) > self::MAX_CODE_LENGTH)
        {
            return '<div class="message-helper error">' . LangLoader::get_message('warning.code.too.long.error', 'warning-lang') . '</div>';
        }

        if (!empty($matches[1])) {
            $info = new SplFileInfo($matches[1]);
            $extension = $info->getExtension();
            $extension = TextHelper::strtolower($extension);

            if ($extension == 'js' || $extension == 'jquery')
            {
                $extension = 'javascript';
            }
            else if ($extension && TextHelper::strtolower($extension) != 'tpl')
            {
                // Check if Prism knows this language (via our map or directly)
                $lang_check = TextHelper::strtolower($extension);
                if (!isset(self::$language_map[$lang_check]) && !in_array($lang_check, array_values(self::$language_map)))
                {
                    $extension = 'text';
                }
            }
        }

        if ($extension != "")
        {
            $typecode = TextHelper::strtoupper($extension);
            $title = $matches[1] .' : ';
        }
        else
        {
            $typecode = TextHelper::strtoupper($matches[1]);
            $title = sprintf(LangLoader::get_message('editor.code.language', 'editor-lang'), TextHelper::strtoupper($matches[1]));
        }

        $contents = $this->highlight_code($content_to_highlight, $typecode, $line_number, $inline_code);

        if (!$inline_code && !empty($matches[1]))
        {
            $contents = '<div class="formatter-container formatter-code code-' . $typecode . '"><span class="formatter-title">' . $title . '</span><div class="formatter-content">' . $contents .'</div></div>';
        }
        else if (!$inline_code && empty($matches[1]))
        {
            $contents = '<div class="formatter-container formatter-code"><span class="formatter-title">' . LangLoader::get_message('editor.code.tag', 'editor-lang') . '</span><div class="formatter-content">' . $contents . '</div></div>';
        }

        return $contents;
    }

    /**
     * @static
     * Display the content only if it's a connected user.
     * @param array 1 => the content inside member tag.
     * @return string The content if it's a member or a generic message.
     */
    private function callback_member_tag($matches)
    {
        if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
        {
            return  MessageHelper::display(LangLoader::get_message('warning.bbcode.is.member', 'warning-lang'), MessageHelper::MEMBER_ONLY)->render() . '<div class="member-only">' . $matches[1] . '</div>';
        }
        return MessageHelper::display(LangLoader::get_message('warning.bbcode.member', 'warning-lang'), MessageHelper::MEMBER_ONLY)->render();
    }

    /**
     * @static
     * Display the content only if it's a member user. The begining of the content is shown to visitors
     * @param array 1 => the content inside teaser tag.
     * @return string The content if it's a member or a generic message.
     */
    private function callback_teaser_tag($matches)
    {
        if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
        {
            return  MessageHelper::display(LangLoader::get_message('warning.bbcode.is.teaser', 'warning-lang'), MessageHelper::MEMBER_ONLY)->render() . '<div class="member-only">' . $matches[1] . '</div>';
        }
        return MessageHelper::display(LangLoader::get_message('warning.bbcode.teaser', 'warning-lang'), MessageHelper::MEMBER_ONLY)->render() . '<div class="teaser">' . $matches[1] . '</div>';
    }

    /**
     * @static
     * Display the content only if it's a moderator user.
     * @param array 1 => the content inside moderator tag.
     * @return string The content if it's a moderator or a generic message.
     */
    private function callback_moderator_tag($matches)
    {
        if (AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL))
        {
            return  MessageHelper::display(LangLoader::get_message('warning.bbcode.is.moderator', 'warning-lang'), MessageHelper::MODERATOR_ONLY)->render() . '<div class="moderator-only">' . $matches[1] . '</div>';
        }
        return MessageHelper::display(LangLoader::get_message('warning.bbcode.moderator', 'warning-lang'), MessageHelper::MODERATOR_ONLY)->render();
    }

    /**
     * @static
     * Parses the latex code and replaces it by an image containing the mathematic formula.
     * @param array $matches 0 => the whole tag, 1 => the latex code to parse.
     * @return string The code of the image containing the formula.
     */
    private function math_code($matches)
    {
        $matches[1] = str_replace('<br />', '', $matches[1]);
        $code = mathimage($matches[1], 12, '/images/maths/');
        return $code;
    }

    /**
     * Processes the media insertion it replaces the [[MEDIA]]tag[[/MEDIA]] by the Javascript API correspondig calls.
     */
    private function process_media_insertion()
    {
        // Sound
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_sound_tag'], $this->content);
        // Movie
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_movie_tag'], $this->content);
        // Movie with poster
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+), \'([^\']+)\'\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_movie_tag'], $this->content);
        // Youtube
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertYoutubePlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_youtube_tag'], $this->content);
        // Dailymotion
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertDailymotionPlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_dailymotion_tag'], $this->content);
        // Vimeo
        $this->content = preg_replace_callback('`\[\[MEDIA\]\]insertVimeoPlayer\(\'([^\']+)\', ([0-9]+), ([0-9]+)\);\[\[/MEDIA\]\]`isuU', ['ContentSecondParser', 'process_vimeo_tag'], $this->content);
    }

    /**
     * Inserts the javascript calls for the movie tag.
     * @param $matches The matched elements
     * @return The movie insertion code containing javascrpt calls
     */
    private static function process_movie_tag($matches)
    {
        $sources = '';
        $video_files = explode(',', $matches[1]);

        $poster = '';
        if (!empty($matches[4]))
        {
            $poster_type = new FileType(new File($matches[4]));
            $poster = $poster_type->is_picture() ? ' poster="' . Url::to_rel($matches[4]) . '"' : '';
        }

        foreach ($video_files as $video)
        {
            $video_file = new File($video);
            $type = new FileType($video_file);
            if ($type->is_video())
                $sources .= '<source src="' . Url::to_rel($video) . '" type="video/' . pathinfo($video, PATHINFO_EXTENSION) . '" />';
        }

        return '<div class="media-content" style="width: ' . $matches[2] . 'px; height: ' . $matches[3] . 'px;"><video class="video-player"' . $poster . ' controls>' . $sources . '</video></div>';
    }

    /**
     * Inserts the javascript calls for the sound tag.
     * @param $matches The matched elements
     * @return The movie insertion code containing javascript calls
     */
    private static function process_sound_tag($matches)
    {
        return '<audio class="audio-player" controls><source src="' . Url::to_rel($matches[1]) . '" /></audio>';
    }

    private static function process_youtube_tag($matches)
    {
        preg_match('#(?<=docid=)[a-zA-Z0-9-]+(?=&)|(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=e\/)[^&\n]+(?=\?)|(?<=embed/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $matches[1], $url_matches);
        $video_id = isset($url_matches[0]) ? $url_matches[0] : '';
        return $video_id ? '<div class="media-content" style="width: ' . $matches[2] . 'px; height: ' . $matches[3] . 'px;"><iframe class="youtube-player" type="text/html"  src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe></div>' : '';
    }

    private static function process_dailymotion_tag($matches)
    {
        preg_match('#(?<=embed/video/)[^&\n]+|(?<=video/)[^&\n]+#', $matches[1], $url_matches);
        $video_id = isset($url_matches[0]) ? $url_matches[0] : '';
        return $video_id ? '<div class="media-content" style="width: ' . $matches[2] . 'px; height: ' . $matches[3] . 'px;"><iframe class="dailymotion-player" type="text/html" src="https://www.dailymotion.com/embed/video/' . $video_id . '" frameborder="0" allowfullscreen></iframe></div>' : '';
    }

    private static function process_vimeo_tag($matches)
    {
        preg_match('#(?<=vimeo.com/)(?<=video/)?[^&\n]+#', $matches[1], $url_matches);
        $video_id = isset($url_matches[0]) ? $url_matches[0] : '';
        return $video_id ? '<div class="media-content" style="width: ' . $matches[2] . 'px; height: ' . $matches[3] . 'px;"><iframe class="vimeo-player" type="text/html" src="https://player.vimeo.com/' . (!preg_match('#video/#', $video_id) ? 'video/' : '') . $video_id . '" frameborder="0" allowfullscreen></iframe></div>' : '';
    }

    private function parse_feed_tag()
    {
        $this->content = preg_replace_callback('`\[\[FEED((?: [a-z]+="[^"]+")*)\]\]([a-z]+)\[\[/FEED\]\]`uU', [self::class, 'inject_feed'], $this->content);
    }

    private static function inject_feed(array $matches)
    {
        $module = $matches[2];
        $args = parent::parse_tag_args($matches[1], ['name', 'cat', 'number']);
        $name = !empty($args['name']) ? $args['name'] : Feed::DEFAULT_FEED_NAME;
        $cat = !empty($args['cat']) ? $args['cat'] : 0;
        $tpl = false;
        $number = !empty($args['number']) ? $args['number'] : 10;

        $result = '';

        try
        {
            $result = Feed::get_parsed($module, $name, $cat, $tpl, $number);
        }
        catch (Exception $e)
        {
        }

        if (!empty($result))
        {
            return $result;
        }
        else
        {
            $error = StringVars::replace_vars(LangLoader::get_message('warning.feed.tag.error', 'warning-lang'), ['module' => $module]);
            return '<div class="message-helper error">' . $error . '</div>';
        }
    }
}
?>