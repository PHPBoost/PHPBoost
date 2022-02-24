<article id="component-explorer" class="sandbox-block">
    <header>
        <h2>{@sandbox.component.explorer}</h2>
    </header>
    <div class="explorer">
        <div class="cats">
                <h2>{@sandbox.component.explorer}</h2>
            <div class="content no-list">
                <ul>
                    <li><a id="class_0" href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.root}</a>
                        <ul>
                            <li class="sub"><a id="class_1" href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.cat} 1</a><span id="cat_1"></span></li>
                            <li class="sub">
                                <a class="parent" href="#">
                                    <i class="far fa-minus-square" id="img2_2"></i> <i class="fa fa-fw fa-folder-open" id="img_2"></i>
                                </a>
                                <a class="selected" id="class_2" href="#">{@sandbox.component.cat} 2</a>
                                <ul>
                                    <li class="sub"><a href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.cat} 3</a></li>
                                    <li class="sub"><a href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.cat} 4</a></li>
                                </ul>
                                <span id="cat_2"></span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="files">
                <h2>{@sandbox.component.tree}</h2>
            <div class="content no-list" id="cat_contents">
                <ul>
                    <li><a href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.cat} 3</a></li>
                    <li><a href="#"><i class="fa fa-fw fa-folder"></i> {@sandbox.component.cat} 4</a></li>
                    <li><a href="#"><i class="fa fa-fw fa-file"></i> {@sandbox.component.file} 1</a></li>
                    <li><a href="#"><i class="fa fa-fw fa-file"></i> {@sandbox.component.file} 2</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="no-style">
<pre class="precode"><code>&lt;div class="explorer">
    &lt;div class="cats">
            &lt;h2>{@sandbox.component.explorer}&lt;/h2>
        &lt;div class="content">
            &lt;ul>
                &lt;li>&lt;a id="class_0" href="#">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.root}&lt;/a>
                    &lt;ul>
                        &lt;li class="sub">&lt;a id="class_1" href="#">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.cat} 1&lt;/a>&lt;span id="cat_1">&lt;/span>&lt;/li>
                        &lt;li class="sub">
                            &lt;a class="parent" href="javascript:show_cat_contents(2, 0);">
                                &lt;span class="fa fa-fw fa-minus-square" id="img2_2">&lt;/span>&lt;span class="fa fa-fw fa-folder-open" id ="img_2">&lt;/span>
                            &lt;/a>
                            &lt;a class="selected" id="class_2" href="#">{@sandbox.component.cat} 2&lt;/a>
                            &lt;span id="cat_2">
                                &lt;ul>
                                    &lt;li class="sub">&lt;a href="#">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.cat} 3&lt;/a>&lt;/li>
                                    &lt;li class="sub">&lt;a href="#">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.cat} 4&lt;/a>&lt;/li>
                                &lt;/ul>
                            &lt;/span>
                        &lt;/li>
                    &lt;/ul>
                &lt;/li>
            &lt;/ul>
        &lt;/div>
    &lt;/div>
    &lt;div class="files">
            &lt;h2>Tree&lt;/h2>
        &lt;div class="content" id="cat_contents">
            &lt;ul>
                &lt;li>&lt;a href="#">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.cat} 3&lt;/a>&lt;/li>
                &lt;li>&lt;a href="javascript:open_cat(2); show_cat_contents(0, 0);">&lt;i class="fa fa-fw fa-folder">&lt;/i> {@sandbox.component.cat} 4&lt;/a>&lt;/li>
                &lt;li>&lt;a href="#">&lt;i class="fa fa-fw fa-file">&lt;/i> {@sandbox.component.file} 1&lt;/a>&lt;/li>
                &lt;li>&lt;a href="#">&lt;i class="fa fa-fw fa-file">&lt;/i> {@sandbox.component.file} 2&lt;/a>&lt;/li>
            &lt;/ul>
        &lt;/div>
    &lt;/div>
&lt;/div></code></pre>
            </div>
        </div>
    </div>
</article>
