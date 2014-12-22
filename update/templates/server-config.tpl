<h1>{@step.server.title}</h1>
<a href="http://www.php.net/">
	<img src="templates/images/php.png" alt="PHP" style="float:right;bottom:20px; margin-left:5px;position:relative;"/>
</a>
<span class="spacer">&nbsp;</span>
{@H|step.server.explanation}
<fieldset>
	<legend>{@php.version}</legend>
	<p>${set(@H|php.version.check.explanation, ['min_php_version': MIN_PHP_VERSION])}</p>
	<div class="form-element">
		<label>${set(@php.version.check, ['min_php_version': MIN_PHP_VERSION])}</label>
		<div class="form-field">
		# IF PHP_VERSION_OK #
			<i class="fa fa-success fa-2x" title="{@yes}"></i>
		# ELSE #
			<i class="fa fa-error fa-2x" title="{@no}"></i>
		# ENDIF #
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>{@php.extensions}</legend>
	<p>{@php.extensions.check}</p>
	<div class="form-element">
		<label>{@php.extensions.check.gdLibrary} <span class="field-description">{@php.extensions.check.gdLibrary.explanation}</span></label>
		<div class="form-field">
		# IF HAS_GD_LIBRARY #
			<i class="fa fa-success fa-2x" title="{@yes}"></i>
		# ELSE #
			<i class="fa fa-error fa-2x" title="{@no}"></i>
		# ENDIF #
		</div>
	</div>
	<div class="form-element">
		<label>{@server.urlRewriting} <span class="field-description">{@server.urlRewriting.explanation}</span></label>
		<div class="form-field">
		# IF URL_REWRITING_KNOWN #
			# IF URL_REWRITING_AVAILABLE #
			<i class="fa fa-success fa-2x" title="{@yes}"></i>
			# ELSE #
			<i class="fa fa-error fa-2x" title="{@no}"></i>
			# ENDIF #
		# ELSE #
		<i class="fa fa-question fa-2x" title="{@unknown}"></i>
		# ENDIF #
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>{@folders.chmod}</legend>
	<p>{@H|folders.chmod.check}</p>
	<div id="chmod">
		# START folder #
		<div class="form-element">
			<label>{folder.NAME}</label>
			<div class="form-field">
				# IF folder.EXISTS #
					<div class="success-block">{@folder.exists}</div>
				# ELSE #
					<div class="failure-block">{@folder.doesNotExist}</div>
				# ENDIF #
				# IF folder.IS_WRITABLE #
					<div class="success-block">{@folder.isWritable}</div>
				# ELSE #
					<div class="failure-block">{@folder.isNotWritable}</div>
				# ENDIF #
			</div>
		</div>
		# END chmod #
	</div>
</fieldset>

# IF ERROR #
<fieldset id="error"><div class="error">{ERROR}</div></fieldset>
# END #

# INCLUDE CONTINUE_FORM #