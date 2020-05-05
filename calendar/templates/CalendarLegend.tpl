# START legend #
	<span class="pinned small" data-color-surround="{legend.COLOR}"> {legend.NAME}</span>
# END legend #
<script>
	// data-color add a colored square to the element and color its borders.
	jQuery(document).ready(function(){
		jQuery('[data-color-surround]').colorSurround();
	});
</script>
