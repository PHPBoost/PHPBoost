<div id="sortables" class="sandbox-block">
    <article>
        <header>
            <h5>{@sandbox.layout.sortables}</h5>
        </header>
        <div class="more">{@sandbox.layout.sortables.description}</div>
        <div class="content">
            <form action="" method="post" onsubmit="serialize_sortable();">
                <fieldset>
                    <legend>{@sandbox.layout.sortables.legend}</legend>
                    <div class="fieldset-inset">
                        <ul id="categories" class="sortable-block">
                            <li id="cat-1" class="sortable-element" data-id="1">
                                <div class="sortable-selector" aria-label="{@common.move}"></div>
                                <div class="sortable-title">
                                    <a href="#">{@sandbox.layout.title} 1</a>
                                    <em class="h-padding small">{@sandbox.layout.title.sub}</em>
                                </div>
                                <div class="sortable-actions">
                                    <a href="#" aria-label="{@common.move.up}" id="move-up-1" onclick="return false;" style="display: none;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@common.move.down}" id="move-down-1" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@form.authorizations.default}">
                                        <i class="fa fa-fw fa-user-shield" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@common.delete}" data-confirmation="{@delete-element}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                                </div>
                                <ul id="subcat-1" class="sortable-block">
                                    <li id="cat-2" class="sortable-element" data-id="2">
                                        <div class="sortable-selector" aria-label="{@common.move}"></div>
                                        <div class="sortable-title">
                                            <a href="#">{@sandbox.layout.title} 1.1 </a>
                                        </div>
                                        <div class="sortable-actions">
                                            <a href="#" aria-label="{@common.move.up}" id="move-up-2" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                            <a href="#" aria-label="{@common.move.down}" id="move-down-2" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                            <a href="#" aria-label="{@form.authorizations.default}">
                                                <i class="fa fa-fw fa-user-shield" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
                                            <a href="#" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                                        </div>
                                        <ul id="subcat-2" class="sortable-block"></ul>
                                    </li>
                                </ul>
                            </li>
                            <li id="cat-3" class="sortable-element" data-id="3">
                                <div class="sortable-selector" aria-label="{@common.move}"></div>
                                <div class="sortable-title">
                                    <a href="#">{@sandbox.layout.title} 2 </a>
                                </div>
                                <div class="sortable-actions">
                                    <a href="#" aria-label="{@common.move.up}" id="move-up-3" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@common.move.down}" id="move-down-3" onclick="return false;" style="display: none;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@form.authorizations.specials}">
                                        <i class="fa fa-fw fa-user-shield warning" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
                                    <a href="#" aria-label="{@common.delete}" data-confirmation="{@delete-element}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                                </div>
                                <ul id="subcat-3" class="sortable-block"></ul>
                            </li>
                        </ul>
                    </div>
                </fieldset>
                <fieldset class="fieldset-submit">
                    <button type="submit" class="button submit" name="submit" value="true">{@form.submit}</button>
                    <input type="hidden" name="token" value="5b548e3ec5255af9">
                    <input type="hidden" name="tree" id="tree" value="">
                </fieldset>
            </form>
        </div>
        <div class="content">
            <ul class="sortable-block">
                <li class="sortable-element">
                    <div class="sortable-selector" aria-label="{@sandbox.layout.sortable.move}"></div>
                    <div class="sortable-title">
                        <span><a>{@sandbox.layout.static.sortable}</a></span>
                    </div>
                </li>
                <li class="sortable-element dragged" style="position: relative;">
                    <div class="sortable-selector" aria-label="{@sandbox.layout.sortable.move}"></div>
                    <div class="sortable-title">
                        <span><a>{@sandbox.layout.moving.sortable}</a></span>
                    </div>
                </li>
                <li>
                    <div class="dropzone">{@sandbox.layout.dropzone}</div>
                </li>
            </ul>
        </div>
    </article>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;ul id="[ID]" class="sortable-block">
    &lt;li id="cat-1" class="sortable-element" data-id="1">
        &lt;div class="sortable-selector" aria-label="{@common.move}">&lt;/div>
        &lt;div class="sortable-title">
            &lt;a href="...">{@sandbox.layout.title} 1&lt;/a>
            &lt;em class="h-padding small">{@sandbox.layout.title.sub}&lt;/em>
        &lt;/div>
        &lt;div class="sortable-actions">
            &lt;a href="#" aria-label="{@common.move.up}" id="move-up-1" onclick="return false;" style="display: none;">&lt;i class="fa fa-arrow-up" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="#" aria-label="{@common.move.down}" id="move-down-1" onclick="return false;">&lt;i class="fa fa-arrow-down" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="..." aria-label="{@form.authorizations.default}">
                &lt;i class="fa fa-fw fa-user-shield" aria-hidden="true">&lt;/i>
            &lt;/a>
            &lt;a href="..." aria-label="{@common.edit}">&lt;i class="far fa-edit" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="..." aria-label="{@common.delete}" data-confirmation="{@delete-element}">&lt;i class="far fa-trash-alt" aria-hidden="true">&lt;/i>&lt;/a>
        &lt;/div>
        &lt;ul id="subcat-1" class="sortable-block">
            &lt;li id="cat-2" class="sortable-element" data-id="2">
                &lt;div class="sortable-selector" aria-label="{@common.move}">&lt;/div>
                &lt;div class="sortable-title">
                    &lt;a href="...">{@sandbox.layout.title} 1.1 &lt;/a>
                &lt;/div>
                &lt;div class="sortable-actions">
                    &lt;a href="#" aria-label="{@common.move.up}" id="move-up-2" onclick="return false;">&lt;i class="fa fa-arrow-up" aria-hidden="true">&lt;/i>&lt;/a>
                    &lt;a href="#" aria-label="{@common.move.down}" id="move-down-2" onclick="return false;">&lt;i class="fa fa-arrow-down" aria-hidden="true">&lt;/i>&lt;/a>
                    &lt;a href="..." aria-label="{@form.authorizations.default}">
                        &lt;i class="fa fa-fw fa-user-shield" aria-hidden="true">&lt;/i>
                    &lt;/a>
                    &lt;a href="..." aria-label="{@common.edit}">&lt;i class="far fa-edit" aria-hidden="true">&lt;/i>&lt;/a>
                    &lt;a href="..." aria-label="{@common.delete}" data-confirmation="{@delete-element}">&lt;i class="far fa-trash-alt" aria-hidden="true">&lt;/i>&lt;/a>
                &lt;/div>
                &lt;ul id="subcat-2" class="sortable-block">&lt;/ul>
            &lt;/li>
        &lt;/ul>
    &lt;/li>
    &lt;li id="cat-3" class="sortable-element" data-id="3">
        &lt;div class="sortable-selector" aria-label="{@common.move}">&lt;/div>
        &lt;div class="sortable-title">
            &lt;a href="...">{@sandbox.layout.title} 2 &lt;/a>
        &lt;/div>
        &lt;div class="sortable-actions">
            &lt;a href="#" aria-label="{@common.move.up}" id="move-up-3" onclick="return false;">&lt;i class="fa fa-arrow-up" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="#" aria-label="{@common.move.down}" id="move-down-3" onclick="return false;" style="display: none;">&lt;i class="fa fa-arrow-down" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="..." aria-label="{@form.authorizations.specials}">
                &lt;i class="fa fa-fw fa-user-shield warning" aria-hidden="true">&lt;/i>
            &lt;/a>
            &lt;a href="..." aria-label="{@common.edit}">&lt;i class="far fa-edit" aria-hidden="true">&lt;/i>&lt;/a>
            &lt;a href="..." aria-label="{@common.delete}" data-confirmation="{@delete-element}">&lt;i class="far fa-trash-alt" aria-hidden="true">&lt;/i>&lt;/a>
        &lt;/div>
        &lt;ul id="subcat-3" class="sortable-block">&lt;/ul>
    &lt;/li>
&lt;/ul></code></pre>
            </div>
        </div>
    </div>
</div>
