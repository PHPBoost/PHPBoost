<div class="sticky-menu">
    <nav id="fwkboost-submenu" class="cssmenu cssmenu-horizontal summary-menu">
        <ul>
            <li><a class="cssmenu-title" href="${relative_url(SandboxUrlBuilder::home())}" aria-label="{@sandbox.title}"><span></span>&nbsp;<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>&nbsp;</a></li>
            <li class="has-sub fwk-builder">
                <a href="${relative_url(SandboxUrlBuilder::builder())}" class="cssmenu-title"><span>{@builder.title}</span></a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_text_field" class="cssmenu-title"><span>{@hashtag.text.fields}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_textarea" class="cssmenu-title"><span>{@hashtag.textareas}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_choices" class="cssmenu-title"><span>{@hashtag.choices}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_selects" class="cssmenu-title"><span>{@hashtag.selects}</span></a></li>
                    <li class="has-sub">
                        <a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_miscellaneous" class="cssmenu-title"><span>{@hashtag.miscellaneous}</span></a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_date" class="cssmenu-title"><span>{@hashtag.dates}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_file" class="cssmenu-title"><span>{@hashtag.upload}</span></a></li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_links_list" class="cssmenu-title"><span>{@hashtag.links}</span></a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_links_field" class="cssmenu-title"><span>{@hashtag.classics}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_accordion_field" class="cssmenu-title"><span>{@hashtag.accordion}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_modal_field" class="cssmenu-title"><span>{@hashtag.modal}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_tabs_field" class="cssmenu-title"><span>{@hashtag.tabs}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_wizard_field" class="cssmenu-title"><span>{@hashtag.wizard}</span></a></li>
                        </ul>
                    </li>
                    # IF C_GMAP #<li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_fieldset_maps" class="cssmenu-title"><span>{@hashtag.gmap}</span></a></li># ENDIF #
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_authorizations" class="cssmenu-title"><span>{@hashtag.authorizations}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_vertical_fieldset" class="cssmenu-title"><span>{@hashtag.vertical.form}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_horizontal_fieldset" class="cssmenu-title"><span>{@hashtag.horizontal.form}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_buttons" class="cssmenu-title"><span>{@hashtag.buttons}</span></a></li>
                </ul>
            </li>
            <li class="has-sub fwk">
                <span class="cssmenu-title"><span>{@fwkboost.title}</span></span>
                <ul>
                    <li class="has-sub fwk-component">
                        <a href="${relative_url(SandboxUrlBuilder::component())}" class="cssmenu-title"><span>{@component.title}</span></a>
                        <ul>
                            <li class="has-sub">
                                <a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-typography" class="cssmenu-title"><span>{@hashtag.typography}</span></a>
                                <ul>
                                    <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-titles" class="cssmenu-title"><span>{@hashtag.titles}</span></a></li>
                                    <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-sizes" class="cssmenu-title"><span>{@hashtag.sizes}</span></a></li>
                                    <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-styles" class="cssmenu-title"><span>{@hashtag.styles}</span></a></li>
                                </ul>
                            </li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-colors" class="cssmenu-title"><span>{@hashtag.colors}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-media" class="cssmenu-title"><span>{@hashtag.media}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-progressbar" class="cssmenu-title"><span>{@hashtag.progressbar}</span></a></li>
                            <li class="has-sub">
                                <a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-lists" class="cssmenu-title"><span>{@hashtag.lists}</span></a>
                                <ul>
                                    <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-lists" class="cssmenu-title"><span>{@hashtag.classic}</span></a></li>
                                    <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-explorer" class="cssmenu-title"><span>{@hashtag.explorer}</span></a></li>
                                </ul>
                            </li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-notation" class="cssmenu-title"><span>{@hashtag.notation}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-pagination" class="cssmenu-title"><span>{@hashtag.pagination}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-sortable" class="cssmenu-title"><span>{@hashtag.sortable}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-table" class="cssmenu-title"><span>{@hashtag.table}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-alerts" class="cssmenu-title"><span>{@hashtag.message.helper}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#fwkboost-tooltip" class="cssmenu-title"><span>{@hashtag.tooltip}</span></a></li>
                        </ul>
                    </li>
                    <li class="has-sub fwk-layout">
                        <a href="${relative_url(SandboxUrlBuilder::layout())}" class="cssmenu-title"><span>{@layout.title}</span></a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::layout())}#messages" class="cssmenu-title"><span>{@hashtag.message}</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::bbcode())}" class="cssmenu-title"><span>{@bbcode.title}</span></a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-typography" class="cssmenu-title"><span>{@hashtag.typography}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-blocks" class="cssmenu-title"><span>{@hashtag.blocks}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-code" class="cssmenu-title"><span>{@hashtag.code}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-lists" class="cssmenu-title"><span>{@hashtag.lists}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-table" class="cssmenu-title"><span>{@hashtag.table}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-wiki" class="cssmenu-title"><span>{@hashtag.wiki}</span></a></li>
                </ul>
            </li>
            <li><a href="${relative_url(SandboxUrlBuilder::menus())}" class="cssmenu-title"><span>{@menus.title}</span></a></li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::icons())}" class="cssmenu-title"><span>{@icons.title}</span></a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::icons())}#font-awesome" class="cssmenu-title"><span>{@hashtag.font.awesome}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::icons())}#icomoon" class="cssmenu-title"><span>{@hashtag.icomoon}</span></a></li>
                </ul>
            </li>
            <li><a href="${relative_url(SandboxUrlBuilder::table())}" class="cssmenu-title"><span>{@table.title}</span></a></li>
            <li><a href="${relative_url(SandboxUrlBuilder::email())}" class="cssmenu-title"><span>{@emails.title}</span></a></li>
        </ul>
    </nav>
</div>
<script>jQuery("#fwkboost-submenu").menumaker({ title: "FWKBoost", format: "multitoggle", breakpoint: 768 }); </script>
