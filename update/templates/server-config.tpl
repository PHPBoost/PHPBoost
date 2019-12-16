	<header>
		<h2>{@step.server.title}</h2>
	</header>

	<div class="content">
		<div class="float-right pbt-box align-center">
			<a href="http://www.php.net/">
				<img src="templates/images/php.png" alt="PHP" class="float-right" />
			</a>
		</div>
		<span class="spacer">&nbsp;</span>
		{@H|step.server.explanation}
		<fieldset class="fieldset-content">
			<legend>{@php.version}</legend>
			<div class="fieldset-inset">
				<p>${set(@H|php.version.check.explanation, ['min_php_version': MIN_PHP_VERSION])}</p>
				<div class="form-element">
					<label>${set(@php.version.check, ['min_php_version': MIN_PHP_VERSION])}</label>
					<div class="form-field"# IF PHP_VERSION_OK # aria-label="{@yes}"# ELSE # aria-label="{@no}"# ENDIF #>
					# IF PHP_VERSION_OK #
						<i class="fa fa-check fa-2x success" aria-hidden="true"></i><span class="sr-only">{@yes}</span>
					# ELSE #
						<i class="fa fa-times fa-2x error" aria-hidden="true"></i><span class="sr-only">{@no}</span>
					# ENDIF #
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-content">
			<legend>{@php.extensions}</legend>
			<div class="fieldset-inset">
				<p>{@php.extensions.check}</p>
				<div class="form-element">
					<label>{@php.extensions.check.gdLibrary} <span class="field-description">{@php.extensions.check.gdLibrary.explanation}</span></label>
					<div class="form-field"# IF HAS_GD_LIBRARY # aria-label="{@yes}"# ELSE # aria-label="{@no}"# ENDIF #>
					# IF HAS_GD_LIBRARY #
						<i class="fa fa-check fa-2x success" aria-hidden="true"></i><span class="sr-only">{@yes}</span>
					# ELSE #
						<i class="fa fa-times fa-2x error" aria-hidden="true"></i><span class="sr-only">{@no}</span>
					# ENDIF #
					</div>
				</div>
				<div class="form-element">
					<label>{@php.extensions.check.curlLibrary} <span class="field-description">{@php.extensions.check.curlLibrary.explanation}</span></label>
					<div class="form-field"# IF HAS_CURL_LIBRARY # aria-label="{@yes}"# ELSE # aria-label="{@no}"# ENDIF #>
					# IF HAS_CURL_LIBRARY #
						<i class="fa fa-check fa-2x success" aria-hidden="true"></i><span class="sr-only">{@yes}</span>
					# ELSE #
						<i class="fa fa-times fa-2x error" aria-hidden="true"></i><span class="sr-only">{@no}</span>
					# ENDIF #
					</div>
				</div>
				<div class="form-element">
					<label>{@php.extensions.check.mbstringLibrary} <span class="field-description">{@php.extensions.check.mbstringLibrary.explanation}</span></label>
					<div class="form-field"# IF HAS_MBSTRING_LIBRARY # aria-label="{@yes}"# ELSE # aria-label="{@no}"# ENDIF #>
					# IF HAS_MBSTRING_LIBRARY #
						<i class="fa fa-check fa-2x success" aria-hidden="true"></i><span class="sr-only">{@yes}</span>
					# ELSE #
						<i class="fa fa-times fa-2x error" aria-hidden="true"></i><span class="sr-only">{@no}</span>
					# ENDIF #
					</div>
				</div>
				<div class="form-element">
					<label>{@server.urlRewriting} <span class="field-description">{@server.urlRewriting.explanation}</span></label>
					<div class="form-field"# IF URL_REWRITING_KNOWN ## IF URL_REWRITING_AVAILABLE # aria-label="{@yes}"# ELSE # aria-label="{@no}"# ENDIF ## ELSE # aria-label="{@unknown}"# ENDIF #>
					# IF URL_REWRITING_KNOWN #
						# IF URL_REWRITING_AVAILABLE #
						<i class="fa fa-check fa-2x success" aria-hidden="true"></i><span class="sr-only">{@yes}</span>
						# ELSE #
						<i class="fa fa-times fa-2x error" aria-hidden="true"></i><span class="sr-only">{@no}</span>
						# ENDIF #
					# ELSE #
					<i class="fa fa-question fa-2x" aria-hidden="true"></i><span class="sr-only">{@unknown}</span>
					# ENDIF #
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="fieldset-content">
			<legend>{@folders.chmod}</legend>
			<div id="chmod" class="fieldset-inset">
				<p>{@H|folders.chmod.check}</p>
				# START folder #
					<div class="form-element">
						<label>{folder.NAME}</label>
						<div class="form-field">
							# IF folder.EXISTS #
								<div class="message-helper bgc success">{@folder.exists}</div>
							# ELSE #
								<div class="message-helper bgc error">{@folder.doesNotExist}</div>
							# ENDIF #
							# IF folder.IS_WRITABLE #
								<div class="message-helper bgc success">{@folder.isWritable}</div>
							# ELSE #
								<div class="message-helper bgc error">{@folder.isNotWritable}</div>
							# ENDIF #
						</div>
					</div>
				# END folder #
			</div>
		</fieldset>

		# IF C_MBSTRING_ERROR #
		<fieldset id="mbstring-error"><div class="message-helper bgc error">{@php.extensions.check.mbstringLibrary.error}</div></fieldset>
		# END #
		# IF C_FOLDERS_ERROR #
		<fieldset id="folders-error"><div class="message-helper bgc error">{@folders.chmod.error}</div></fieldset>
		# END #

	</div>

	<footer>
		<div class="next-step">
			# INCLUDE CONTINUE_FORM #
		</div>
	</footer>
