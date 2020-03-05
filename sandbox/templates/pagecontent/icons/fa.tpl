<article class="content">
    <header>
        <h2>{@icons.fa}</h2>
    </header>
        <p>{@icons.howto.explain}</p>
        <p>{@icons.howto.update}</p>
        <h3>{@icons.sample}</h3>
    <div>
        <table class="table">
            <caption>{@icons.social}</caption>
            <thead>
                <tr>
                    <th>{@icons.icon}</th>
                    <th>{@icons.name}</th>
                    <th>{@icons.code}</th>
                </tr>
            </thead>
            <tbody>
                # START social #
                    <tr>
                        <td><i class="{social.PREFIX} fa-{social.FA} fa-lg"></i></td>
                        <td><span>{social.FA}</span></td>
                        <td>{social.CODE}</td>
                    </tr>
                # END web #
            </tbody>
        </table>

        <table class="table">
            <caption>{@icons.screen}</caption>
            <thead>
                <tr>
                    <th>{@icons.icon}</th>
                    <th>{@icons.name}</th>
                    <th>{@icons.code}</th>
                </tr>
            </thead>
            <tbody>
                # START responsive #
                <tr>
                    <td><i class="{responsive.PREFIX} fa-{responsive.FA} fa-lg"></i></td>
                    <td><span>{responsive.FA}</span></td>
                    <td>{responsive.CODE}</td>
                </tr>
                # END responsive #
            </tbody>
        </table>
    </div>
    <footer>{@icons.list}<a class="pinned bgc-full moderator" href="https://fontawesome.com/icons?d=listing&m=free"><i class="fa fa-share"></i> Cheatsheet Font-Awesome</a></footer>
</article>

<article>
    <header>
        <h3>{@icons.howto}</h3>
    </header>
    <div class="content">
        <h4>{@icons.howto.html}</h4>
        <p>{@icons.howto.html.class}</p>
        <pre class="language-html"><code class="language-html">&lt;i class="far fa-edit">&lt;/i> Edition</code></pre>
        <p>{@icons.howto.html.class.result.i}<i class="far fa-edit"></i> Edition</p>
        <pre class="language-html"><code class="language-html">&lt;a class="fa fa-globe" href="https://www.phpboost.com">PHPBoost&lt;/a></code></pre>
        <p>{@icons.howto.html.class.result.a}<a href="https://www.phpboost.com"><i class="fa fa-globe"></i>PHPBoost</a></p>
        <p>{@icons.howto.html.class.result.all}</p>

        <h4>{@icons.howto.css}</h4>
        <p>{@icons.howto.css.class}</p>
        <div class="formatter-container formatter-code">
            <span id="copy-code-1" class="copy-code" aria-label="${LangLoader::get_message('tag_copytoclipboard', 'editor-common')}" onclick="copy_code_clipboard(1)"><i class="far fa-copy"></i></span>
            <span class="formatter-title">{@icons.howto.css.css.code}</span>
            <div id="copy-code-1-content" class="formatter-content copy-code-content">
<pre class="language-css line-numbers" data-line="3-5"><code class="language-css">.success { ... }
.success::before {
    content: "\f00c";
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
}</code></pre>
            </div>
        </div>

        <div class="formatter-container formatter-code">
            <span id="copy-code-2" class="copy-code" aria-label="${LangLoader::get_message('tag_copytoclipboard', 'editor-common')}" onclick="copy_code_clipboard(2)"><i class="far fa-copy"></i></span>
            <span class="formatter-title">{@icons.howto.css.html.code}</span>
            <div id="copy-code-2-content" class="formatter-content copy-code-content">
                <pre class="language-html"><code class="language-html">&lt;div class="message-helper bgc success">{@fwkboost.message.success}&lt;/div></code></pre>
            </div>
        </div>
        <div class="message-helper bgc success">{@fwkboost.message.success}</div>

        <br />
        <h4>{@icons.howto.bbcode}</h4>
        <p>{@icons.howto.bbcode.some.icons} <i class="fab fa-font-awesome-flag"></i></p>
        <p>{@icons.howto.bbcode.tag}</p>
        <p>{@icons.howto.bbcode.icon.name}</p>
        <p>{@H|icons.howto.bbcode.icon.test} <i class="fa fa-cubes"></i></p>
        <p>{@H|icons.howto.bbcode.icon.variants}<a class="pinned bgc-full link-color" href="https://www.phpboost.com/wiki/la-bibliotheque-font-awesome"><i class="fa fa-share"></i> {@phpboost.doc}</a>.</p>

        <br />
        <h4>{@icons.howto.variants}</h4>
        <p>{@icons.howto.variants.explain}</p>
        <p>{@icons.howto.variants.list}<a class="pinned bgc-full moderator" href="https://fortawesome.github.io/Font-Awesome/examples/"><i class="fa fa-share"></i> Font-Awesome/examples</a></p>

        <pre class="language-html"><code class="language-html">&lt;i class="fa fa-spinner fa-spin fa-2x">&lt;/i></code></pre>

        <p>{@icons.howto.variants.spinner}<i class="fa fa-spinner fa-spin fa-2x"></i></p>
    </div>
</article>
