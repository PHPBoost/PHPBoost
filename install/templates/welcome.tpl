	<header>
		<h2>{@step.welcome.message}</h2>
	</header>
	
	<div class="content">
		<img class="float-right pbt-box" src="templates/images/PHPBoost_box.png" alt="{@phpboost.logo}" title="{@phpboost.logo}" />
	
		{@H|step.welcome.explanation}
		<div style="margin-bottom:60px;">&nbsp;</div>
		
		<h3>${set(@step.welcome.distribution, ['distribution': @distribution.name])}</h3>
		${html(@step.welcome.distribution.explanation)}
		<br />
		${html(@distribution.description)}
		
			
	</div>
	
	<footer>
		<div class="next-step">
			# INCLUDE LICENSE_FORM #	
		</div>
	</footer>
	
