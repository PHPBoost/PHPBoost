<section>
	<header>
		<h1>
			{@sandbox.module.title} - {@title.form.builder}
		</h1>
	</header>

	<div class="sandbox-summary">
	  <div class="close-summary" aria-label="${LangLoader::get_message('close_menu', 'admin')} {@sandbox.summary}">
		<i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
	  </div>
	  <ul>
		  <li>
		  	<ul>
				<li>
					<a class="summary-link" href="#sandboxForm_fieldset_1">{@form.title.inputs}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_short_multi_line_text_field">{@form.title.textarea}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_checkbox_field">{@form.title.radio}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_select_field">{@form.title.select}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_date_field">{@form.title.date}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_file_field">{@form.title.upload}</a>
				</li>
				# IF C_GMAP #
				<li>
					<a class="summary-link" href="#sandboxForm_fieldset_maps">{@form.title.gmap}</a>
				</li>
				# ENDIF #
				<li>
					<a class="summary-link" href="#sandboxForm_fieldset3">{@form.title.authorization}</a>
				</li>
				<li>
					<a class="summary-link" href="#sandboxForm_fieldset4">{@form.title.orientation}</a>
				</li>
		  	</ul>
		  </li>
	  </ul>
	</div>
	<div class="open-summary">
		<i class="fa fa-arrow-circle-right" aria-hidden="true"></i> {@sandbox.summary}
	</div>

	<article class="content">
		# IF C_PREVIEW #
		<div class="message-helper bgc notice">Prévisualisation</div>
		# ENDIF #

		# IF C_RESULT #
			<strong>TEXT :</strong> {TEXT}
			<div class="spacer"></div>
			<strong>MAIL :</strong> {MAIL}
			<div class="spacer"></div>
			<strong>WEB :</strong> {WEB}
			<div class="spacer"></div>
			<strong>AGE :</strong> {AGE}
			<div class="spacer"></div>
			<strong>MULTI_LINE_TEXT :</strong> {MULTI_LINE_TEXT}
			<div class="spacer"></div>
			<strong>RICH_TEXT :</strong> {RICH_TEXT}
			<div class="spacer"></div>
			<strong>RADIO :</strong> {RADIO}
			<div class="spacer"></div>
			<strong>CHECKBOX :</strong> {CHECKBOX}
			<div class="spacer"></div>
			<strong>SELECT :</strong> {SELECT}
			<div class="spacer"></div>
			<strong>HIDDEN :</strong> {HIDDEN}
			<div class="spacer"></div>
			<strong>DATE :</strong> {DATE}
			<div class="spacer"></div>
			<strong>DATE_TIME :</strong> {DATE_TIME}
			<div class="spacer"></div>
			<strong>FILE :</strong> {FILE}
			<div class="spacer"></div>
			<strong>HIDDEN TEXT AREA TEXT FIELD :</strong> {H_T_TEXT_FIELD}
		# ENDIF #

		# INCLUDE form #

	</article>
	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/sandbox/templates/js/sandbox.js"></script>
