<div class="formatter-container formatter-hide no-js tpl">
    <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
    <div class="formatter-content formatter-code">
        <div class="no-style">
<pre class="precode"><code>// cssmenu
&lt;nav id="menu-[ID]" class="cssmenu"> // cssmenu-horizontal cssmenu-vertical cssmenu-static cssmenu-left cssmenu-right cssmenu-with-submenu
    &lt;ul class="level-0">
        &lt;li class="has-sub">
            &lt;a class="cssmenu-title" href="#">&lt;span>Element&lt;/span>&lt;/a>
            &lt;ul>
                &lt;li>&lt;a href="#" class="cssmenu-title">&lt;span>Element&lt;/span>&lt;/a>&lt;/li>
                ...
            &lt;/ul>
        &lt;/li>
        &lt;li>
            &lt;a class="cssmenu-title" href="#">&lt;span>Element&lt;/span>&lt;/a>
        &lt;/li>
        ...
    &lt;/ul>
&lt;/nav>
&lt;script>jQuery("#menu-[ID]").menumaker({ title: "menu-[TITLE]", format: "multitoggle", breakpoint: 768 });&lt;/script> // static: true
&nbsp;
// Pushmenu
&lt;a class="toggle-[ID] pushmenu-toggle">
    &lt;span>[TITLE]&lt;/span>
&lt;/a>
&lt;nav id="pushmenu-[ID]" class="pushnav">
    &lt;ul class="level-0">
        &lt;li class="has-sub">
            &lt;a class="cssmenu-title" href="#">&lt;span>Element&lt;/span>&lt;/a>
            &lt;ul>
                &lt;li>&lt;a href="#" class="cssmenu-title">&lt;span>Element&lt;/span>&lt;/a>&lt;/li>
                ...
            &lt;/ul>
        &lt;/li>
        &lt;li>
            &lt;a class="cssmenu-title" href="#">&lt;span>Element&lt;/span>&lt;/a>
        &lt;/li>
        ...
    &lt;/ul>
&lt;/nav>
&lt;script>
    jQuery('#pushmenu-[ID]').pushmenu({
        customToggle: jQuery('.toggle-[ID]'), // null
        navTitle: '[TITLE]', // null
        pushContent: '[PUSHED_CONTENT]', // container pushed (body, #id_container, or whatever)
        position: '[PUSHMENU_OPENING]', // left, right, top, bottom
        levelOpen: [PUSHMENU_EXPANDING], // 'overlap', 'expand', false
        levelTitles: true, // overlap only
        levelSpacing: 40, // px - overlap only
        navClass: 'pushmenu-nav-[ID]',
        disableBody: [DISABLED_BODY],
        closeOnClick: true, // if disableBody is true
        insertClose: true,
        labelClose: ${escapejs(@common.close)},
        insertBack: true,
        labelBack: ${escapejs(@common.back)}
    });
&lt;/script></code></pre>
        </div>
    </div>
</div>
