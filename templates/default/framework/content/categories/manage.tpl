<style>
<!--
#menu_element_list{
    position: relative;
    padding:5px;
}

.menu_link_element > .menu_link_list { margin-left:30px; }
#lists .menu_link_element, .lists .menu_link_element { padding:7px 10px; }

.menu_link_list {
    list-style-type:none;
    list-style-position:outside;
    margin:0px;
	padding:0px;
}

.menu_link_element {
	background:none;
	border:1px solid #D6DEE7;
	padding:0;
	position: relative;
	margin:5px;
}
.menu_link_element:hover {
	border:1px solid #D6DEE7;
	cursor:move;
}

.menu_link_element_title{
	background:#f2f5f8;
	height: 28px;
	padding: 10px 5px 5px 10px;
}
.menu_link_element_title:hover{ background:#D6DEE7; }

.menu_link_element_title img{ cursor:move; }

.menu_link_element_admin{
	float:right;
	padding:0px 10px;
}
.menu_link_element_admin img{ opacity:0.01; }
.menu_link_element_admin img:hover{ opacity:1!important; }

.menu_link_element_title:hover > .menu_link_element_admin img{ opacity:0.5; }

.menu_link_element_title > img.drag{ opacity:0.5; }
.menu_link_element_title:hover > img.drag{ opacity:1; }

.menu_link_element label { color:#1F507F; }
.menu_link_element:hover img { cursor:pointer; }
.menu_link_element:hover label { cursor:pointer; }

.menu_link_menu {
	margin:0;
	border:none;
	background:none;
}
-->
</style>

<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {
	Sortable.create('menu_element_list', {tree:true,dropOnEmpty: true,scroll:window,format: /^menu_element_([0-9]+)$/});   
});
-->
</script>
<fieldset>
	<legend>Gestion des catégories</legend>
	<ul id="menu_element_lists" class="menu_link_list_tosupp cat_list" style="position: relative;">
		<li class="menu_link_menu_tosupp" style="position: relative;">
			<ul id="cats_elements_list" class="cat_list" style="">
				# START childrens #
					{childrens.child}
				# END childrens #
			</ul>
		</li>
	</ul>
</fieldset>
<fieldset class="fieldset_submit">
	<legend>Enregistrer les modifications</legend>
	<input type="hidden" name="id" value="0">
	<input type="hidden" name="token" value="c04be7aff42fb860">
	<input type="hidden" name="menu_tree" id="menu_tree" value="">
	<input type="submit" name="valid" value="Enregistrer les modifications" class="submit">					
</fieldset>