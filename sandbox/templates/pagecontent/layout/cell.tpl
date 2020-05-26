<div id="cell" class="sandbox-block">
    <header>
        <h5>{@layout.cell}</h5>
    </header>

    <h6>{@layout.cell.columns}</h6>
    <div class="cell-flex cell-tile layout-content-demo cell-columns-2">
        <article class="cell">
            <header class="cell-header">
                <h2 class="cell-name">{@layout.title}</h2>
            </header>
            <div class="cell-infos">
                <div class="more">
                    <span class="pinned">{@layout.item}</span>
                    <span class="pinned">{@layout.item}</span>
                </div>
                <div class="controls align-right">
                    <a href="#"><i class="fa fa-edit"></i></a>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </div>
            </div>
            <div class="cell-body">
                <div class="cell-thumbnail">
                    <img src="{U_PICTURE}" alt="{@layout.title}">
                    <a href="" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-content">{@lorem.short.content}</div>
            </div>
        </article>
    </div>

    <h6>{@layout.cell.row}</h6>
    <div class="cell-flex cell-tile layout-content-demo cell-row">
        <article class="cell">
            <header class="cell-header">
                <h2 class="cell-name"><a href="#">{@layout.title}</a></h2>
            </header>
            <div class="cell-infos">
                <div class="more">
                    <span class="pinned">{@layout.item}</span>
                    <span class="pinned">{@layout.item}</span>
                </div>
                <div class="controls align-right">
                    <a href="#"><i class="fa fa-edit"></i></a>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </div>
            </div>
            <div class="cell-body">
                <div class="cell-thumbnail">
                    <img src="{U_PICTURE}" alt="{@layout.title}">
                    <a href="" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-content">{@lorem.short.content}</div>
            </div>
        </article>
    </div>

    <h6>{@layout.cell.all}</h6>
    <div class="cell-flex layout-content-demo cell-tile cell-columns-2">
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.more}</h6>
                <span><i class="fa fa-cog"></i></span>
            </header>
            <div class="cell-infos">
                <div class="more">
                    <span class="pinned">{@layout.item}</span>
                    <span class="pinned">{@layout.item}</span>
                </div>
                <div class="controls align-right">
                    <a href="#"><i class="fa fa-edit"></i></a>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </div>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.alert}</h6>
            </header>
            <div class="cell-alert">
                <span class="message-helper bgc warning">{@lorem.short.content}</span>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.form}</h6>
            </header>
            <div class="cell-form">
                <div class="cell-input"><input type="text" value="input"></div>
            </div>
            <div class="cell-form">
                <div class="cell-label">
                    Label
                    <span class="field-description">{@layout.item}</span>
                </div>
                <div class="cell-input"><input type="text" value="input + label" /></div>
            </div>
            <div class="cell-form">
                <div class="cell-input">
                    <select name="" id="">
                        <option value="">select</option>
                        <option value="">{@layout.item}</option>
                        <option value="">{@layout.item}</option>
                    </select>
                </div>
            </div>
            <div class="cell-form">
                <div class="grouped-inputs">
                    <label for="" class="grouped-element">Label</label>
                    <input type="text" class="grouped-element" value="input">
                    <select name="" id="" class="grouped-element">
                        <option value="">select</option>
                        <option value="">{@layout.item}</option>
                        <option value="">{@layout.item}</option>
                    </select>
                    <a href="" class="grouped-element"><i class="fa fa-caret-right"></i></a>
                </div>
            </div>
            <div class="cell-textarea">
				<textarea name="textarea" rows="3">Textarea : {@lorem.short.content}</textarea>
				<fieldset class="fieldset-submit">
					<button type="submit" class="button submit" name="submit" value="true">{@layout.item}</button>
					<button type="reset" class="button reset-button" value="true">{@layout.item}</button>
				</fieldset>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.content}</h6>
            </header>
            <div class="cell-body">
                <div class="cell-thumbnail">
                    <img src="{U_PICTURE}" alt="{@layout.title}">
                    <a href="" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-content">{@lorem.medium.content}</div>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.list}</h6>
            </header>
            <div class="cell-list">
                <ul>
                    <li class="li-stretch">
                        <span>{@layout.title.sub}</span>
                        <span>{@layout.item}</span>
                    </li>
                    <li class="li-stretch">
                        <span>{@layout.title.sub}</span>
                        <span>{@layout.item}</span>
                    </li>
                    <li>{@layout.item}</li>
                    <li>{@layout.item}</li>
                </ul>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.table}</h6>
            </header>
            <div class="cell-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{@layout.title.sub}</th>
                            <th>{@layout.title.sub}</th>
                            <th>{@layout.title.sub}</th>
                            <th>{@layout.title.sub}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{@layout.item}</td>
                            <td>{@layout.item}</td>
                            <td>{@layout.item}</td>
                            <td>{@layout.item}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>
        <article class="cell cell-100">
            <header class="cell-header">
                <h6 class="cell-name">{@layout.title.footer}</h6>
            </header>
            <div class="cell-footer">
                {@layout.item}
            </div>
        </article>
    </div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content">
