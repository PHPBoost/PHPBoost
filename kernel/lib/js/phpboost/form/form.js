// This contains all the HTML forms contained in the page
function HTMLForms(){}
HTMLForms.forms = new Array();

HTMLForms.add = function(form) {
	return HTMLForms.forms.push(form);
};
HTMLForms.get = function(id) {
	var form = null;
	HTMLForms.forms.each(function(aForm) {
		if (aForm.getId() == id) {
			form = aForm;
			throw $break;
		}
	});
	return form;
};
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
};
HTMLForms.getFieldset = function(id) {
	var fieldset = null;
	HTMLForms.forms.each(function(form) {
		var aFieldset = form.getFieldset(id);
		if (aFieldset != null) {
			fieldset = aFieldset;
			throw $break;
		}
	});
	return fieldset;
};
HTMLForms.getField = function(id) {
	var field = null;
	HTMLForms.forms.each(function(form) {
		var aField = form.getField(id);
		if (aField != null) {
			field = aField;
			throw $break;
		}
	});
	return field;
};

// Shortcuts
var $HF = HTMLForms.get;
var $FFS = HTMLForms.getFieldset;
var $FF = HTMLForms.getField;

// This represents a HTML form.
function HTMLForm(id){
	this.id = id;
	this.fieldsets = new Array();
};
HTMLForm.prototype.getId = function () {
	return this.id;
};
HTMLForm.prototype.addFieldset = function (fieldset) {
	this.fieldsets.push(fieldset);
	fieldset.setFormId(this.id);
};
HTMLForm.prototype.getFieldset = function (id) {
	var fieldset = null;
	this.fieldsets.each(function(aFieldset) {
		if (aFieldset.getId() == id) {
			fieldset = aFieldset;
			throw $break;
		}
	});
	return fieldset;
};
HTMLForm.prototype.hasFieldset = function (id) {
	var hasFieldset = false;
	this.fieldsets.each(function(aFieldset) {
		if (aFieldset.getId() == id) {
			hasFieldset = true;
			throw $break;
		}
	});
	return hasFieldset;
};
HTMLForm.prototype.getFields = function () {
	var fields = new Array();
	this.fieldsets.each(function(fieldset) {
		fieldset.getFields().each(function(field) {
			fields.push(field);
		});
	});
	return fields;
};
HTMLForm.prototype.getField = function (id) {
	var field = null;
	this.getFields().each(function(aField) {
		if (aField.getId() == id) {
			field = aField;
			throw $break;
		}
	});
	return field;
};
HTMLForm.prototype.validate = function () {
	var validated = true;
	var validation = '';
	var form = this;
	this.getFields().each(function(field) {
		var field_validation = field.validate();
		
		if (field_validation != "") {
			validation = validation + '\n\n' + field_validation;
			validated = false;
		}
	});
	
	if (validated == false) {
		form.displayValidationError(validation);
		jQuery('html, body').animate({scrollTop:jQuery('#' + this.id).offset().top}, 'slow');
	}
	
	this.registerDisabledFields();
	return validated;
};
HTMLForm.prototype.displayValidationError = function (message) {
	message = message.replace(/&quot;/g, '"');
	message = message.replace(/&amp;/g, '&');
	alert(message);
};
HTMLForm.prototype.registerDisabledFields = function () {
	var disabledFields = "";
	this.getFields().each(function(field) {
		if (field.isDisabled()) {
			disabledFields += "|" + field.getId();
		}
	});
	jQuery('#' + this.id + '_disabled_fields').value = disabledFields;

	var disabledFieldsets = "";
	this.getFieldsets().each(function(fieldset) {
		if (fieldset.isDisabled()) {
			disabledFieldsets += "|" + fieldset.getId();
		}
	});
	jQuery('#' + this.id + '_disabled_fieldsets').value = disabledFieldsets;
};

// This represents a fieldset
function FormFieldset(id){
	this.id = id;
	this.fields = new Array();
	this.disabled = false;
	this.formId = "";
};
FormFieldset.prototype.getId = function () {
	return this.id;
};
FormFieldset.prototype.getHTMLId = function () {
	return this.formId + '_' + this.id;
};
FormFieldset.prototype.setFormId = function (formId) {
	this.formId = formId;
};
FormFieldset.prototype.addField = function (field) {
	this.fields.push(field);
	field.setFormId(this.formId);
};
FormFieldset.prototype.getField = function (id) {
	var field = null;
	this.fields.each(function(aField) {
		if (aField.getId() == id) {
			field = aField;
			throw $break;
		}
	});
	return field;
};
FormFieldset.prototype.getFields = function () {
	return this.fields;
};
FormFieldset.prototype.hasField = function (id) {
	var hasField = false;
	this.fields.each(function(field) {
		if (field.getId() == id) {
			hasField = true;
			throw $break;
		}
	});
	return hasField;
};
FormFieldset.prototype.enable = function () {
	this.disabled = false;
	jQuery("#" + this.getHTMLId()).fadeIn();
	this.fields.each(function(field) {
		field.enable();
	});
};
FormFieldset.prototype.disable = function () {
	this.disabled = true;
	jQuery("#" + this.getHTMLId()).fadeOut();
	this.fields.each(function(field) {
		field.disable();
	});
};
FormFieldset.prototype.isDisabled = function () {
	return this.disabled;
};

