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
    &lt;span data-modal="" data-target="target-panel">{@sandbox.component.item}&lt;/span>
    &lt;div id="target-panel" class="modal modal-animation">
        &lt;div class="close-modal" aria-label="{@common.close}">&lt;/div>
        &lt;div class="content-panel">
            &lt;div class="align-right">&lt;a href="#" class="error big hide-modal" aria-label="{@common.close}">&lt;i class="far fa-circle-xmark" aria-hidden="true">&lt;/i>&lt;/a>&lt;/div>
            ...
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
                </div>
            </div>
        </div>

</article>
