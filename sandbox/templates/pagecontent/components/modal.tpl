<article id="component-modal" class="sandbox-block">
    <header>
        <h2>{@sandbox.component.modal} {@H|sandbox.pinned.php}</h2>
    </header>
    <div class="content">
        # INCLUDE MODAL_FORM #
    </div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;div class="modal-container">
    &lt;span class="modal-button --target-panel">{@sandbox.component.item}&lt;/span>
    &lt;div id="target-panel" class="modal">
        &lt;div class="modal-overlay close-modal" aria-label="{@common.close}">&lt;/div>
        &lt;div class="modal-content">
            &lt;span class="error big hide-modal close-modal" aria-label="Fermer">&lt;i class="far fa-circle-xmark" aria-hidden="true">&lt;/i>&lt;/span>
            ...
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
                </div>
            </div>
        </div>

</article>
