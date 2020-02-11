<script type="text/plain" class="language-html line-numbers"><div class="accordion-container basic">
    <div class="accordion-controls">
        <span class="open-all-accordions" aria-label="Open all tabs">&or;</span>
        <span class="close-all-accordions" aria-label="Close all tabs">&and;</i></span>
    </div>
    <nav>
        <ul>
            <li><a href="#" data-accordion data-target="target-01">Pannel 01</a></li>
            <li><a href="#" data-accordion data-target="target-02">Pannel 02</a></li>
            <li><a href="#" data-accordion data-target="target-03">Pannel 03</a></li>
        </ul>
    </nav>
    <div id="target-01" class="accordion accordion-animation">
        <div class="content-panel">
            content of the pannel 01
        </div>
    </div>
    <div id="target-02" class="accordion accordion-animation">
        <div class="content-panel">
            content of the pannel 01 02
        </div>
    </div>
    <div id="target-03" class="accordion accordion-animation">
        <div class="content-panel">
            content of the pannel 01 03
        </div>
    </div>
</div>
</script>
<pre><code class="language-javascript">jQuery('.accordion-container.basic [data-accordion]').multiTabs({pluginType: 'accordion'});</code></pre>
