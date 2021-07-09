<article id="component-modal" class="sandbox-block">
    <header>
        <h2>{@component.modal} {@H|sandbox.pinned.php}</h2>
    </header>
    <div class="content">
        # INCLUDE MODAL_FORM #
    </div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content"><pre class="language-html line-numbers"><code class="language-html">&lt;div class="modal-container">
    &lt;span data-modal="" data-target="target-panel">{@component.item}&lt;/span>
    &lt;div id="target-panel" class="modal modal-animation">
        &lt;div class="close-modal" aria-label="Fermer">&lt;/div>
        &lt;div class="content-panel">
            ...
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
                </div>
            </div>
        </div>

</article>
