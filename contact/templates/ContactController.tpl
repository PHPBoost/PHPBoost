# IF IS_ADMIN #
	<menu class="dynamic_menu right">
		<ul>
			<li><a><i class="icon-cog"></i></a>
				<ul>
					<li>
						<a href="${relative_url(ContactUrlBuilder::manage_fields())}" title="{@admin.fields.manage}">{@admin.fields.manage}</a>
					</li>
					<li>
						<a href="${relative_url(ContactUrlBuilder::configuration())}" title="{@admin.config}">{@admin.config}</a>
					</li>
				</ul>
			</li>
		</ul>
	</menu>
# ENDIF #

<section>
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="module_contents">
		# INCLUDE MSG #
		
		# IF C_INFORMATIONS_TOP #
		<p>{INFORMATIONS}</p>
		<div class="spacer">&nbsp;</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
		<div>
		# ENDIF #
		
		# IF C_INFORMATIONS_LEFT #
		<div class="float_left informations_side">
			<p>{INFORMATIONS}</p>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
		<div class="# IF C_INFORMATIONS_LEFT #float_right# ELSE #float_left# ENDIF # form_side">
		# ENDIF #
		# INCLUDE FORM #
		# IF C_INFORMATIONS_SIDE #
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_RIGHT #
		<div class="float_right informations_side">
			<p>{INFORMATIONS}</p>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_SIDE #
			<div class="spacer">&nbsp;</div>
		</div>
		# ENDIF #
		
		# IF C_INFORMATIONS_BOTTOM #
		<p>{INFORMATIONS}</p>
		<div class="spacer">&nbsp;</div>
		# ENDIF #
	</div>
	<footer></footer>
</section>
