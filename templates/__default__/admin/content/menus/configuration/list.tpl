<table class="table">
	<thead>
		<tr>
			<th style="width: 180px;">{L_MENU_CONFIGURATION_NAME}</th>
			<th>{L_MENU_CONFIGURATION_MATCH_REGEX}</th>
			<th style="width: 60px;">{L_MENU_CONFIGURATION_EDIT}</th>
		</tr>
	</thead>
	<tbody>
		# START menu_configuration #
		<tr>
			<td>
				<a href="{menu_configuration.U_CONFIGURE}" aria-label="{EL_MENU_CONFIGURATION_CONFIGURE}">{menu_configuration.E_NAME}</a>
			</td>
			<td>{menu_configuration.E_MATCH_REGEX}</td>
			<td class="align-center">
				<a href="{menu_configuration.U_EDIT}" aria-label="{L_MENU_CONFIGURATION_EDIT}"><i class="far fa-edit" aria-hidden="true" aria-label="{L_MENU_CONFIGURATION_EDIT}"></i></a>
			</td>
		</tr>
		# END menu_configuration #
	</tbody>
</table>
<br />
<span>
	<a href="{U_DEFAULT_MENU_CONFIG_CONFIGURE}"	aria-label="{EL_MENU_CONFIGURATION_CONFIGURE}">{L_MENU_CONFIGURATION_CONFIGURE_DEFAULT_CONFIG}</a>
</span>
