<div class="cssmenu-sticky">
    <nav id="fwkboost-submenu" class="cssmenu cssmenu-horizontal summary-menu">
        <ul>
            <li><a class="cssmenu-title" href="${relative_url(SandboxUrlBuilder::home())}" aria-label="{@sandbox.title}"><span></span>&nbsp;<i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i>&nbsp;</a></li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::builder())}" class="cssmenu-title"><span>{@builder.title}</span></a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_text_field" class="cssmenu-title"><span>{@hashtag.text.fields}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_textarea" class="cssmenu-title"><span>{@hashtag.textareas}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_choices" class="cssmenu-title"><span>{@hashtag.choices}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_selects" class="cssmenu-title"><span>{@hashtag.selects}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_buttons" class="cssmenu-title"><span>{@hashtag.buttons}</span></a></li>
                    <li class="has-sub">
                        <a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_miscellaneous" class="cssmenu-title"><span>{@hashtag.miscellaneous}</span></a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_date" class="cssmenu-title"><span>{@hashtag.dates}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_file" class="cssmenu-title"><span>{@hashtag.upload}</span></a></li>
                        </ul>
                    </li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_links_list" class="cssmenu-title"><span>{@hashtag.links}</span></a></li>
                    # IF C_GMAP #<li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_fieldset_maps" class="cssmenu-title"><span>{@hashtag.gmap}</span></a></li># ENDIF #
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_authorizations" class="cssmenu-title"><span>{@hashtag.authorizations}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_vertical_fieldset" class="cssmenu-title"><span>{@hashtag.vertical.form}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::builder())}#Sandbox_Builder_horizontal_fieldset" class="cssmenu-title"><span>{@hashtag.horizontal.form}</span></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <span class="cssmenu-title"><span>{@fwkboost.title}</span></span>
                <ul>
                    <li class="has-sub">
                        <a href="${relative_url(SandboxUrlBuilder::component())}" class="cssmenu-title"><span>{@component.title}</span></a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span>test</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#css-table" class="cssmenu-title"><span>{@hashtag.table}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#alerts" class="cssmenu-title"><span>{@hashtag.message.helper}</span></a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::component())}#explorer" class="cssmenu-title"><span></span></a></li>
                        </ul>
                    </li>
                    <li class="has-sub">
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
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#typography" class="cssmenu-title"><span>{@hashtag.bbcode.typography}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#blocks" class="cssmenu-title"><span>{@hashtag.bbcode.blocks}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#code" class="cssmenu-title"><span>{@hashtag.bbcode.code}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#bbcode-table" class="cssmenu-title"><span>{@hashtag.table}</span></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#wiki" class="cssmenu-title"><span>{@hashtag.bbcode.wiki}</span></a></li>
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
<script>
    jQuery('#fwkboost-submenu .cssmenu-title').on('click',function(){
        var targetId = jQuery(this).attr("href"),
            hash = targetId.substring(targetId.indexOf('#'));

        if(hash != null || hash != targetId) {
            if (parseInt($(window).width()) < 769)
                menuOffset = jQuery('#fwkboost-submenu > ul > li > .cssmenu-title').innerHeight();
            else
                menuOffset = jQuery('#fwkboost-submenu').innerHeight();
            history.pushState('', '', hash);
            jQuery('html, body').animate({scrollTop:jQuery(hash).offset().top - menuOffset}, 'slow');
        }
    });
</script>
