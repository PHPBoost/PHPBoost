<article id="menu-tabs" class="sandbox-block">
    <header>
        <h2>{@sandbox.menu.tabs.title} {@H|sandbox.pinned.php}</h2>
    </header>
    <div class="content">
        # INCLUDE TABS_FORM #
    </div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;div id="lorem" class="tabs-container"> // 4 options to display tabs menu : tabs-top (default) | tabs-left | tabs-right | tabs-bottom
    &lt;nav class="tabs-nav">
        &lt;ul class="tabs-items">
            &lt;li>&lt;a href="#" class="tab-item --target_01_panel">...&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#" class="tab-item --target_02_panel">...&lt;/a>&lt;/li>
        &lt;/ul>
    &lt;/nav>
    &lt;div class="tabs-wrapper">
        &lt;div id="target_01_panel" class="tab-content">...&lt;/div>
        &lt;div id="target_02_panel" class="tab-content">...&lt;/div>
    &lt;/div>
&lt;/div></code></pre>
                </div>
            </div>
        </div>

</article>
