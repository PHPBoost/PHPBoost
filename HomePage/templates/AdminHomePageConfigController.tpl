<style>
<!--
#containers {
	width:98%;
	vertical-align:top;
	text-align:center;
	display:block !important;
	margin-left:auto;
	margin-right:auto;
}

.home_page_container {
	float:left;
	margin-top:10px;
	min-height:600px;
	width:{WIDTH_CSS_CONTAINERS}%;
	overflow:auto;
	margin-left:2%;
	border:2px dashed #8F8F8F;
	-moz-border-radius:3px;
    -khtml-border-radius:3px;
    -webkit-border-radius:3px;
    border-radius:3px;
	background:#E5E5E5;
}

.homepage_element {
	cursor:move;
}
-->
</style>
<script type="text/javascript">
<!--
var HomePageConfigSortable = Class.create({
	get_containers : function () {
		return new Array({LIST_CONTAINERS});
	},
	create : function() {
		ContainerList = this.get_containers();
		ContainerList.each(function(container) {
			Sortable.create(
				container, 
				{
					tag:'div',
					containment:[{LIST_CONTAINERS}],
					constraint:false,
					scroll:window,
					format:/^plugin([0-9]+)$/,
					dropOnEmpty: true
				}
			);   
		});
	},
	destroy : function() {
		ContainerList = this.get_containers();
		ContainerList.each(function(container) {
			Sortable.destroy(container); 
		});
	},
	serialize : function() {
		ContainerList = this.get_containers();
		ContainerList.each(function(container) {
			$('position').value += Sortable.serialize(container);
		});
	}
});
var HomePageConfigSortable = new HomePageConfigSortable();
//-->
</script>
<div id="admin_main">
	<div id="admin_contents admin_contents_no_column">
		<div class="module_table" style="background:#f4f4f4;width:99%;margin:auto;padding-bottom:25px;">
			# INCLUDE CONFIGURATION #
			<form action="{REWRITED_SCRIPT}" method="post" onsubmit="HomepageConfigSortable.serialize();">
				<div id="containers">
					# START containers #
						<div id="container{containers.ID}" class="home_page_container">
						# START containers.elements #
							<div id="plugin{containers.elements.ID}" class="homepage_element">{containers.elements.PLUGIN}</div>
						# END containers.elements #
						</div>
					# END containers #
				</div>
				<script type="text/javascript">
				<!--
				Event.observe(window, 'load', function() {
					HomePageConfigSortable.destroy();
					HomePageConfigSortable.create();
				});
				//-->
				</script>
				<fieldset class="fieldset_submit">
					<input type="submit" name="submit" value="VALID" class="submit" />
					<input type="hidden" name="token" value="{TOKEN}" />
					<input type="hidden" name="position" id="position" value="" />
				</fieldset>
			</form>
		</div>
	</div>
</div>