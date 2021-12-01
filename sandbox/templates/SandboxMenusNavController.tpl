<section id="module-sandbox-nav-menus">
	<header class="section-header">
		<h1>{@sandbox.cssmenu.h1}</h1>
	</header>
	# INCLUDE SANDBOX_SUBMENU #
	<div class="sub-section">
		<div class="content-container">
			<article>
				<header>
					<h2>{@sandbox.cssmenu.h2}</h2>
				</header>
				<div class="content">
					# IF IS_ADMIN #
						<a href="{PATH_TO_ROOT}/admin/menus/menus.php">
							<i class="fa fa-cogs" aria-hidden="true"></i>
							{@sandbox.menu.management} - {@sandbox.menus}
						</a>
					# ENDIF #
					<div class="message-helper bgc warning">
						{@H|sandbox.cssmenu.warning}
					</div>
					<p>{@sandbox.lorem.large.content}</p>
					# INCLUDE MARKUP #
				</div>
				<footer></footer>
			</article>
		</div>
	</div>
	<footer></footer>
</section>

<script>
	jQuery(document).ready(function() {
		jQuery('#top-header-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/header.tpl');
		jQuery('#inner-header-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/header.tpl');
		jQuery('#sub-header-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/sub-header.tpl');
		jQuery('#top-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/top-content.tpl');
		jQuery('aside#menu-left').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/vertical-left.tpl');
		jQuery('aside#menu-right').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/vertical-right.tpl');
		jQuery('#bottom-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/bottom-content.tpl');
		jQuery('#top-footer').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/top-footer.tpl');
		jQuery('#footer-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/footer.tpl');
	});
</script>