// This represents a field. It can be overloaded to fit to different fields
// types
function FormField(id){
	this.id = id;
	this.validationMessageEnabled = false;
	this.hasConstraints = false;
	this.formId = "";
};
FormField.prototype.getId = function () {
	return this.id;
};
FormField.prototype.getHTMLId = function () {
	return this.formId + '_' + this.id;
};
FormField.prototype.setFormId = function (formId) {
	this.formId = formId;
};
FormField.prototype.HTMLFieldExists = function () {
	return jQuery('#' + this.getHTMLId()) != null;
};
FormField.prototype.enable = function () {
	if (this.HTMLFieldExists()) {
		Field.enable(this.getHTMLId());
	}
	jQuery("#" + this.getHTMLId() + "_field").fadeIn(300);
	this.liveValidate();
};
FormField.prototype.disable = function () {
	if (this.HTMLFieldExists()) {
		Field.disable(this.getHTMLId());
	}
	jQuery("#" + this.getHTMLId() + "_field").fadeOut(300);
	this.clearErrorMessage();
};
FormField.prototype.isDisabled = function () {
	if (this.HTMLFieldExists()) {
		var element = jQuery('#' + this.getHTMLId())[0];
		var disabled = element.disabled != "disabled" && element.disabled != false;
		if (disabled == false) {
			var field = jQuery('#' + this.getHTMLId() + '_field')[0];
			if (field) {
				var display = field.style.display;
				if (display != null) {
					return display == "none";
				} else {
					return false;
				}
			} else {
				var display = element.style.display;
				if (display != null) {
					return display == "none";
				} else {
					return false;
				}
			}
		}
		return true;
	}
	return false;
};
FormField.prototype.getValue = function () {
	var field = jQuery('#' + this.getHTMLId());
	if (field.is(":checkbox")){
		return field.prop("checked");
	}
	else {
		return field.val();
	}
};
FormField.prototype.setValue = function (value) {
	jQuery('#' + this.getHTMLId()).val(value);
};
FormField.prototype.enableValidationMessage = function () {
	this.validationMessageEnabled = true;
};
FormField.prototype.displayErrorMessage = function (message) {
	if (!this.validationMessageEnabled) {
		return;
	}
	
	if (jQuery('#' + this.getHTMLId() + '_field') && jQuery('#onblurContainerResponse' + this.getHTMLId())) {
		
		jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-right').addClass('constraint-status-error');
		jQuery('#onblurMessageResponse' + this.getHTMLId()).text(message);
		
		jQuery("#onblurMessageResponse" + this.getHTMLId()).fadeIn(500);
	}
};
FormField.prototype.displaySuccessMessage = function () {
	if (!this.validationMessageEnabled) {
		return;
	}
	
	if (jQuery('#' + this.getHTMLId() + '_field') && jQuery('#onblurContainerResponse' + this.getHTMLId())) {
		
		jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-error').addClass('constraint-status-right');
		jQuery("#onblurMessageResponse" + this.getHTMLId()).fadeOut(200);
	}
};
FormField.prototype.clearErrorMessage = function () {
	if (jQuery('#' + this.getHTMLId() + '_field') && jQuery('#onblurContainerResponse' + this.getHTMLId())) {

		jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-right').addClass('constraint-status-error');
		jQuery('#onblurMessageResponse' + this.getHTMLId()).text('');
		jQuery("#onblurMessageResponse" + this.getHTMLId()).fadeOut(200);
	}
};
FormField.prototype.liveValidate = function () {
	if (!this.isDisabled() && this.hasConstraints) {
		var errorMessage = this.doValidate();
		if (errorMessage != "") {
			this.displayErrorMessage(errorMessage);
		} else {
			this.displaySuccessMessage();
		}
	};
};
FormField.prototype.validate = function () {
	if (!this.isDisabled() && this.hasConstraints) {
		var errorMessage = this.doValidate();
		if (errorMessage != "") {
			this.enableValidationMessage();
			this.displayErrorMessage(errorMessage);
		} 
		return errorMessage;
	}
	return "";
};
FormField.prototype.doValidate = function () {
	return '';
};