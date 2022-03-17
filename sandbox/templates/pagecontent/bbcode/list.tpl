<article id="bbcode-lists" class="sandbox-block">
    <header>
        <h2>{@sandbox.bbcode.lists}</h2>
    </header>
    <div class="cell-flex cell-columns-2">
        <div class="cell">
            <ul class="formatter-ul">
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
                <li class="formatter-li">
                    {@sandbox.bbcode.item}
                    <ul class="formatter-ul">
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                    </ul>
                </li>
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
            </ul>
        </div>
        <div class="cell">
            <ol class="formatter-ol">
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
                <li class="formatter-li">
                    {@sandbox.bbcode.item}
                    <ol class="formatter-ol">
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                        <li class="formatter-li">{@sandbox.bbcode.item}</li>
                    </ol>
                </li>
                <li class="formatter-li">{@sandbox.bbcode.item}</li>
            </ol>
        </div>
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;ul class="formatter-ul">
    &lt;li class="formatter-li">
        {@sandbox.bbcode.item}
        &lt;ul class="formatter-ul">
            &lt;li class="formatter-li">{@sandbox.bbcode.item}&lt;/li>
        &lt;/ul>
    &lt;/li>
    &lt;li class="formatter-li">{@sandbox.bbcode.item}&lt;/li>
&lt;/ul>
&nbsp;
&lt;ol class="formatter-ol">
    &lt;li class="formatter-li">
        {@sandbox.bbcode.item}
        &lt;ol class="formatter-ol">
            &lt;li class="formatter-li">{@sandbox.bbcode.item}&lt;/li>
        &lt;/ol>
    &lt;/li>
    &lt;li class="formatter-li">{@sandbox.bbcode.item}&lt;/li>
&lt;/ol></code></pre>
            </div>
        </div>
    </div>
</article>
