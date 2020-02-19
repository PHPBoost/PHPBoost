		<script>
			function add_filter(nbr_filter)
			{
				if (typeof this.max_filter_p == 'undefined' )
					this.max_filter_p = nbr_filter;
				else
					this.max_filter_p++;

				var new_id = this.max_filter_p + 1;
				document.getElementById('add_filter' + this.max_filter_p).innerHTML +=
					'<div id="filter' + new_id + '" class="menu-filter grouped-inputs inputs-with-sup large-inputs-group"><span class="grouped-element">{PATH_TO_ROOT}</span><label class="label-sup grouped-element"><span>' + ${escapejs(LangLoader::get_message('module', 'admin-modules-common'))} + '</span><select name="filter_module' + new_id + '" id="filter_module' + new_id + '">' +
					# START modules #
						'<option value="{modules.ID}">{modules.ID}</option>' +
					# END modules #
					'</select></label><label class="label-sup grouped-element"><span>' + ${escapejs(LangLoader::get_message('page', 'main'))} + '</span><input type="text" name="f' + new_id + '" id="f' + new_id + '" value=""></label>' +
					'<a class="grouped-element" href="javascript:delete_filter(' + new_id + ');" aria-label="' + ${escapejs(LangLoader::get_message('delete', 'common'))} + '"><i class="far fa-trash-altr" aria-hidden="true"></i></a>' +
					'</div><span id="add_filter' + new_id + '"></span>';
			}
			function delete_filter(id) {
				document.getElementById('f' + id).value = '_deleted';
				document.getElementById('filter_module' + id).value = '';
				document.getElementById('filter' + id).style.display = 'none';
			}
		</script>

		<fieldset>
			<legend>{@filters}</legend>
			<p>{@links_menus_filters_explain}</p>
			<div class="fieldset-inset">
				<div class="form-element full-field align-right">
					<label>{@filters}</label>
					<div class="form-field">
						# START filters #
							<div id="filter{filters.ID}" class="menu-filter grouped-inputs inputs-with-sup large-inputs-group">
								<span class="grouped-element">{PATH_TO_ROOT}</span>
								<label for="filter_module{filters.ID}" class="label-sup grouped-element"><span>${LangLoader::get_message('module', 'admin-modules-common')}</span>
									<select name="filter_module{filters.ID}" id="filter_module{filters.ID}">
										# START filters.modules #
											<option value="{filters.modules.ID}"{filters.modules.SELECTED}>{filters.modules.ID}</option>
										# END filters.modules #
									</select>
								</label>
								<label for="f{filters.ID}" class="label-sup grouped-element"><span>${LangLoader::get_message('page', 'main')}</span>
									<input type="text" name="f{filters.ID}" id="f{filters.ID}" value="{filters.FILTER}">
								</label>
								<a class="grouped-element" href="javascript:delete_filter({filters.ID});" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
							</div>
						# END filters #

						<span id="add_filter{NBR_FILTER}"></span>
						<p class="menu-filter align-right">
							<a href="javascript:add_filter({NBR_FILTER})" aria-label="{@add_filter}"><i class="fa fa-plus" aria-hidden="true"></i></a>
						</p>
					</div>
				</div>
			</div>
	    </fieldset>
