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
            <div class="layout-content-demo">
                <div class="cell-flex cell-inline">
                    <article class="cell" style="width: 140px;"><div class="cell-grid-demo">width: 140px</div></article>
                    <article class="cell" style="width: 240px;"><div class="cell-grid-demo">width: 240px</div></article>
                    <article class="cell" style="width: 340px;"><div class="cell-grid-demo">width: 340px</div></article>
                    <article class="cell" style="width: 140px;"><div class="cell-grid-demo">width: 140px</div></article>
                    <article class="cell" style="width: 140px;"><div class="cell-grid-demo">width: 140px</div></article>
                    <article class="cell" style="width: 440px;"><div class="cell-grid-demo">width: 440px</div></article>
                </div>
            </div>
            <!-- Source code -->
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex cell-inline">
    &lt;article style="width: 140px">... &lt;/article>
    &lt;article style="width: 240px">... &lt;/article>
    &lt;article style="width: 340px">... &lt;/article>
    &lt;article style="width: 140px">... &lt;/article>
    &lt;article style="width: 140px">... &lt;/article>
    &lt;article style="width: 440px">... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <h5>{@sandbox.layout.grid.mosaic}</h5>
            <p>{@sandbox.layout.grid.mosaic.clue}</p>
            <p><code class="language-css">.cell-columns-4</code></p>
            <div class="layout-content-demo">
                <div class="cell-flex cell-columns-4">
                    <article><div class="cell-grid-demo"></div></article>
                    <article><div class="cell-grid-demo"></div></article>
                    <article><div class="cell-grid-demo"></div></article>
                    <article><div class="cell-grid-demo"></div></article>
                </div>
            </div>
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex cell-columns-4">
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
            <p><code class="language-css">.cell-columns-3</code></p>
            <div class="layout-content-demo">
                <div class="cell-flex cell-columns-3">
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                </div>
            </div>
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex cell-columns-3">
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
            <p><code class="language-css">.cell-columns-2</code></p>
            <div class="layout-content-demo">
                <div class="cell-flex cell-columns-2">
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                    <article class="cell"><div class="cell-grid-demo"></div></article>
                </div>
            </div>
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex cell-columns-2">
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <h5>{@sandbox.layout.grid.list}</h5>
            <p>{@sandbox.layout.grid.list.clue}</p>
            <p><code>.cell-row</code></p>
            <div class="layout-content-demo">
                <div class="cell-flex cell-row">
                    <article><div class="cell-grid-demo"></div></article>
                    <article><div class="cell-grid-demo"></div></article>
                    <article style="width: 25%"><div class="cell-grid-demo">width: 25%</div></article>
                    <article style="width: 60%"><div class="cell-grid-demo">width: 60%</div></article>
                </div>
            </div>
            <!-- Source code -->
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex cell-inline">
    &lt;article>... &lt;/article>
    &lt;article>... &lt;/article>
    &lt;article style="width: 25%">... &lt;/article>
    &lt;article style="width: 60%">... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <h5>{@sandbox.layout.grid.forced}</h5>
            <p>{@H|sandbox.layout.grid.forced.clue}</p>
            <div class="layout-content-demo">
                <div class="cell-flex cell-inline">
                    <article class="cell-100"><div class="cell-grid-demo">.cell-100</div></article>
                    <article class="cell-1-2"><div class="cell-grid-demo">.cell-1-2</div></article>
                    <article class="cell-1-2"><div class="cell-grid-demo">.cell-1-2</div></article>
                    <article class="cell-1-3"><div class="cell-grid-demo">.cell-1-3</div></article>
                    <article class="cell-2-3"><div class="cell-grid-demo">.cell-2-3</div></article>
                    <article class="cell-1-4"><div class="cell-grid-demo">.cell-1-4</div></article>
                    <article class="cell-3-4"><div class="cell-grid-demo">.cell-3-4</div></article>
                    <article class="cell-1-4"><div class="cell-grid-demo">.cell-1-4</div></article>
                    <article class="cell-1-2"><div class="cell-grid-demo">.cell-1-2</div></article>
                    <article class="cell-1-4"><div class="cell-grid-demo">.cell-1-4</div></article>
                </div>
            </div>
            <div class="formatter-container formatter-hide no-js tpl">
                <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
                <div class="formatter-content formatter-code">
                    <div class="no-style">
<pre class="precode"><code>&lt;div class="cell-flex ...">
&lt;article class="cell-100">... &lt;/article>
&lt;article class="cell-1-2">... &lt;/article>
&lt;article class="cell-1-2">... &lt;/article>
&lt;article class="cell-1-4">... &lt;/article>
&lt;article class="cell-1-3">... &lt;/article>
&lt;article class="cell-2-3">... &lt;/article>
&lt;article class="cell-1-4">... &lt;/article>
&lt;article class="cell-3-4">... &lt;/article>
&lt;/div></code></pre>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
