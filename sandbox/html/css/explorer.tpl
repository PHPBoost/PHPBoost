<pre>
&lt;div class="explorer">
    &lt;div class="cats">
            &lt;h2>Explorer&lt;/h2>
        &lt;div class="content">
            &lt;ul>
                &lt;li>&lt;a id="class_0" href="#">&lt;i class="fa fa-folder">&lt;/i>Root&lt;/a>
                    &lt;ul>
                        &lt;li class="sub">&lt;a id="class_1" href="#">&lt;i class="fa fa-folder">&lt;/i>Category 1&lt;/a>&lt;span id="cat_1">&lt;/span>&lt;/li>
                        &lt;li class="sub">
                            &lt;a class="parent" href="javascript:show_cat_contents(2, 0);">
                                &lt;span class="fa fa-minus-square" id="img2_2">&lt;/span>&lt;span class="fa fa-folder-open" id ="img_2">&lt;/span>
                            &lt;/a>
                            &lt;a class="selected" id="class_2" href="#">Category 2&lt;/a>
                            &lt;span id="cat_2">
                                &lt;ul>
                                    &lt;li class="sub">&lt;a href="#">&lt;i class="fa fa-folder">&lt;/i>Category 3&lt;/a>&lt;/li>
                                    &lt;li class="sub">&lt;a href="#">&lt;i class="fa fa-folder">&lt;/i>Category 4&lt;/a>&lt;/li>
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
                &lt;li>&lt;a href="#">&lt;i class="fa fa-folder">&lt;/i>Category 3&lt;/a>&lt;/li>
                &lt;li>&lt;a href="javascript:open_cat(2); show_cat_contents(0, 0);">&lt;i class="fa fa-folder">&lt;/i>Category 4&lt;/a>&lt;/li>
                &lt;li>&lt;a href="#">&lt;i class="fa fa-file">&lt;/i>File 1&lt;/a>&lt;/li>
                &lt;li>&lt;a href="#">&lt;i class="fa fa-file">&lt;/i>File 2&lt;/a>&lt;/li>
            &lt;/ul>
        &lt;/div>
    &lt;/div>
&lt;/div>
</pre>
