<section>
	<header>
		<h1>
			{@newsletter}
			# IF C_CREATE_AUTH #
			<span class="actions">	
				<a href="{LINK_CREATE}">
					<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/french/add.png" alt="" />
				</a>
			</span>
			# ENDIF #
		</h1>
	</header>
	<div class="content">
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>