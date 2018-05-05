<table id="table">
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
				<a href="{menu_configuration.U_CONFIGURE}" title="{EL_MENU_CONFIGURATION_CONFIGURE}">{menu_configuration.E_NAME}</a>
			</td>
			<td>{menu_configuration.E_MATCH_REGEX}</td>
			<td class="center">
				<a href="{menu_configuration.U_EDIT}" title="{L_MENU_CONFIGURATION_EDIT}" class="fa fa-edit"></a>
			</td>
		</tr>
		# END menu_configuration #
	</tbody>
</table>
<br />
<span>
	<a href="{U_DEFAULT_MENU_CONFIG_CONFIGURE}"	title="{EL_MENU_CONFIGURATION_CONFIGURE}">{L_MENU_CONFIGURATION_CONFIGURE_DEFAULT_CONFIG}</a>
</span>