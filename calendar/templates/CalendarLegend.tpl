# START legend #
	<span class="pinned small" data-color-surround="{legend.COLOR}"> {legend.NAME}</span>
# END legend #
<script>
	// data-color-surround add a colored square to the pinned class and color its borders.
	jQuery(document).ready(function(){
		jQuery('.pinned[data-color-surround]').each(function(){
			var color = jQuery(this).data('color-surround');
			jQuery(this).css('border-color', color);
			jQuery(this).prepend('<span style="background-color: '+color+';" class="data-color-surround"></span>')
		});
	});
</script>
