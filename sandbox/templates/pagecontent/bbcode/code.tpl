<div id="bbcode-code" class="sandbox-block">
    <h2>{@sandbox.bbcode.blocs.code}</h2>
    <article id="quote-code" class="sandbox-block">
        <header>
            <h5>{@sandbox.bbcode.quote}</h5>
        </header>
        <blockquote class="formatter-container formatter-blockquote">
            <span class="formatter-title title-perso">{@sandbox.bbcode.quote} : Lorem Ipsum</span>
            <div class="formatter-content">
                {@sandbox.lorem.medium.content}
            </div>
        </blockquote>
    </article>
    <article id="hidden-code" class="sandbox-block">
        <header>
            <h5>{@sandbox.bbcode.hidden}</h5>
        </header>
        <div class="formatter-container formatter-hide no-js">
            <span class="formatter-title">{@sandbox.bbcode.hidden} :</span>
            <div class="formatter-content">
                {@sandbox.lorem.medium.content}
            </div>
        </div>
    </article>
    <article id="php-code" class="sandbox-block">
        <header>
            <h5>{@sandbox.bbcode.code.php}</h5>
        </header>
        <p>{@sandbox.bbcode.code.with.html}</p>
        <div class="formatter-container code-php">
            <span class="formatter-title title-perso">{@sandbox.bbcode.code.php} : CategoriesCache.class.php</span>
            <div class="formatter-content formatter-code">
                <div class="no-style">
<pre class="precode"><code>&lt;?php
abstract class CategoriesCache implements CacheData
{
    /**
     * @var string the module identifier
     */
    protected static $module_id;
    protected static $module;
    protected static $module_category;
    protected $categories;

    public static function __static()
    {
        $module_id = Environment::get_running_module_name();
        if (!in_array($module_id, array('admin', 'kernel', 'user')))
        {
            self::$module_id       = $module_id;
            self::$module          = ModulesManager::get_module(self::$module_id);
            $category_class        = TextHelper::ucfirst(self::$module_id) . 'Category';
            self::$module_category = (class_exists($category_class) && is_subclass_of($category_class, 'Category') ? $category_class : '');
        }
    }
}
?></code></pre>
                </div>
            </div>
        </div>
    </article>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>// {@sandbox.bbcode.quote}
&lt;blockquote class="formatter-container formatter-blockquote">
    &lt;span class="formatter-title">John Doe&lt;/span>
    &lt;div class="formatter-content">
        Lorem ipsum
    &lt;/div>
&lt;/blockquote>
&nbsp;
// {@sandbox.bbcode.hidden}
&lt;div class="formatter-container formatter-hide no-js">
    &lt;span class="formatter-title title-perso">Lorem ipsum&lt;/span>
    &lt;div class="formatter-content">
        Lorem ipsum
    &lt;/div>
&lt;/div>
&nbsp;     
// {@sandbox.bbcode.code.php} (php)
&lt;div class="formatter-container formatter-code">
    &lt;span class="formatter-title">Code : PHP&lt;/span> <em>// file.ext</em>
    &lt;div class="formatter-content formatter-code">
        &lt;div class="no-style">
&lt;pre class="precode">&lt;code> // class="language-" => php, html, css, bbcode, txt ...
    ...[code /]...&lt;/code>&lt;/pre>
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
            </div>
        </div>
    </div>
</div>
