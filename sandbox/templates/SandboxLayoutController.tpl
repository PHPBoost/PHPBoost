<section id="sandbox-css">
	# INCLUDE SANDBOX_SUBMENU #
	<header>
		<h1>
			{@sandbox.module.title} - {@title.component}
		</h1>
	</header>


	<div id="component" class="sandbox-block">
		<h2>{@component.title.component}</h2>
	</div>


	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
<script>jQuery('#sandbox-css article a').on('click', function(){return false;});</script>
