<article id="fwkboost-table" class="sandbox-block">
    <header>
        <h2>{@fwkboost.table}</h2>
    </header>
    <table class="table">
        <caption>
            {@fwkboost.table.caption}
        </caption>
        <thead>
            <tr>
                <th>
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@fwkboost.table.sort.down}">
                            <i class="fa fa-caret-up" aria-hidden="true"></i>
                        </a>
                    </span>
                    {@fwkboost.table.name}
                    <span class="html-table-header-sortable">
                        <a href="#" aria-label="{@fwkboost.table.sort.up}">
                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </a>
                    </span>
                </th>
                <th>{@fwkboost.table.description}</th>
                <th>{@fwkboost.table.author}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
            </tr>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
            </tr>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
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
            {@fwkboost.table.caption.no.header}
        </caption>
        <tbody>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
            </tr>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
            </tr>
            <tr>
                <td>{@fwkboost.table.test}</td>
                <td>{@fwkboost.table.description}</td>
                <td>{@fwkboost.table.author}</td>
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
            <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">// {@fwkboost.table.responsive.header}
&lt;table class="table">...&lt;/table>
<br />
// {@fwkboost.table.responsive.no.header}
&lt;table class="table-no-header">...&lt;/table>
</code></pre>
            </div>
        </div>        
    </div>
</article>
