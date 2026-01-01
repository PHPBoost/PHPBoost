<div id="cell" class="sandbox-block">
    <header>
        <h5>{@sandbox.layout.cell}</h5>
    </header>

    <h6>{@sandbox.layout.cell.columns}</h6>
    <div class="layout-content-demo">

        <div class="cell-flex cell-tile cell-columns-2">
            <article class="cell">
                <header class="cell-header">
                    <h2 class="cell-name">{@sandbox.layout.title}</h2>
                </header>
                <div class="cell-infos">
                    <div class="more">
                        <span class="pinned">{@sandbox.layout.item}</span>
                        <span class="pinned">{@sandbox.layout.item}</span>
                    </div>
                    <div class="controls align-right">
                        <a href="#"><i class="fa fa-edit"></i></a>
                        <a href="#"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                    <div class="cell-thumbnail">
                        <img src="{U_PICTURE}" alt="{@sandbox.layout.title}">
                        <a href="#" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                    </div>
                <div class="cell-body">
                    <div class="cell-content">{@sandbox.lorem.short.content}</div>
                </div>
            </article>
            <article class="cell">
                <header class="cell-header">
                    <h2 class="cell-name">{@sandbox.layout.title}</h2>
                </header>
                <div class="cell-infos">
                    <div class="more">
                        <span class="pinned">{@sandbox.layout.item}</span>
                        <span class="pinned">{@sandbox.layout.item}</span>
                    </div>
                    <div class="controls align-right">
                        <a href="#"><i class="fa fa-edit"></i></a>
                        <a href="#"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                    <div class="cell-thumbnail">
                        <img src="{U_PICTURE}" alt="{@sandbox.layout.title}">
                        <a href="#" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                    </div>
                <div class="cell-body">
                    <div class="cell-content">{@sandbox.lorem.medium.content}</div>
                </div>
            </article>
        </div>
    </div>

    <h6>{@sandbox.layout.cell.row}</h6>
    <div class="layout-content-demo">
        <div class="cell-flex cell-tile cell-row">
            <article class="cell">
                <header class="cell-header">
                    <h2 class="cell-name"><a href="#">{@sandbox.layout.title}</a></h2>
                </header>
                <div class="cell-infos">
                    <div class="more">
                        <span class="pinned">{@sandbox.layout.item}</span>
                        <span class="pinned">{@sandbox.layout.item}</span>
                    </div>
                    <div class="controls align-right">
                        <a href="#"><i class="fa fa-edit"></i></a>
                        <a href="#"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="cell-thumbnail cell-center">
                    <img src="{U_PICTURE}" alt="{@sandbox.layout.title}">
                    <a href="#" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-body">
                    <div class="cell-content">{@sandbox.lorem.short.content}</div>
                </div>
            </article>
            <article class="cell">
                <header class="cell-header">
                    <h2 class="cell-name"><a href="#">{@sandbox.layout.title}</a></h2>
                </header>
                <div class="cell-infos">
                    <div class="more">
                        <span class="pinned">{@sandbox.layout.item}</span>
                        <span class="pinned">{@sandbox.layout.item}</span>
                    </div>
                    <div class="controls align-right">
                        <a href="#"><i class="fa fa-edit"></i></a>
                        <a href="#"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <div class="cell-thumbnail cell-center">
                    <img src="{U_PICTURE}" alt="{@sandbox.layout.title}">
                    <a href="#" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-body">
                    <div class="cell-content">{@sandbox.lorem.short.content}</div>
                </div>
            </article>
        </div>
    </div>

    <h6>{@sandbox.layout.cell.all}</h6>
    <div class="cell-flex layout-content-demo cell-tile cell-columns-2">
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.more}</h6>
                <span><i class="fa fa-cog"></i></span>
            </header>
            <div class="cell-infos">
                <div class="more">
                    <span class="pinned">{@sandbox.layout.item}</span>
                    <span class="pinned">{@sandbox.layout.item}</span>
                </div>
                <div class="controls align-right">
                    <a href="#"><i class="fa fa-edit"></i></a>
                    <a href="#"><i class="fa fa-trash"></i></a>
                </div>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.alert}</h6>
            </header>
            <div class="cell-alert">
                <span class="message-helper bgc warning">{@sandbox.lorem.short.content}</span>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.form}</h6>
            </header>
            <div class="cell-form">
                <div class="cell-input"><input type="text" placeholder="input text"></div>
            </div>
            <div class="cell-form">
                <div class="cell-label">
                    <label for="label-and-input">
                        Label
                        <span class="field-description">{@sandbox.layout.item}</span>
                    </label>
                </div>
                <div class="cell-input"><input type="text" id="label-and-input" placeholder="input text" /></div>
            </div>
            <div class="cell-form">
                <div class="cell-input">
                    <select name="">
                        <option value="">select</option>
                        <option value="">{@sandbox.layout.item}</option>
                        <option value="">{@sandbox.layout.item}</option>
                    </select>
                </div>
            </div>
            <div class="cell-form">
                <div class="grouped-inputs">
                    <label for="input-id" class="grouped-element">
                        <span>Label</span>
                    </label>
                    <input class="grouped-element" id="input-id" type="text" placeholder="input text">
                    <select name="" class="grouped-element">
                        <option value="">select</option>
                        <option value="">{@sandbox.layout.item}</option>
                        <option value="">{@sandbox.layout.item}</option>
                    </select>
                    <a href="#" class="grouped-element"><i class="fa fa-caret-right"></i></a>
                </div>
            </div>
            <div class="cell-textarea">
				<textarea name="textarea" rows="3">Textarea : {@sandbox.lorem.short.content}</textarea>
            </div>
            <div class="cell-form">
                <fieldset class="fieldset-submit cell-center">
                    <button type="submit" class="button submit" name="submit" value="true">{@sandbox.layout.item}</button>
                    <button type="reset" class="button reset-button" value="true">{@sandbox.layout.item}</button>
                </fieldset>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.content}</h6>
            </header>
            <div class="cell-body">
                <div class="cell-thumbnail">
                    <img src="{U_PICTURE}" alt="{@sandbox.layout.title}">
                    <a href="#" class="cell-thumbnail-caption"><i class="fa fa-eye"></i></a>
                </div>
                <div class="cell-content">{@sandbox.lorem.medium.content}</div>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.list}</h6>
            </header>
            <div class="cell-list">
                <ul>
                    <li class="li-stretch">
                        <span>{@sandbox.layout.title.sub}</span>
                        <span>{@sandbox.layout.item}</span>
                    </li>
                    <li class="li-stretch">
                        <span>{@sandbox.layout.title.sub}</span>
                        <span>{@sandbox.layout.item}</span>
                    </li>
                    <li>{@sandbox.layout.item}</li>
                    <li>{@sandbox.layout.item}</li>
                </ul>
            </div>
        </article>
        <article class="cell">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.sandbox.table}</h6>
            </header>
            <div class="cell-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{@sandbox.layout.title.sub}</th>
                            <th>{@sandbox.layout.title.sub}</th>
                            <th>{@sandbox.layout.title.sub}</th>
                            <th>{@sandbox.layout.title.sub}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{@sandbox.layout.item}</td>
                            <td>{@sandbox.layout.item}</td>
                            <td>{@sandbox.layout.item}</td>
                            <td>{@sandbox.layout.item}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </article>
        <article class="cell cell-100">
            <header class="cell-header">
                <h6 class="cell-name">{@sandbox.layout.title.footer}</h6>
            </header>
            <div class="cell-footer">
                {@sandbox.layout.item}
            </div>
        </article>
    </div>

    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>// header
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
        &lt;select name="">...&lt;/select>
    &lt;/div>
&lt;/div>
&lt;div class="cell-form">
    &lt;div class="grouped-inputs">
        &lt;label for="input-id" class="grouped-element">
            &lt;span class="grouped-element">...&lt;/span>
            &lt;input id="input-id" type="text" class="grouped-element" value="...">
        &lt;/label>
        &lt;select name="" class="grouped-element">...&lt;/select>
        &lt;a href="" class="grouped-element">...&lt;/a>
    &lt;/div>
&lt;/div>
&lt;div class="cell-textarea">
    &lt;form action="" method="">
        &lt;textarea name="textarea">&lt;/textarea>
        &lt;fieldset class="fieldset-submit">
            &lt;button type="submit" class="button submit" name="submit" value="true">{@sandbox.layout.item}&lt;/button>
            &lt;button type="reset" class="button reset-button" value="true">{@sandbox.layout.item}&lt;/button>
        &lt;/fieldset>
    &lt;/form>
&lt;/div>
// thumbnail
&lt;div class="cell-thumbnail">
    &lt;img src="path-to-picture" alt="text">
    &lt;a href="" class="cell-thumbnail-caption">&lt;i class="fa fa-eye">&lt;/i>&lt;/a>
&lt;/div>
// content
&lt;div class="cell-body">
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
