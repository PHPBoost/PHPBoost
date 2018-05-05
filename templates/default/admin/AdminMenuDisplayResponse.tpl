<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{TITLE}">
		<i class="fa fa-bars"></i> {TITLE}
	</a>
	<ul>
		# START links #
		<li>
			<a href="{links.U_LINK}" class="quick-link">${escape(links.LINK)}</a>
		</li>
		# END links #
	</ul>
</nav>
<div id="admin-contents">
	# INCLUDE KERNEL_MESSAGE #
	# INCLUDE content #
</div>