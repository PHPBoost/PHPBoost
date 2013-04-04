<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {
	Sortable.create('menu_element_list', {tree:true,dropOnEmpty: true,scroll:window,format: /^menu_element_([0-9]+)$/});   
});
-->
</script>

<ul id="menu_element_lists" class="menu_link_list" style="position: relative;">
	<li class="menu_link_menu" style="position: relative;">
		<ul id="menu_element_list" class="menu_link_list" style="">
			# START childrens #
				{childrens.child}
			# END childrens #
		</ul>
	</li>
</ul>