<section id="sandbox-css">
	<header>
		<h1>
			{@module.title} - {@title.plugins}
		</h1>
	</header>
	<div class="sandbox-summary">
      <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
      </div>
      <ul>
        <li>
			<a class="summary-link" href="#framework">{@css.title.framework}</a>
			<ul>
				<li><a href="#page-title" class="summary-link">{@css.page.title}</a></li>
				<li><a href="#options" class="summary-link">{@css.options}</a></li>
				<li><a href="#options-infos" class="summary-link">{@css.options}.infos</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#typography">{@css.title.typography}</a>
			<ul>
				<li><a href="#titles" class="summary-link">{@css.titles}</a></li>
				<li><a href="#sizes" class="summary-link">{@css.title.sizes}</a></li>
				<li><a href="#styles" class="summary-link">{@css.styles}</a></li>
				<li><a href="#rank-colors" class="summary-link">{@css.rank_color}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#miscellaneous">{@css.miscellaneous}</a>
			<ul>
				<li><a href="#progress-bar" class="summary-link">{@css.progress_bar}</a></li>
				<li><a href="#icons" class="summary-link">{@css.main_actions_icons}</a></li>
				<li><a href="#explorer" class="summary-link">{@css.explorer}</a></li>
				<li><a href="#lists" class="summary-link">{@css.lists}</a></li>
				<li><a href="#buttons" class="summary-link">{@css.button}</a></li>
				<li><a href="#notation" class="summary-link">{@css.notation}</a></li>
				<li><a href="#pagination" class="summary-link">{@css.pagination}</a></li>
				<li><a href="#sortable" class="summary-link">{@css.sortable}</a></li>
				<li><a href="#css-table" class="summary-link">{@css.table}</a></li>
				<li><a href="#messages" class="summary-link">{@css.messages.and.coms}</a></li>
				<li><a href="#alerts" class="summary-link">{@css.alert.messages}</a></li>
			</ul>
		</li>
        <li>
			<a class="summary-link" href="#blocks">{@css.blocks}</a>
		</li>
      </ul>
    </div>
	<div class="open-summary">
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
    </div>
	<script>jQuery("#cssmenu-sandbox").menumaker({ title: "Sandbox", format: "multitoggle", breakpoint: 768 }); </script>

	<div id="easytabs" class="sandbox-title">
		<h2>{@plugins.tabs.title}</h2>
	</div>
	<article id="easytabs-html" class="sandbox-title">
		<header>
			<h5>{@plugins.title.html}</h5>
		</header>
		<div class="tab-container">
			<nav>
				<ul>
					<li><a href="#tab-01">{@plugins.menu.title} 01</a></li>
					<li><a href="#tab-02">{@plugins.menu.title} 02</a></li>
					<li><a href="#tab-03">{@plugins.menu.title} 03</a></li>
				</ul>
			</nav>
			<div class="panel-container">
				<div id="tab-01"> {@plugins.short.content} </div>
				<div id="tab-02"> {@plugins.large.content} </div>
				<div id="tab-03"> <img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="PHPBoost" itemprop="image"> </div>
			</div>
		</div>
	</article>

	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PRE_TABS_HTML}
		</div>
	</div>

	<article id="easytabs-form" class="sandbox-title">
		<header>
			<h5>{@plugins.title.form}</h5>
		</header>
		# INCLUDE TABS_PHP_FORM #
	</article>

	<!-- Source code -->
	<div class="formatter-container formatter-hide no-js tpl" onclick="bb_hide(this)">
		<span class="formatter-title title-perso">{@sandbox.source.code} :</span>
		<div class="formatter-content">
			{PRE_TABS_FORM}
		</div>
	</div>

	<div id="wizard" class="sandbox-title">
		<h2>{@plugins.wizard.title}</h2>
	</div>
	<article class="wizard-container">
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
			{@plugins.short.content}
		</div>
		<div class="wizard-step">
			{@plugins.large.content}
		</div>
		<div class="wizard-step">
			{@plugins.short.content}
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

	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
