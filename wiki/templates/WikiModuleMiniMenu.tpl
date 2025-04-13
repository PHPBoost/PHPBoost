<div class="cell-body# IF C_HORIZONTAL # inline-nav# ENDIF #">
	<nav id="wiki-mini-nav" class="wiki-mini-nav">
		<ul>
			# START categories #
				<li
						data-wiki-id="{categories.CATEGORY_ID}"
						data-wiki-parent-id="{categories.CATEGORY_PARENT_ID}"
						data-wiki-order-id="{categories.CATEGORY_SUB_ORDER}"
						class="sub-cat">
					<span class="swap-handle"></span>
					<a class="menutree-title offload" href="{categories.U_CATEGORY}">
						<i class="fa fa-fw fa-folder" aria-hidden></i> 
						<span>{categories.CATEGORY_NAME}</span>
					</a>
					# IF categories.C_ITEMS #
						<ul class="level-1">
							# START categories.items #
								<li>
									<span class="swap-handle"></span>
									<a class="menutree-title offload" href="{categories.items.U_ITEM}">
										<i class="far fa-file" aria-hidden="true"></i>
										<span>{categories.items.TITLE}</span>
									</a>
								</li>
							# END categories.items #
						</ul>
					# ENDIF #
				</li>
			# END categories #
		</ul>
	</nav>
	# IF C_ROOT_ITEMS #
		<nav id="wiki-root-nav" class="wiki-mini-nav">
			<ul class="root-ul">
				<li class="sub-cat root-category">
					<span class="swap-handle"></span>
					<span class="menutree-title offload">
						<i class="fa fa-house-flag" aria-hidden></i> 
						<span>{@wiki.root}</span>
					</span>
					<ul>
						# START root_items #
							<li>
								<span class="swap-handle"></span>
								<a class="menutree-title offload" href="{root_items.U_ITEM}">
									<i class="far fa-file" aria-hidden="true"></i>
									<span>{root_items.TITLE}</span>
								</a>
							</li>
						# END root_items #
					</ul>
				</li>
			</ul>
		</nav>
	# ENDIF #
</div>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki.mini# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script>
	jQuery(document).ready(function() { 
		jQuery('#wiki-mini-nav').menutree();
		jQuery('#wiki-root-nav').menutree();
	});
</script>