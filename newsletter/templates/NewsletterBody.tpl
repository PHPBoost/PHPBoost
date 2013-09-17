<section>
	<header>
		<h1>{@newsletter}</h1>
		# IF C_CREATE_AUTH #
			<a href="{LINK_CREATE}" style="float:right">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/french/add.png" alt="" />
			</a>
		# ENDIF #
	</header>
	<div class="content">
		# INCLUDE TEMPLATE #
	</div>
	<footer></footer>
</section>