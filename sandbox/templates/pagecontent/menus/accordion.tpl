<article id="menu-accordion" class="sandbox-block">
    <header>
        <h2>{@sandbox.menu.accordion.title} {@H|sandbox.pinned.php}</h2>
    </header>
    <div class="content">
        # INCLUDE ACCORDION_FORM #
    </div>

    <div>{@H|sandbox.menu.accordion.options}</div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;div class="accordion-container basic"> // basic|siblings
    &lt;div class="accordion-controls">
        &lt;span class="open-all-accordions" aria-label="{@sandbox.menu.accordion.open}">&lt;i class="fa fa-fw fa-chevron-down">&lt;/i>&lt;/span>
        &lt;span class="close-all-accordions" aria-label="{@sandbox.menu.accordion.close}">&lt;i class="fa fa-fw fa-chevron-up">&lt;/i>&lt;/span>
    &lt;/div>
    &lt;nav id="lorem" class="accordion-nav">
        &lt;ul>
            &lt;li>&lt;a href="#" data-accordion="" data-target="target_01_panel">...&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#" data-accordion="" data-target="target_02_panel">...&lt;/a>&lt;/li>
        &lt;/ul>
    &lt;/nav>
    &lt;div id="target_01_panel" class="accordion accordion-animation">
        &lt;div class="content-panel">...&lt;/div>
    &lt;/div>
    &lt;div id="target_02_panel" class="accordion accordion-animation">
        &lt;div class="content-panel">...&lt;/div>
    &lt;/div>
&lt;/div></code></pre>
                </div>
            </div>
        </div>

</article>
