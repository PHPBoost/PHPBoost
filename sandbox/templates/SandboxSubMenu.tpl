<div class="cssmenu-sticky">
    <nav id="fwkboost-submenu" class="cssmenu cssmenu-horizontal summary-menu">
        <ul>
            <li><a class="cssmenu-title" href="${relative_url(SandboxUrlBuilder::home())}"><i class="fa fa-fw fa-hard-hat" aria-hidden="true"></i></a></li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::form())}" class="cssmenu-title">{@form.title}</a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_text_field" class="cssmenu-title">{@hashtag.text.fields}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_textarea" class="cssmenu-title">{@hashtag.textareas}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_choices" class="cssmenu-title">{@hashtag.choices}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_selects" class="cssmenu-title">{@hashtag.selects}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_buttons" class="cssmenu-title">{@hashtag.buttons}</a></li>
                    <li class="has-sub">
                        <a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_miscellaneous" class="cssmenu-title">{@hashtag.miscellaneous}</a>
                        <ul>
                            <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_date" class="cssmenu-title">{@hashtag.dates}</a></li>
                            <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_file" class="cssmenu-title">{@hashtag.upload}</a></li>
                        </ul>
                    </li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_links_list" class="cssmenu-title">{@hashtag.links}</a></li>
                    # IF C_GMAP #<li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_fieldset_maps" class="cssmenu-title">{@hashtag.gmap}</a></li># ENDIF #
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_authorizations" class="cssmenu-title">{@hashtag.authorizations}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_vertical_fieldset" class="cssmenu-title">{@hashtag.vertical.form}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::form())}#Sandbox_Form_horizontal_fieldset" class="cssmenu-title">{@hashtag.horizontal.form}</a></li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::css())}" class="cssmenu-title">{@component.title}</a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title">test</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::css())}#explorer" class="cssmenu-title"></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::bbcode())}" class="cssmenu-title">{@bbcode.title}</a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#php" class="cssmenu-title">test</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#php" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#php" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#php" class="cssmenu-title"></a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::bbcode())}#php" class="cssmenu-title"></a></li>
                </ul>
            </li>
            <li><a href="${relative_url(SandboxUrlBuilder::menus())}" class="cssmenu-title">{@menus.title}</a></li>
            <li class="has-sub">
                <a href="${relative_url(SandboxUrlBuilder::icons())}" class="cssmenu-title">{@icons.title}</a>
                <ul>
                    <li><a href="${relative_url(SandboxUrlBuilder::icons())}#font-awesome" class="cssmenu-title">{@hashtag.font.awesome}</a></li>
                    <li><a href="${relative_url(SandboxUrlBuilder::icons())}#icomoon" class="cssmenu-title">{@hashtag.icomoon}</a></li>
                </ul>
            </li>
            <li><a href="${relative_url(SandboxUrlBuilder::table())}" class="cssmenu-title">{@table.title}</a></li>
            <li><a href="${relative_url(SandboxUrlBuilder::email())}" class="cssmenu-title">{@emails.title}</a></li>
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
