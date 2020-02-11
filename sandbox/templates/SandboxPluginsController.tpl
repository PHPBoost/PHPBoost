<section id="sandbox-css">
	<header>
		<h1>
			{@sandbox.module.title} - {@title.plugins}
		</h1>
	</header>
	<div class="sandbox-summary">
      <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </div>
      <ul>
        <li>
			<a class="summary-link" href="#wizard-example">Wizard.js</a>
			<ul>
				<li><a href="#wizard-html" class="summary-link">HTML</a></li>
				<li><a href="#wizard-form" class="summary-link">Form</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#tooltip-example">Tooltips.js</a>
		</li>
      </ul>
    </div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<div id="wizard-example" class="sandbox-title">
		<h2>{@plugins.wizard.title}</h2>
	</div>
	<header>
		<h5>{@plugins.title.html}</h5>
	</header>
	<article id="wizard-html" class="wizard-container">
		<nav class="wizard-header">
			<ul>
				<li><a href="#">{@plugins.menu.title} 01</a></li>
				<li><a href="#">{@plugins.menu.title} 02</a></li>
				<li><a href="#">{@plugins.menu.title} 03</a></li>
				<li><a href="#">{@plugins.last.step}</a></li>
			</ul>
		</nav>
		<div class="wizard-navigator"></div>
		<div class="wizard-step">
			{@lorem.large.content}
		</div>
		<div class="wizard-step">
			{@lorem.large.content}
		</div>
		<div class="wizard-step">
			{@lorem.large.content}
		</div>
		<div class="wizard-step">
			<div id="tab-03"> <img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image"> </div>
		</div>
	</article>

	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PRE_WIZARD_HTML}
		</div>
	</div>

	<article id="wizard-form" class="sandbox-title">
		<header>
			<h5>{@plugins.title.form}</h5>
		</header>
		# INCLUDE WIZARD_PHP_FORM #
	</article>

	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PRE_WIZARD_FORM}
		</div>
	</div>

	<div id="tooltip-example" class="sandbox-title">
		<h2>{@plugins.tooltip.title}</h2>
	</div>
	<article>
		<p>
			{@plugins.tooltip.desc}
		</p>
		<p>
			<span aria-label="{@plugins.tooltip.label.basic}" class="warning">Tooltip</span>
			{@plugins.tooltip.eg.basic}
		</p>
		<p>
			<span
		        data-tooltip="{@plugins.tooltip.label.options}"
		        data-tooltip-pos="b"
		        data-tooltip-class="error"
		        aria-label="{@plugins.tooltip.label.basic}"
				class="warning">Tooltip</span>
			{@plugins.tooltip.eg.options}
		</p>
		<p>
			${LangLoader::get_message('plugins.tooltip.options', 'plugins', 'sandbox')}
		</p>

	</article>

	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PRE_TOOTLTIP}
		</div>
	</div>

	THIS PAGE IS STILL UNDER CONSTRUCTION

	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
