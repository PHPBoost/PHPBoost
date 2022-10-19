<article id="component-tooltip" class="sandbox-block">
    <header>
        <h5>{@sandbox.component.tooltip}</h5>
    </header>
    <div class="content">
        <p>
			{@H|sandbox.component.tooltip.clue}
		</p>
		<p>
			<span aria-label="{@sandbox.component.tooltip.label.basic}" class="pinned member">Lorem ipsum</span>
			{@sandbox.component.tooltip.eg.basic}
		</p>
		<p>
			<span
                    data-tooltip="{@sandbox.component.tooltip.alt.options}"
                    data-tooltip-class="bigger bgc-full error"
                    aria-label="{@sandbox.component.tooltip.label.basic}"
                    class="pinned moderator">
                Lorem ipsum
            </span>
            {@sandbox.component.tooltip.eg.options}
		</p>
		{@H|sandbox.component.tooltip.options}
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>// {@sandbox.component.tooltip}
&lt;span aria-label="Lorem ipsum....">Tooltip&lt;/span>
&nbsp;
// {@sandbox.component.tooltip.custom}
&lt;span data-tooltip="{@sandbox.component.tooltip.alt.options}" data-tooltip-class="bigger bgc-full error" aria-label="Lorem ipsum....">Tooltip&lt;/span></code></pre>
            </div>
        </div>
    </div>
</article>
