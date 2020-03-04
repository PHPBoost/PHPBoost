<div id="css-table" class="sandbox-block">
    <article>
        <header>
            <h5>{@css.table}</h5>
        </header>
        <div class="content">
            <table class="table">
                <caption>
                    {@css.table.caption}
                </caption>
                <thead>
                    <tr>
                        <th>
                            <span class="html-table-header-sortable">
                                <a href="#" aria-label="{@css.table.sort.down}">
                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                </a>
                            </span>
                            {@css.table.name}
                            <span class="html-table-header-sortable">
                                <a href="#" aria-label="{@css.table.sort.up}">
                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                </a>
                            </span>
                        </th>
                        <th>{@css.table.description}</th>
                        <th>{@css.table.author}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
                    </tr>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
                    </tr>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
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
                    {@css.table.caption.no.header}
                </caption>
                <tbody>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
                    </tr>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
                    </tr>
                    <tr>
                        <td>{@css.table.test}</td>
                        <td>{@css.table.description}</td>
                        <td>{@css.table.author}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"># INCLUDE PAGINATION_TABLE #</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </article>

    <div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">// {@css.table.responsive.header}
&lt;table class="table">...&lt;/table>
<br />
// {@css.table.responsive.no.header}
&lt;table class="table-no-header">...&lt;/table>
</code></pre>
        </div>
    </div>
</div>
