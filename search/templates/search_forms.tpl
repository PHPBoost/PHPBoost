<script>
	const FORM = 'form-';
	const SPECIALIZED_FORM_LINK = 'specialize-form-link-';
	var LastSpecializedFormUsed = '{MODULE_MODE}';

	function ChangeForm(module)
	// Limit the search to a single module
	{
		jQuery('#' + FORM + LastSpecializedFormUsed).fadeOut();
		jQuery('#' + FORM + module).fadeIn();

		document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).className = '';

		LastSpecializedFormUsed = module;
		document.getElementById('search-in').value = module;

		document.getElementById(SPECIALIZED_FORM_LINK + module).className = 'current';
	}

	function check_search_form_post()
	// Check the form validity
	{
		var searchText = document.getElementById("advanced-search-text").value;
		if ( searchText.length >= 3 && searchText != '{@form.search}')
		{
			searchText = escape_xmlhttprequest(searchText);
			return true;
		}
		else
		{
			alert(${escapejs(@search.warning.length)});
			return false;
		}
	}
</script>

<section id="module-search">
	<header>
		<h1>{@search.module.title}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<form action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
					<div id="forms-selection" class="cell-tile">
						<div class="cell cell-options">
							<div class="cell-list">
								<ul>
									<li id="specialize-form-link-all" # IF C_SIMPLE_SEARCH # class="current" # ENDIF #>
										<a href="javascript:ChangeForm('all');">{@search.all}</a>
									</li>
									# START forms #
										<li id="specialize-form-link-{forms.MODULE_NAME}" # IF forms.C_SELECTED # class="current" # ENDIF #>
											<a  href="javascript:ChangeForm('{forms.MODULE_NAME}');">{forms.L_MODULE_NAME}</a>
										</li>
									# END forms #
								</ul>
							</div>
						</div>
					</div>
					<div class="form-element">
						<label for="">{@search.text}</label>
						<div class="form-field form-field-text">
							<input type="search" id="advanced-search-text" name="q" value="{TEXT_SEARCHED}" class="field-xlarge" placeholder="">
						</div>
					</div>
					<div id="form-all" class="specialized-form" # IF NOT C_SIMPLE_SEARCH # style="display: none;" # ENDIF #>
							<div class="form-element">
								<label for="">
									{@search.in.modules}
									<span class="field-description">{@search.in.modules.clue}</span>
								</label>
								<div class="form-field form-field-multi-select">
									<select id="searched_modules" name="searched_modules[]" size="5" multiple="multiple" class="list-modules">
									# START searched_modules #
										<option value="{searched_modules.MODULE}" {searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
									# END searched_modules #
									</select>
								</div>
							</div>
					</div>
					# START forms #
						<div id="form-{forms.MODULE_NAME}" class="specialized-form" # IF NOT forms.C_SELECTED # style="display: none;" # ENDIF #>
							# IF forms.C_SEARCH_FORM #
								{forms.SEARCH_FORM}
							# ELSE #
								<p class="align-center">{forms.SEARCH_FORM}</p>
							# ENDIF #
						</div>
					# END forms #
					<fieldset class="fieldset-submit">
						<input type="hidden" id="search-in" name="search_in" value="all">
						<input type="hidden" name="query_mode" value="0">
						<button type="submit" name="search_submit" value="{@form.search}" class="button submit"><i class="fa fa-search" aria-hidden="true"></i> {@form.search}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</fieldset>
				</form>
			</div>

		</div>
	</div>
	<footer></footer>
</section>
<script>
	ChangeForm('{MODULE_MODE}');
</script>
