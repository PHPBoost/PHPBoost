<div class="sticky-menu">
    <nav id="component-submenu" class="cssmenu cssmenu-horizontal summary-menu">
        <ul>
            <li><a class="cssmenu-title offload" href="{U_HOME}" aria-label="{@sandbox.trigger}"><span></span>&nbsp;<i class="fa fa-fw fa-screwdriver-wrench" aria-hidden="true"></i>&nbsp;</a></li>
            <li class="has-sub fwk-builder">
                <a href="{U_BUILDER}" class="cssmenu-title offload"><span>{@sandbox.builder.title}</span></a>
                <ul>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_text_field" class="cssmenu-title offload"><span>{@sandbox.hashtag.text.fields}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_textarea" class="cssmenu-title offload"><span>{@sandbox.hashtag.textareas}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_choices" class="cssmenu-title offload"><span>{@sandbox.hashtag.choices}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_selects" class="cssmenu-title offload"><span>{@sandbox.hashtag.selects}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_miscellaneous" class="cssmenu-title offload"><span>{@sandbox.hashtag.miscellaneous}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_date" class="cssmenu-title offload"><span>{@sandbox.hashtag.dates}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_file" class="cssmenu-title offload"><span>{@sandbox.hashtag.upload}</span></a></li>
                    # IF C_GMAP #<li><a href="{U_BUILDER}#Sandbox_Builder_fieldset_maps" class="cssmenu-title offload"><span>{@sandbox.hashtag.gmap}</span></a></li># ENDIF #
                    <li><a href="{U_BUILDER}#Sandbox_Builder_authorizations" class="cssmenu-title offload"><span>{@sandbox.hashtag.authorizations}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_vertical_fieldset" class="cssmenu-title offload"><span>{@sandbox.hashtag.vertical.form}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_horizontal_fieldset" class="cssmenu-title offload"><span>{@sandbox.hashtag.horizontal.form}</span></a></li>
                    <li><a href="{U_BUILDER}#Sandbox_Builder_buttons" class="cssmenu-title offload"><span>{@sandbox.hashtag.buttons}</span></a></li>
                </ul>
            </li>
            <li class="has-sub fwk-component">
                <a href="{U_COMPONENT}" class="cssmenu-title offload"><span>{@sandbox.component.title}</span></a>
                <ul>
                    <li class="has-sub">
                        <a href="{U_ICONS}" class="cssmenu-title offload"><span>{@sandbox.icons.title}</span></a>
                        <ul>
                            <li><a href="{U_ICONS}#font-awesome" class="cssmenu-title offload"><span>{@sandbox.hashtag.font.awesome}</span></a></li>
                            <li><a href="{U_ICONS}#icomoon" class="cssmenu-title offload"><span>{@sandbox.hashtag.icomoon}</span></a></li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="{U_COMPONENT}#component-typography" class="cssmenu-title offload"><span>{@sandbox.hashtag.typography}</span></a>
                        <ul>
                            <li><a href="{U_COMPONENT}#component-titles" class="cssmenu-title offload"><span>{@sandbox.hashtag.titles}</span></a></li>
                            <li><a href="{U_COMPONENT}#component-sizes" class="cssmenu-title offload"><span>{@sandbox.hashtag.sizes}</span></a></li>
                            <li><a href="{U_COMPONENT}#component-styles" class="cssmenu-title offload"><span>{@sandbox.hashtag.styles}</span></a></li>
                        </ul>
                    </li>
                    <li><a href="{U_COMPONENT}#component-colors" class="cssmenu-title offload"><span>{@sandbox.hashtag.colors}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-media" class="cssmenu-title offload"><span>{@sandbox.hashtag.media}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-progressbar" class="cssmenu-title offload"><span>{@sandbox.hashtag.progressbar}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-notation" class="cssmenu-title offload"><span>{@sandbox.hashtag.notation}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-modal" class="cssmenu-title offload"><span>{@sandbox.hashtag.modal}</span></a></li>
                    <li class="has-sub">
                        <a href="{U_COMPONENT}#component-lists" class="cssmenu-title offload"><span>{@sandbox.hashtag.lists}</span></a>
                        <ul>
                            <li><a href="{U_COMPONENT}#component-lists" class="cssmenu-title offload"><span>{@sandbox.hashtag.classic}</span></a></li>
                            <li><a href="{U_COMPONENT}#component-explorer" class="cssmenu-title offload"><span>{@sandbox.hashtag.explorer}</span></a></li>
                            <li><a href="{U_COMPONENT}#component-pagination" class="cssmenu-title offload"><span>{@sandbox.hashtag.pagination}</span></a></li>
                        </ul>
                    </li>
                    <li><a href="{U_COMPONENT}#component-table" class="cssmenu-title offload"><span>{@sandbox.hashtag.table}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-alerts" class="cssmenu-title offload"><span>{@sandbox.hashtag.message.helper}</span></a></li>
                    <li><a href="{U_COMPONENT}#component-tooltip" class="cssmenu-title offload"><span>{@sandbox.hashtag.tooltip}</span></a></li>
                </ul>
            </li>
            <li class="has-sub fwk-layout">
                <a href="{U_LAYOUT}" class="cssmenu-title offload"><span>{@sandbox.layout.title}</span></a>
                <ul>
                    <li><a href="{U_LAYOUT}#grid" class="cssmenu-title offload"><span>{@sandbox.hashtag.grid}</span></a></li>
                    <li><a href="{U_LAYOUT}#cell" class="cssmenu-title offload"><span>{@sandbox.hashtag.cell}</span></a></li>
                    <li><a href="{U_LAYOUT}#messages" class="cssmenu-title offload"><span>{@sandbox.hashtag.message}</span></a></li>
                    <li><a href="{U_LAYOUT}#sortables" class="cssmenu-title offload"><span>{@sandbox.hashtag.sortable}</span></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="{U_BBCODE}" class="cssmenu-title offload"><span>{@sandbox.bbcode.title}</span></a>
                <ul>
                    <li><a href="{U_BBCODE}#bbcode-typography" class="cssmenu-title offload"><span>{@sandbox.hashtag.typography}</span></a></li>
                    <li><a href="{U_BBCODE}#bbcode-blocks" class="cssmenu-title offload"><span>{@sandbox.hashtag.blocks}</span></a></li>
                    <li><a href="{U_BBCODE}#bbcode-code" class="cssmenu-title offload"><span>{@sandbox.hashtag.code}</span></a></li>
                    <li><a href="{U_BBCODE}#bbcode-lists" class="cssmenu-title offload"><span>{@sandbox.hashtag.lists}</span></a></li>
                    <li><a href="{U_BBCODE}#bbcode-table" class="cssmenu-title offload"><span>{@sandbox.hashtag.table}</span></a></li>
                    <li><a href="{U_BBCODE}#bbcode-wiki" class="cssmenu-title offload"><span>{@sandbox.hashtag.wiki}</span></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <span class="cssmenu-title">{@sandbox.menus.title}</span>
                <ul>
                    <li><a href="{U_MENUS_NAV}" class="cssmenu-title offload"><span>{@sandbox.menus.nav.title}</span></a></li>
                    <li class="has-sub">
                        <a href="{U_MENUS_CONTENT}" class="cssmenu-title offload"><span>{@sandbox.menus.content.title}</span></a>
                        <ul>
                            <li><a href="{U_MENUS_CONTENT}#menu-basic" class="cssmenu-title offload"><span>{@sandbox.hashtag.links}</span></a></li>
                            <li><a href="{U_MENUS_CONTENT}#menu-accordion" class="cssmenu-title offload"><span>{@sandbox.hashtag.accordion}</span></a></li>
                            <li><a href="{U_MENUS_CONTENT}#menu-tabs" class="cssmenu-title offload"><span>{@sandbox.hashtag.tabs}</span></a></li>
                            <li><a href="{U_MENUS_CONTENT}#menu-wizard" class="cssmenu-title offload"><span>{@sandbox.hashtag.wizard}</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="has-sub">
                <span class="cssmenu-title">{@sandbox.tests.title}</span>
                <ul>
                    <li><a href="{U_LANG}" class="cssmenu-title offload"><span>{@sandbox.lang.title}</span></a></li>
                    <li><a href="{U_TABLE}" class="cssmenu-title offload"><span>{@sandbox.table.title}</span></a></li>
                    <li><a href="{U_EMAIL}" class="cssmenu-title offload"><span>{@sandbox.emails.title}</span></a></li>
                    <li><a href="{U_TEMPLATE}" class="cssmenu-title offload"><span>{@sandbox.template.title}</span></a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
<script>jQuery("#component-submenu").menumaker({ title: "FWKBoost", format: "multitoggle", breakpoint: 768 }); </script>
