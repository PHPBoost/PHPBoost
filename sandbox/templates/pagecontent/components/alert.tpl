<article id="component-alerts" class="sandbox-block">
    <header>
        <h2>{@sandbox.component.alert.messages} {@H|sandbox.pinned.bbcode}</h2>
    </header>
    # START messages # # INCLUDE messages.VIEW # # END messages #
    # INCLUDE FLOATING_MESSAGES # # INCLUDE FLOATING_SUCCESS # # INCLUDE FLOATING_NOTICE # # INCLUDE FLOATING_WARNING # # INCLUDE FLOATING_ERROR #
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;div id="msg-helper-{ID}" class="message-helper bgc success">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc notice">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc warning">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc error">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc question">Lorem ipsum&lt;div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc member">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc moderator">Lorem ipsum&lt;/div>
&lt;div id="msg-helper-{ID}" class="message-helper bgc administrator">Lorem ipsum&lt;/div>
</code></pre>
            </div>
        </div>
    </div>
</article>
