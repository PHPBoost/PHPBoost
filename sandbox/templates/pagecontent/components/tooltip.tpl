<article id="component-tooltip" class="sandbox-block">
    <header>
        <h5>{@component.tooltip}</h5>
    </header>
    <div class="content">
        <p>
			{@H|component.tooltip.desc}
		</p>
		<p>
			<span aria-label="{@component.tooltip.label.basic}" class="pinned member">Lorem ipsum</span>
			{@component.tooltip.eg.basic}
		</p>
		<p>
			<span
		        data-tooltip="{@component.tooltip.alt.options}"
		        data-tooltip-class="bigger bgc-full error"
		        aria-label="{@component.tooltip.label.basic}"
				class="pinned moderator">Lorem ipsum</span>
            {@component.tooltip.eg.options}
		</p>
		{@H|component.tooltip.options}
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content"><pre class="language-html line-numbers"><code class="language-html">// {@component.tooltip}
&lt;span aria-label="Lorem ipsum....">Tooltip&lt;/span>
<br />
// {@component.tooltip.custom}
&lt;span data-tooltip="{@component.tooltip.alt.options}" data-tooltip-class="bigger bgc-full error" aria-label="Lorem ipsum....">Tooltip&lt;/span></code></pre>
            </div>
        </div>        
    </div>
</article>
