<div id="grid" class="sandbox-block">

    <article>
        <header>
            <h5>{@sandbox.layout.grid}</h5>
        </header>
        <div class="content">{@H|sandbox.layout.grid.clue}</div>
        <div class="content">
            <h5>{@sandbox.layout.grid.free}</h5>
            <p>{@sandbox.layout.grid.free.clue}</p>
            <p><code>.cell-inline</code></p>
            <div class="cell-flex cell-inline layout-content-demo">
                <article class="cell" style="width: 140px;"><div class="cell-grid-demo">140px</div></article>
                <article class="cell" style="width: 240px;"><div class="cell-grid-demo">240px</div></article>
                <article class="cell" style="width: 340px;"><div class="cell-grid-demo">340px</div></article>
                <article class="cell" style="width: 440px;"><div class="cell-grid-demo">440px</div></article>
            </div>
            <p>{@H|sandbox.layout.grid.free.forced.clue}</p>
            <p><code>.cell-inline</code></p>
            <div class="cell-flex cell-inline layout-content-demo">
                <article class="cell cell-100"><div class="cell-grid-demo">100%</div></article>
                <article class="cell cell-1-4"><div class="cell-grid-demo">1/4</div></article>
                <article class="cell cell-1-3"><div class="cell-grid-demo">1/3</div></article>
                <article class="cell cell-1-2"><div class="cell-grid-demo">1/2</div></article>
                <article class="cell cell-2-3"><div class="cell-grid-demo">2/3</div></article>
                <article class="cell cell-3-4"><div class="cell-grid-demo">3/4</div></article>
            </div>
        </div>
        <div class="content">
            <h5>{@sandbox.layout.grid.block.columns}</h5>
            <p>{@sandbox.layout.grid.block.columns.clue}</p>
            <p><code class="language-css">.cell-columns-4</code></p>
            <div class="cell-flex cell-columns-4 layout-content-demo">
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
            </div>
            <p><code class="language-css">.cell-columns-3</code></p>
            <div class="cell-flex cell-columns-3 layout-content-demo">
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
            </div>
            <p><code class="language-css">.cell-columns-2</code></p>
            <div class="cell-flex cell-columns-2 layout-content-demo">
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
            </div>
            <p>{@H|sandbox.layout.grid.block.columns.forced.clue}</p>
            <p><code class="language-css">.cell-columns-3</code></p>
            <div class="cell-flex cell-columns-3 layout-content-demo">
                <article class="cell cell-2-3"><div class="cell-grid-demo">2/3</div></article>
                <article class="cell cell-1-3"><div class="cell-grid-demo">1/3</div></article>
                <article class="cell cell-1-4"><div class="cell-grid-demo">1/4</div></article>
                <article class="cell cell-3-4"><div class="cell-grid-demo">3/4</div></article>
                <article class="cell cell-1-2"><div class="cell-grid-demo">1/2</div></article>
                <article class="cell cell-1-2"><div class="cell-grid-demo">1/2</div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
                <article class="cell"><div class="cell-grid-demo"></div></article>
            </div>
        </div>

        <h5>{@sandbox.layout.grid.list}</h5>
        <p>{@sandbox.layout.grid.list.clue}</p>
        <p><code>.cell-row</code></p>
        <div class="cell-flex cell-row layout-content-demo">
            <article class="cell"><div class="cell-grid-demo"></div></article>
            <article class="cell"><div class="cell-grid-demo"></div></article>
            <article class="cell cell-1-3"><div class="cell-grid-demo">1/3</div></article>
            <article class="cell cell-1-4"><div class="cell-grid-demo">1/4</div></article>
        </div>
    </article>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>// {@sandbox.layout.grid.free}
&lt;div class="cell-flex">
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
&lt;/div>
// {@sandbox.layout.grid.block.columns}
&lt;div class="cell-flex cell-columns-[NUMBER]"> // 1 to 4
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
&lt;/div>
// {@sandbox.layout.grid.list}
&lt;div class="cell-flex cell-row">
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
    &lt;article class="cell">... &lt;/article>
&lt;/div>
// {@sandbox.layout.grid.forced}
&lt;div class="cell-flex ...">
    &lt;article class="cell cell-[RATIO]">... &lt;/article>
    // [RATIO] = 1-4 / 1-3 / 1-2 / 2-3 / 3-4
&lt;/div></code></pre>
            </div>
        </div>
    </div>
</div>