<pre class="language-html line-numbers"><code class="language-html">// header
&lt;div class="cell-header">
    &lt;h1 class="cell-name">...&lt;/h1>
    &lt;span>...&lt;/span>
&lt;/div>
// infos
&lt;div class="cell-infos">
    &lt;div>...&lt;/div>
    &lt;div>...&lt;/div>
&lt;/div>
// alert
&lt;div class="cell-alert">
    &lt;div class="message-helper bgc warning">...&lt;/div>
&lt;/div>
// form
&lt;div class="cell-form">
    &lt;div class="cell-input">&lt;input type="text" value="...">&lt;/div>
&lt;/div>
&lt;div class="cell-form">
    &lt;div class="cell-label">
        ...
        &lt;span class="field-description">...&lt;/span>
    &lt;/div>
    &lt;div class="cell-input">&lt;input type="text" value="..." />&lt;/div>
&lt;/div>
&lt;div class="cell-form">
    &lt;div class="cell-input">
        &lt;select name="" id="">...&lt;/select>
    &lt;/div>
&lt;/div>
&lt;div class="cell-form">
    &lt;div class="grouped-inputs">
        &lt;label for="" class="grouped-element">...&lt;/label>
        &lt;input type="text" class="grouped-element" value="...">
        &lt;select name="" id="" class="grouped-element">...&lt;/select>
        &lt;a href="" class="grouped-element">...&lt;/a>
    &lt;/div>
&lt;/div>
&lt;div class="cell-textarea">
    &lt;form action="" method="">
        &lt;textarea name="textarea">&lt;/textarea>
        &lt;fieldset class="fieldset-submit">
            &lt;button type="submit" class="button submit" name="submit" value="true">{@layout.item}&lt;/button>
            &lt;button type="reset" class="button reset-button" value="true">{@layout.item}&lt;/button>
        &lt;/fieldset>
    &lt;/form>
&lt;/div>
// content
&lt;div class="cell-body">
    &lt;div class="cell-thumbnail">
        &lt;img src="path-to-picture" alt="text">
        &lt;a href="" class="cell-thumbnail-caption">&lt;i class="fa fa-eye">&lt;/i>&lt;/a>
    &lt;/div>
    &lt;div class="cell-content">...&lt;/div>
&lt;/div>
// list
&lt;div class="cell-list">
    &lt;ul>
        &lt;li class="li-stretch">
            &lt;span>...&lt;/span>
            &lt;span>...&lt;/span>
        &lt;/li>
        &lt;li>...&lt;/li>
    &lt;/ul>
&lt;/div>
// table
&lt;div class="cell-table">
    &lt;table class="table">...&lt;/table>
&lt;/div>
// footer
&lt;div class="cell-footer">...&lt;/div></code></pre>
            </div>
        </div>
    </div>
</div>
