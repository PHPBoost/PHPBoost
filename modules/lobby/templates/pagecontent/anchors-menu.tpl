<div class="sticky-menu" style="order: {MENU_POSITION};">
    <nav id="cssmenu-anchors" class="cssmenu cssmenu-horizontal">
        <ul>
            # START tabs #
                # IF tabs.C_DISPLAYED_TAB #
                    <li style="order: {tabs.TAB_POSITION};">
                        <a href="{tabs.U_TAB}-panel" class="cssmenu-title">{tabs.TAB_TITLE}# IF tabs.C_CATEGORY #/{tabs.TAB_CATEGORY}# ENDIF #</a>
                    </li>
                # ENDIF #
            # END tabs #
        </ul>
    </nav>
</div>

<script>
    jQuery("#cssmenu-anchors").menumaker({
        title: "{@lobby.anchors.title}",
        format: "multitoggle",
        breakpoint: 768
    });
</script>
