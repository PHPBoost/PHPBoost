<ul class="tabs-items">
	# START action #
		<li class="tab-item --{action.TARGET}# IF action.C_CSS_CLASS # {action.CLASS}# ENDIF # # IF NOT action.C_IS_ACTIVE_MODULE # hidden# ENDIF #">
            # IF action.C_PICTURE #
                # IF action.C_IMG #
                    <img src="{action.U_IMG}" alt="{action.TITLE}" />
                # ELSE #
                    <i class="{action.CSS_CLASS}" aria-hidden="true"></i>
                # ENDIF #
            # ENDIF #
            {action.TITLE}
		</li>
	# END action #
</ul>

# INCLUDE ADD_FIELD_JS #
