<article id="component-table" class="sandbox-block">
    <header>
        <h2>{@component.table} {@H|sandbox.pinned.bbcode}</h2>
    </header>
    <table class="table">
        <caption>
            {@component.table.caption}
        </caption>
        <thead>
            <tr>
                <th>
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@component.table.sort.down}">
                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                        </a>
                    </span>
                    {@component.table.name}
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@component.table.sort.up}">
                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </a>
                    </span>
                </th>
                <th>{@component.table.clueription}</th>
                <th>{@component.table.author}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"># INCLUDE PAGINATION_TABLE #</td>
            </tr>
        </tfoot>
    </table>

    <table class="table-no-header">
        <caption>
            {@component.table.caption.no.header}
        </caption>
        <tbody>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
            <tr>
                <td>{@component.table.test}</td>
                <td>{@component.table.clueription}</td>
                <td>{@component.table.author}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"># INCLUDE PAGINATION_TABLE #</td>
            </tr>
        </tfoot>
    </table>

    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="language-html line-numbers"><code class="language-html">// {@component.table.responsive.header}
&lt;table class="table">...&lt;/table>
<br />
// {@component.table.responsive.no.header}
&lt;table class="table-no-header">...&lt;/table>
</code></pre>
            </div>
        </div>
    </div>
</article>
