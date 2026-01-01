<article id="component-pagination" class="sandbox-block">
    <header>
        <h5>{@sandbox.component.pagination}</h5>
    </header>
    <div class="content">
        <div># INCLUDE PAGINATION_FULL #</div>
        <div># INCLUDE PAGINATION_LIGHT #</div>
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;nav class="pagination">
    &lt;ul> // class="light-pagination"
        &lt;li class="pagination-item">
            &lt;a href="#" rel="prev" aria-label="{@sandbox.component.pagination.prev}" class="prev-page">&lt;i class="fa fa-angle-left">&lt;/i>&lt;/a>
        &lt;/li>
        &lt;li class="pagination-item">
            &lt;a href="#" aria-label="{@sandbox.component.pagination.page} 1">1&lt;/a>
        &lt;/li>
        &lt;li class="pagination-item">
            &lt;a href="#" class="current-page" aria-label="{@sandbox.component.pagination.this}">2&lt;/a>
        &lt;/li>
        &lt;li class="pagination-item">
            &lt;a href="#" aria-label="{@sandbox.component.pagination.page} 3">3&lt;/a>
        &lt;/li>
        &lt;li class="pagination-item">
            &lt;a href="#" rel="next" aria-label="{@sandbox.component.pagination.next}" class="next-page">&lt;i class="fa fa-angle-right">&lt;/i>&lt;/a>
        &lt;/li>
    &lt;/ul>
&lt;/nav></code></pre>
            </div>
        </div>
    </div>
</article>
