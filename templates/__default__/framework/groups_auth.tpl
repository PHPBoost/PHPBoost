<div class="advanced-auth-container">
	<label for="groups_auth{SELECT_ID}"><span class="sr-only">{@form.select.multiple.clue}</span></label>
	<div class="advanced-auth advanced-auth-group">
		<select id="groups_auth{SELECT_ID}" name="groups_auth{SELECT_ID}[]" size="8" multiple="multiple" onclick="{DISABLED_SELECT} document.getElementById('id{SELECT_ID}r2').selected = true;">
			<optgroup label="{@user.ranks}">
				# START ranks_list #
					<option # IF ranks_list.C_DISABLED #disabled="disabled" # ENDIF #value="r{ranks_list.RANK_ID}" id="id{SELECT_ID}r{ranks_list.ID}" {ranks_list.SELECTED} onclick="check_select_multiple_ranks('id{SELECT_ID}r', {ranks_list.ID})">{ranks_list.RANK_NAME}</option>
				# END ranks_list #
			</optgroup>

			<optgroup label="{@user.groups}">
				# START groups_list #
					<option # IF groups_list.C_DISABLED #disabled="disabled" # ENDIF #value="{groups_list.GROUP_ID}" {groups_list.SELECTED}>{groups_list.GROUP_NAME}</option>
				# END groups_list #
			</optgroup>
		</select>
	</div>

	# IF C_ADVANCED_AUTH #
		<div id="advanced_authb{SELECT_ID}" class="advanced-auth advanced-auth-select"# IF NOT C_ADVANCED_AUTH_OPEN # style="display: none;"# ENDIF #>
			<div id="advanced_auth{SELECT_ID}" class="advanced-auth advanced-auth-input"# IF NOT C_ADVANCED_AUTH_OPEN # style="display: none;"# ENDIF #>
				<div class="grouped-inputs">
					<input class="grouped-element" type="text" size="14" value="" id="login{SELECT_ID}" name="login{SELECT_ID}" placeholder="{@user.add.member}">
					<label for="login{SELECT_ID}" class="sr-only">{@user.add.member}</label>
					<button class="grouped-element button bgc-full link-color" onclick="XMLHttpRequest_search_members('{SELECT_ID}', '{THEME}', 'add_member_auth', ${escapejs(@warning.username)});" type="button" name="valid" aria-label="{@user.search.member}">
						<i class="fa fa-search-plus" aria-hidden="true"></i>
					</button>
				</div>
				<span id="search_img{SELECT_ID}"></span>
				<div id="xmlhttprequest-result-search{SELECT_ID}" class="xmlhttprequest-result-search advanced-auth-input-result" style="display: none;"></div>
			</div>
			<label for="members_auth{SELECT_ID}"><span class="sr-only">{@user.members}</span></label>
			<select id="members_auth{SELECT_ID}" class="advanced-auth-select advanced-auth-input" name="members_auth{SELECT_ID}[]" size="5" multiple="multiple">
				<optgroup label="{@user.members}" id="advanced_auth3{SELECT_ID}">
					# START members_list #
						<option value="{members_list.USER_ID}" selected="selected">{members_list.LOGIN}</option>
					# END members_list #
				</optgroup>
			</select>
		</div>
	# ENDIF #

</div>
<div class="advanced-auth-text">
	<a class="small" href="javascript:check_select_multiple('{SELECT_ID}',true);">{@common.select.all}</a> / <a class="small" href="javascript:check_select_multiple('{SELECT_ID}',false);">{@common.deselect.all}</a>
	<span class="field-description">{@form.select.multiple.clue}</span>
	<a class="small" href="javascript:open_advanced_auth('{SELECT_ID}');">
		# IF C_ADVANCED_AUTH_OPEN #
			<i id="advanced_auth_plus{SELECT_ID}" class="fa fa-minus-square"></i>
		# ELSE #
			<i id="advanced_auth_plus{SELECT_ID}" class="fa fa-plus-square"></i>
		# ENDIF #
		{@form.authorizations.advanced}
	</a>
</div>
<script>
	function check_select_multiple(id, status)
	{
		var i;

		// Groups selection
		var selectidgroups = jQuery('#groups_auth' + id)[0];
		for(i = 0; i < selectidgroups.length; i++)
		{
			if (selectidgroups[i])
				selectidgroups[i].selected = status;
		}

		// Member selection
		var selectidmember = jQuery('#members_auth' + id)[0];
		for(i = 0; i < selectidmember.length; i++)
		{
			if (selectidmember[i])
				selectidmember[i].selected = status;
		}
	}

	function check_select_multiple_ranks(id, start)
	{
		var i;
		for(i = start; i <= 2; i++)
		{
			if (jQuery('#' + id + i) && jQuery('#' + id + i)[0].disabled != true)
				jQuery('#' + id + i)[0].selected = true;
		}
	}

	// Function to add a member to authorizations
	function XMLHttpRequest_add_member_auth(searchid, user_id, login, alert_already_auth)
	{
		var selectid = jQuery('#members_auth' + searchid)[0];
		for(var i = 0; i < selectid.length; i++) // Check if member isn't already in thel list
		{
			if (selectid[i].value == user_id)
			{
				alert(alert_already_auth);
				return;
			}
		}
		var oOption = new Option(login, user_id);
		oOption.id = searchid + 'm' + (selectid.length - 1);
			oOption.selected = true;

		if (jQuery('#members_auth' + searchid)) // Add the member.
			jQuery('#members_auth' + searchid)[0].options[selectid.length] = oOption;
	}

	function open_advanced_auth(id) {
		jQuery('#advanced_auth' + id).fadeToggle(300, function(){
			if (jQuery('#advanced_auth_plus' + id).hasClass('fa-plus-square')){
				jQuery('#advanced_auth_plus' + id)[0].className = 'fa fa-minus-square';
			}
			else{
				jQuery('#advanced_auth_plus' + id)[0].className = 'fa fa-plus-square';
			}
		});
		jQuery('#advanced_authb' + id).fadeToggle();
	}
</script>
