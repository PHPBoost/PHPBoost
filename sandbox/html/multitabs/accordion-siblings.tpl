<pre class="language-html"><code class="language-html">&lt;div class="accordion-container siblings">
    &lt;div class="accordion-controls">
        &lt;span class="open-all-accordions" aria-label="Open all tabs">&or;&lt;/span>
        &lt;span class="close-all-accordions" aria-label="Close all tabs">&and;&lt;/i>&lt;/span>
    &lt;/div>
    &lt;nav>
        &lt;ul>
            &lt;li>&lt;a href="#" data-accordion data-target="target-01">Pannel 01&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#" data-accordion data-target="target-02">Pannel 02&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#" data-accordion data-target="target-03">Pannel 03&lt;/a>&lt;/li>
        &lt;/ul>
    &lt;/nav>
    &lt;div id="target-01" class="accordion accordion-animation">
        &lt;div class="content-panel">
            content of the pannel 01
        &lt;/div>
    &lt;/div>
    &lt;div id="target-02" class="accordion accordion-animation">
        &lt;div class="content-panel">
            content of the pannel 01 02
        &lt;/div>
    &lt;/div>
    &lt;div id="target-03" class="accordion accordion-animation">
        &lt;div class="content-panel">
            content of the pannel 01 03
        &lt;/div>
    &lt;/div>
&lt;/div>
&lt;script>
    jQuery('.accordion-container.siblings [data-accordion]').multiTabs({
        pluginType: 'accordion',
        accordionSiblings : true
    });
&lt;/script>
</code></pre>
