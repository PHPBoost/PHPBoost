# IF C_DISPLAY #
	<li# IF C_HAS_SUB_LINK # class="has-sub"# ENDIF #>
		<a href="{U_LINK}" class="cssmenu-title offload">
			# IF C_HAS_IMG #
				<img src="{IMG}" alt="{NAME}"/>
			# ELSE #
				# IF C_FA_ICON #
					<i class="{FA_ICON}"></i>
				# ELSE #
					# IF C_HEXA_ICON #
						<span class="hexa-icon">{HEXA_ICON}</span>
					# ENDIF #
				# ENDIF #
			# ENDIF #
			{NAME}
		</a>
		# IF C_HAS_SUB_LINK #
			<ul class="level-2">
				# START element #
					# INCLUDE element.ELEMENT #
				# END element #
			</ul>
		# ENDIF #
	</li>
# ENDIF #
