<article id="font-awesome" class="sandbox-block">
    <i class="fab fa-fort-awesome-alt fa-3x"></i> <i class="fab fa-font-awesome-flag fa-3x"></i>
    <header>
        <h2>{@H|icons.fa}</h2>
    </header>
        <p>{@H|icons.fa.howto.explain}</p>
        <p>{@H|icons.fa.howto.update}</p>
        <h3>{@H|icons.fa.sample}</h3>
    <div>
        <table class="table">
            <caption>{@H|icons.fa.social}</caption>
            <thead>
                <tr>
                    <th>{@H|icons.fa.icon}</th>
                    <th>{@H|icons.fa.name}</th>
                    <th>{@H|icons.fa.code}</th>
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
            <caption>{@H|icons.fa.screen}</caption>
            <thead>
                <tr>
                    <th>{@H|icons.fa.icon}</th>
                    <th>{@H|icons.fa.name}</th>
                    <th>{@H|icons.fa.code}</th>
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
    <footer>{@H|icons.fa.list}<a class="pinned bgc-full moderator" href="https://fontawesome.com/icons?d=listing&m=free"><i class="fa fa-share"></i> Cheatsheet Font-Awesome</a></footer>
</article>

<article>
    <header>
        <h3>{@H|icons.fa.howto}</h3>
    </header>
    <div class="content">
        <h4>{@H|icons.fa.howto.html}</h4>
        <p>{@H|icons.fa.howto.html.class}</p>
        <pre class="language-html"><code class="language-html">&lt;i class="far fa-edit">&lt;/i> Edition</code></pre>
        <p>{@H|icons.fa.howto.html.class.result.i}<i class="far fa-edit"></i> Edition</p>
        <pre class="language-html"><code class="language-html">&lt;a class="fa fa-globe" href="https://www.phpboost.com">PHPBoost&lt;/a></code></pre>
        <p>{@H|icons.fa.howto.html.class.result.a}<a href="https://www.phpboost.com"><i class="fa fa-globe"></i>PHPBoost</a></p>
        <p>{@H|icons.fa.howto.html.class.result.all}</p>

        <h4>{@H|icons.fa.howto.css}</h4>
        <p>{@H|icons.fa.howto.css.class}</p>
        <div class="formatter-container formatter-code">
            <span id="copy-code-1" class="copy-code" aria-label="${LangLoader::get_message('tag_copytoclipboard', 'editor-common')}" onclick="copy_code_clipboard(1)"><i class="far fa-copy"></i></span>
            <span class="formatter-title">{@H|icons.fa.howto.css.css.code}</span>
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
            <span class="formatter-title">{@H|icons.fa.howto.css.html.code}</span>
            <div id="copy-code-2-content" class="formatter-content copy-code-content">
                <pre class="language-html"><code class="language-html">&lt;div class="message-helper bgc success">{@component.message.success}&lt;/div></code></pre>
            </div>
        </div>
        <div class="message-helper bgc success">{@component.message.success}</div>

        <br />
        <h4>{@H|icons.fa.howto.bbcode}</h4>
        <p>{@H|icons.fa.howto.bbcode.some.icons} <i class="fab fa-font-awesome-flag"></i></p>
        <p>{@H|icons.fa.howto.bbcode.tag}</p>
        <p>{@H|icons.fa.howto.bbcode.icon.name}</p>
        <p>{@H|icons.fa.howto.bbcode.icon.test} <i class="fa fa-cubes"></i></p>
        <p>{@H|icons.fa.howto.bbcode.icon.variants}<a class="pinned bgc-full link-color" href="https://www.phpboost.com/wiki/la-bibliotheque-font-awesome"><i class="fa fa-share"></i> {@phpboost.doc}</a>.</p>

        <br />
        <h4>{@H|icons.fa.howto.variants}</h4>
        <p>{@H|icons.fa.howto.variants.explain}</p>
        <p>{@H|icons.fa.howto.variants.list}<a class="pinned bgc moderator" href="https://fortawesome.github.io/Font-Awesome/examples/" target="_blank" rel="noopener noreferer"><i class="fa fa-share"></i> Font-Awesome/examples</a></p>

        <pre class="language-html"><code class="language-html">&lt;i class="fa fa-spinner fa-spin fa-2x">&lt;/i></code></pre>

        <p>{@H|icons.fa.howto.variants.spinner}<i class="fa fa-spinner fa-spin fa-2x"></i></p>
    </div>
</article>
