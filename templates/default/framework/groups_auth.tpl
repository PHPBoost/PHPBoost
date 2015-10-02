<div class="advanced-auth-group">
	<select id="groups_auth{IDSELECT}" name="groups_auth{IDSELECT}[]" size="8" multiple="multiple" onclick="{DISABLED_SELECT} document.getElementById('id{IDSELECT}r2').selected = true;">
		<optgroup label="{L_RANKS}">
		# START ranks_list #
			<option {ranks_list.DISABLED} value="r{ranks_list.IDRANK}" id="id{IDSELECT}r{ranks_list.ID}" {ranks_list.SELECTED} onclick="check_select_multiple_ranks('id{IDSELECT}r', {ranks_list.ID})">{ranks_list.RANK_NAME}</option>
		# END ranks_list #
		</optgroup>

		<optgroup label="{L_GROUPS}">
		# START groups_list #
			<option {groups_list.DISABLED} value="{groups_list.IDGROUP}" {groups_list.SELECTED}>{groups_list.GROUP_NAME}</option>
		# END groups_list #
		</optgroup>
	</select>
</div>

# IF C_NO_ADVANCED_AUTH #
<div class="spacer"></div>
# ENDIF #

# IF C_ADVANCED_AUTH #
<div id="advanced_authb{IDSELECT}" class="advanced-auth-select" style="{ADVANCED_AUTH_STYLE}">
	<select id="members_auth{IDSELECT}" name="members_auth{IDSELECT}[]" size="8" multiple="multiple">
		<optgroup label="{L_USERS}" id="advanced_auth3{IDSELECT}">
			# START members_list #
			<option value="{members_list.USER_ID}" selected="selected">{members_list.LOGIN}</option>
			# END members_list #
		</optgroup>
	</select>
</div>

<div id="advanced_auth{IDSELECT}" class="advanced-auth-input" style="{ADVANCED_AUTH_STYLE}">
	<strong>{L_ADD_USER}</strong>
	<br />
	<input type="text" size="14" value="" id="login{IDSELECT}" name="login{IDSELECT}">
	<button onclick="XMLHttpRequest_search_members('{IDSELECT}', '{THEME}', 'add_member_auth', '{L_REQUIRE_PSEUDO}');" type="button" name="valid" class="small">{L_GO}</button>
	<br />
	<span id="search_img{IDSELECT}"></span>
	<div id="xmlhttprequest-result-search{IDSELECT}" class="xmlhttprequest-result-search advanced-auth-input-result" style="display:none;"></div>
</div>
# ENDIF #

<div class="spacer"></div>
<a class="small" href="javascript:open_advanced_auth('{IDSELECT}');">
	# IF C_ADVANCED_AUTH_OPEN #
	<i id="advanced_auth_plus{IDSELECT}" class="fa fa-minus-square-o"></i>
	# ELSE #
	<i id="advanced_auth_plus{IDSELECT}" class="fa fa-plus-square-o"></i>
	# ENDIF #
	{L_ADVANCED_AUTHORIZATION}
</a>
<br />
<a class="small" href="javascript:check_select_multiple('{IDSELECT}', true);">{L_SELECT_ALL}</a>/<a class="small" href="javascript:check_select_multiple('{IDSELECT}', false);">{L_SELECT_NONE}</a>
<br />
<span class="smaller">({L_EXPLAIN_SELECT_MULTIPLE})</span>
<script>
<!--
function check_select_multiple(id, status)
{
	var i;

	//Sélection des groupes.
	var selectidgroups = jQuery('#groups_auth' + id)[0];
	for(i = 0; i < selectidgroups.length; i++)
	{
		if (selectidgroups[i])
			selectidgroups[i].selected = status;
	}
	
	//Sélection des membres.
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
		if (jQuery('#' + id + i))
			jQuery('#' + id + i)[0].selected = true;
	}
}

//Fonction d'ajout de membre dans les autorisations.
function XMLHttpRequest_add_member_auth(searchid, user_id, login, alert_already_auth)
{
    var selectid = jQuery('#members_auth' + searchid)[0];
    for(var i = 0; i < selectid.length; i++) //Vérifie que le membre n'est pas déjà dans la liste.
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

    if (jQuery('#members_auth' + searchid)) //Ajout du membre.
        jQuery('#members_auth' + searchid)[0].options[selectid.length] = oOption;
}

function open_advanced_auth(id) {
	jQuery('#advanced_auth' + id).fadeToggle(300, function(){
		if (jQuery(this).css('display') == 'block'){
			jQuery('#advanced_auth_plus' + id)[0].className = 'fa fa-minus-square-o';
		}
		else{
			jQuery('#advanced_auth_plus' + id)[0].className = 'fa fa-plus-square-o';
			
		}
	});
	jQuery('#advanced_authb' + id).fadeToggle();
}
-->
</script>
