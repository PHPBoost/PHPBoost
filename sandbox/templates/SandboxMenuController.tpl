
<section>
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
					${LangLoader::get_message('cssmenu.warning', 'menu', 'sandbox')}
				</div>
				<p>{@lorem.large.content}</p>
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<script>
	jQuery('#top-header-content > div:not(#module-mini-sandbox)').load('{PATH_TO_ROOT}/sandbox/html/menu/header.tpl');
	jQuery('#sub-header-content').load('{PATH_TO_ROOT}/sandbox/html/menu/sub-header.tpl');
	jQuery('#top-content').load('{PATH_TO_ROOT}/sandbox/html/menu/top-content.tpl');
	jQuery('aside#menu-left').load('{PATH_TO_ROOT}/sandbox/html/menu/vertical-left.tpl');
	jQuery('aside#menu-right').load('{PATH_TO_ROOT}/sandbox/html/menu/vertical-right.tpl');
	jQuery('#bottom-content').load('{PATH_TO_ROOT}/sandbox/html/menu/bottom-content.tpl');
	jQuery('#top-footer').load('{PATH_TO_ROOT}/sandbox/html/menu/top-footer.tpl');
	jQuery('.footer-content').load('{PATH_TO_ROOT}/sandbox/html/menu/footer.tpl');
</script>
