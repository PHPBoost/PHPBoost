<div id="admin_quick_menu">
    <ul>
        <li class="title_menu">{TITLE}</li>
        # START links #
        <li>
            <a href="{PATH_TO_ROOT}/{links.U_LINK}"><img src="{PATH_TO_ROOT}/{links.U_IMG}" alt="${escape(links.LINK)}" /></a><br />
            <a href="{PATH_TO_ROOT}/{links.U_LINK}" class="quick_link">${escape(links.LINK)}</a>
        </li>
        # END links #
    </ul>
</div>
<div id="admin_contents">
	# INCLUDE content #
</div>