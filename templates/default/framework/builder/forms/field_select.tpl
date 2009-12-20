		<dl>
			<dt><label for="{ID}">{L_REQUIRE}{L_FIELD_NAME}</label><br /><span>{L_EXPLAIN}</span></dt>
			<dd>
				# IF C_SELECT_MULTIPLE #
				<select name="{L_FIELD_NAME}[]" multiple="multiple">
				# ELSE #
				<select name="{L_FIELD_NAME}">
				# ENDIF #
					# START field_options #
						{field_options.OPTION}
					# END field_options #		
				</select>
				&nbsp;<span id="onblurContainerResponse{ID}" style="display:none"></span>
			 	<div style="font-weight:bold;display:none" id="onblurMesssageResponse{ID}"></div>
			</dd>
		</dl>
		