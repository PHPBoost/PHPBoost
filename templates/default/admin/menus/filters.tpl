
		<fieldset>
			<legend>{@filters}</legend>
			<p>
				{@links_menus_filters_explain}
			</p>
			<br />
			<dl>
				<dt><label>{@filters}</label></dt>
				<dd>
					# START filters #
					{PATH_TO_ROOT} / 
					<select name="filter_module{filters.ID}" id="filter_module">
						# START filters.modules #
						<option value="{filters.modules.ID}"{filters.modules.SELECTED}>{filters.modules.ID}</option>
						# END filters.modules #
					</select>
					/ <input type="text" name="f{filters.ID}" value="{filters.FILTER}" size="25" />
					<br />
					# END filters #
					
					<span id="add_filter{NBR_FILTER}"></span>
					<p style="text-align:center;margin-top:10px;">
						<a href="javascript:add_filter({NBR_FILTER})" title="{@add_filter}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" alt="+" /></a>
					</p>
				</dd>
			</dl>
	    </fieldset>
	    