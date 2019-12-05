		<script>
		<!--
			const FORM = 'form-';
			const SPECIALIZED_FORM_LINK = 'specialize-form-link-';
			var LastSpecializedFormUsed = '{SEARCH_MODE_MODULE}';

			function ChangeForm(module)
			// Change le cadre des résultats
			{
				jQuery('#' + FORM + LastSpecializedFormUsed).fadeOut();
				jQuery('#' + FORM + module).fadeIn();

				document.getElementById(SPECIALIZED_FORM_LINK + LastSpecializedFormUsed).className = '';

				LastSpecializedFormUsed = module;
				document.getElementById('search-in').value = module;

				document.getElementById(SPECIALIZED_FORM_LINK + module).className = 'SFL-current';
			}

			function check_search_form_post()
			// Vérifie la validité du formulaire
			{
				var textSearched = document.getElementById("TxTsearched").value;
				if ( textSearched.length >= 3 && textSearched != '{L_SEARCH}...')
				{
					textSearched = escape_xmlhttprequest(textSearched);
					return true;
				}
				else
				{
					alert({L_WARNING_LENGTH_STRING_SEARCH});
					return false;
				}
			}
		-->
		</script>

	   <section id="module-search">
		   <header>
				<h1>{L_TITLE_SEARCH}</h1>
			</header>
			<div class="content">
				<div class="spacer"></div>
				<form action="{U_FORM_VALID}" onsubmit="return check_search_form_post();" method="post">
					<div class="search-field"><input type="search" id="TxTsearched" name="q" value="{TEXT_SEARCHED}" class="field-xlarge" placeholder="{L_SEARCH}..."></div>
					<div class="spacer"></div>
					<div id="forms-selection" class="options">
						<a id="specialize-form-link-all" href="javascript:ChangeForm('all');" # IF C_SIMPLE_SEARCH # class="SFL-current" # ENDIF #>{L_SEARCH_ALL}</a>
						# START forms #
							<a id="specialize-form-link-{forms.MODULE_NAME}" href="javascript:ChangeForm('{forms.MODULE_NAME}');" # IF forms.C_SELECTED # class="SFL-current" # ENDIF #>{forms.L_MODULE_NAME}</a>
						# END forms #
					</div>
					<div id="form-all" class="SpecializedForm" # IF NOT C_SIMPLE_SEARCH # style="display: none;" # ENDIF #>
						<fieldset class="searchFieldset">
							<div class="form-element">
								<label>{L_SEARCH_IN_MODULES}<br /><span>{L_SEARCH_IN_MODULES_EXPLAIN}</span></label>
								<div class="form-field">
									<select id="searched_modules" name="searched_modules[]" size="5" multiple="multiple" class="list-modules">
									# START searched_modules #
										<option value="{searched_modules.MODULE}" {searched_modules.SELECTED}>{searched_modules.L_MODULE_NAME}</option>
									# END searched_modules #
									</select>
								</div>
							</div>
						</fieldset>
					</div>
					# START forms #
					<div id="form-{forms.MODULE_NAME}" class="SpecializedForm" # IF NOT forms.C_SELECTED # style="display: none;" # ENDIF #>
						<fieldset class="searchFieldset">
						# IF forms.C_SEARCH_FORM #{forms.SEARCH_FORM}# ELSE #<p class="align-center">{forms.SEARCH_FORM}</p># ENDIF #
						</fieldset>
					</div>
					# END forms #
					<fieldset class="fieldset-submit">
						<legend>{L_SEARCH}</legend>
						<input type="hidden" id="search-in" name="search_in" value="all">
						<input type="hidden" name="query_mode" value="0">
						<button type="submit" name="search_submit" value="{L_SEARCH}" class="button submit"><i class="fa fa-search"></i> {L_SEARCH}</button>
						<input type="hidden" name="token" value="{TOKEN}">
					</fieldset>
				</form>
			</div>
			 <footer></footer>
		</section>
		<script>
		<!--
			ChangeForm('{SEARCH_MODE_MODULE}');
		-->
		</script>
