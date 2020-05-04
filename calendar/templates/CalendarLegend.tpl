# START legend #
	<span class="pinned small" data-color="{legend.COLOR}"> {legend.NAME}</span>
# END legend #
<script>
// data-color add a colored square to the pinned class and color its borders.
jQuery(document).ready(function(){
	jQuery('.pinned[data-color]').each(function(){
		var color = jQuery(this).data('color');
		jQuery(this).css('border-color', color);
		jQuery(this).prepend('<span style="background-color: '+color+';" class="data-color"></span>')
	});
});
</script>
