<div id="bbcode-blocks" class="sandbox-block">
    <h2>{@sandbox.bbcode.blocks}</h2>
    <article id="block" class="tpl">
        <header>
            <h5>{@sandbox.bbcode.block}</h5>
        </header>
        <div class="formatter-container formatter-block">{@sandbox.lorem.medium.content}</div>
    </article>
    <article id="fieldset" class="tpl">
        <header>
            <h5>{@sandbox.bbcode.fieldset}</h5>
        </header>
        <fieldset class="formatter-container formatter-fieldset">
            <legend>{@sandbox.bbcode.legend}</legend>
            <div class="formatter-content">
                {@sandbox.lorem.medium.content}
            </div>
        </fieldset>
    </article>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>// {@sandbox.bbcode.block}
&lt;div class="formatter-container formatter-block">Lorem ipsum&lt;/div>
&nbsp;
// {@sandbox.bbcode.fieldset}
&lt;fieldset class="formatter-container formatter-fieldset">
    &lt;legend>{@sandbox.bbcode.legend}&lt;/legend>
    &lt;div class="formatter-content">
        Lorem ipsum ...
    &lt;/div>
&lt;/fieldset></code></pre>
            </div>
        </div>
    </div>
</div>
