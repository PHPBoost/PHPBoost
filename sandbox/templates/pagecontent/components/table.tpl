<article id="component-table" class="sandbox-block">
    <header>
        <h2>{@sandbox.component.table} {@H|sandbox.pinned.bbcode}</h2>
    </header>
    <table class="table">
        <caption>
            {@sandbox.component.table.caption}
        </caption>
        <thead>
            <tr>
                <th>
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@sandbox.component.table.sort.down}">
                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                        </a>
                    </span>
                    {@sandbox.component.table.name}
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@sandbox.component.table.sort.up}">
                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </a>
                    </span>
                </th>
                <th>{@sandbox.component.table.description}</th>
                <th>{@sandbox.component.table.author}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
            </tr>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
            </tr>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
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
            {@sandbox.component.table.caption.no.header}
        </caption>
        <tbody>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
            </tr>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
            </tr>
            <tr>
                <td>{@sandbox.component.table.test}</td>
                <td>{@sandbox.component.table.description}</td>
                <td>{@sandbox.component.table.author}</td>
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
<pre class="precode"><code>// {@sandbox.component.table.responsive.header}
&lt;table class="table">...&lt;/table>
<br />
// {@sandbox.component.table.responsive.no.header}
&lt;table class="table-no-header">...&lt;/table></code></pre>
            </div>
        </div>
    </div>
</article>
