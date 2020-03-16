<div id="bbcode-blocks" class="sandbox-block">
    <h2>{@bbcode.title.blocks}</h2>
    <article id="block" class="tpl">
        <header>
            <h5>{@bbcode.block}</h5>
        </header>
        <div class="formatter-container formatter-block">{@lorem.medium.content}</div>
    </article>
    <article id="fieldset" class="tpl">
        <header>
            <h5>{@bbcode.fieldset}</h5>
        </header>
        <fieldset class="formatter-container formatter-fieldset">
            <legend>{@bbcode.legend}</legend>
            <div class="formatter-content">
                {@lorem.medium.content}
            </div>
        </fieldset>
    </article>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">// {@bbcode.block}
&lt;div class="formatter-container formatter-block">Lorem ipsum&lt;/div>
<br />
// {@bbcode.fieldset}
&lt;fieldset class="formatter-container formatter-fieldset">
    &lt;legend>{@bbcode.legend}&lt;/legend>
    &lt;div class="formatter-content">
        Lorem ipsum ...
    &lt;/div>
&lt;/fieldset></code></pre>
            </div>
        </div>        
    </div>
</div>
