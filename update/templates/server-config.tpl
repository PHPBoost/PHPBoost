	<header>
		<h2>{@step.server.title}</h2>
	</header>

	<div class="content">
		<a href="http://www.php.net/" title="PHP">
			<img src="templates/images/php.png" alt="PHP" class="float-right" />
		</a>
		<span class="spacer">&nbsp;</span>
		{@H|step.server.explanation}
		<fieldset class="fieldset-content">
			<legend>{@php.version}</legend>
			<div class="fieldset-inset">
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
			</div>
		</fieldset>

		<fieldset class="fieldset-content">
			<legend>{@php.extensions}</legend>
			<div class="fieldset-inset">
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
					<label>{@php.extensions.check.curlLibrary} <span class="field-description">{@php.extensions.check.curlLibrary.explanation}</span></label>
					<div class="form-field">
					# IF HAS_CURL_LIBRARY #
						<i class="fa fa-success fa-2x" title="{@yes}"></i>
					# ELSE #
						<i class="fa fa-error fa-2x" title="{@no}"></i>
					# ENDIF #
					</div>
				</div>
				<div class="form-element">
					<label>{@php.extensions.check.mbstringLibrary} <span class="field-description">{@php.extensions.check.mbstringLibrary.explanation}</span></label>
					<div class="form-field">
					# IF HAS_MBSTRING_LIBRARY #
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
			</div>
		</fieldset>

		<fieldset class="fieldset-content">
			<legend>{@folders.chmod}</legend>
			<div class="fieldset-inset">
				<p>{@H|folders.chmod.check}</p>
				# START folder #
					<div class="form-element">
						<label>{folder.NAME}</label>
						<div class="form-field">
							# IF folder.EXISTS #
								<div class="message-helper success-block">{@folder.exists}</div>
							# ELSE #
								<div class="failure-block">{@folder.doesNotExist}</div>
							# ENDIF #
							# IF folder.IS_WRITABLE #
								<div class="message-helper success-block">{@folder.isWritable}</div>
							# ELSE #
								<div class="failure-block">{@folder.isNotWritable}</div>
							# ENDIF #
						</div>
					</div>
				# END folder #
			</div>
		</fieldset>

		# IF C_MBSTRING_ERROR #
		<fieldset id="mbstring-error"><div class="message-helper error">{@php.extensions.check.mbstringLibrary.error}</div></fieldset>
		# END #
		# IF C_FOLDERS_ERROR #
		<fieldset id="folders-error"><div class="message-helper error">{@folders.chmod.error}</div></fieldset>
		# END #

	</div>

	<footer>
		<div class="next-step">
			# INCLUDE CONTINUE_FORM #
		</div>
	</footer>
