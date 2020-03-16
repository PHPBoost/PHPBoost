
<section>
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>{@cssmenu.h1}</h1>
	</header>
	<div class="elements-container">
		<article>
			<header>
				<h2>{@cssmenu.h2}</h2>
			</header>

			<div class="content">
				<div class="message-helper bgc warning">
					{@H|cssmenu.warning}
				</div>
				<p>{@lorem.large.content}</p>
				# INCLUDE MARKUP #
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<script>
	jQuery('#top-header-content > div:not(#module-mini-sandbox)').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/header.tpl');
	jQuery('#sub-header-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/sub-header.tpl');
	jQuery('#top-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/top-content.tpl');
	jQuery('aside#menu-left').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/vertical-left.tpl');
	jQuery('aside#menu-right').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/vertical-right.tpl');
	jQuery('#bottom-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/bottom-content.tpl');
	jQuery('#top-footer').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/top-footer.tpl');
	jQuery('.footer-content').load('{PATH_TO_ROOT}/sandbox/templates/pagecontent/menus/footer.tpl');
</script>
