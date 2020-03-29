// This contains all the HTML forms contained in the page
function HTMLForms(){}
HTMLForms.forms = new Array();

HTMLForms.add = function(form) {
	return HTMLForms.forms.push(form);
};
HTMLForms.get = function(id) {
	var form = null;
	jQuery.each(HTMLForms.forms, function(index, aForm) {
		if (aForm.getId() == id) {
			form = aForm;
			return false;
		}
	});
	return form;
};
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
};
HTMLForms.getFieldset = function(id) {
	var fieldset = null;
	jQuery.each(HTMLForms.forms, function(index, form) {
		var aFieldset = form.getFieldset(id);
		if (aFieldset != null) {
			fieldset = aFieldset;
			return false;
		}
	});
	return fieldset;
};
HTMLForms.getField = function(id) {
	var field = null;
	jQuery.each(HTMLForms.forms, function(index, form) {
		var aField = form.getField(id);
		if (aField != null) {
			field = aField;
			return false;
		}
	});
	return field;
};

// Shortcuts
var $HF = HTMLForms.get;
var $FFS = HTMLForms.getFieldset;
var $FF = HTMLForms.getField;

// This represents a HTML form.
var HTMLForm = function(id){
	this.id = id;
	this.fieldsets = new Array();
};

HTMLForm.prototype = {
	getId : function() {
		return this.id;
	},
	addFieldset : function(fieldset) {
		this.fieldsets.push(fieldset);
		fieldset.setFormId(this.id);
	},
	getFieldset : function(id) {
		var fieldset = null;
		jQuery.each(this.fieldsets, function(index, aFieldset) {
			if (aFieldset.getId() == id) {
				fieldset = aFieldset;
				return false;
			}
		});
		return fieldset;
	},
	getFieldsets : function() {
		return this.fieldsets;
	},
	hasFieldset : function(id) {
		var hasFieldset = false;
		jQuery.each(this.fieldsets, function(index, aFieldset) {
			if (aFieldset.getId() == id) {
				hasFieldset = true;
				return false;
			}
		});
		return hasFieldset;
	},
	getFields : function() {
		var fields = new Array();
		jQuery.each(this.fieldsets, function(index, fieldset) {
			jQuery.each(fieldset.getFields(), function(index, field) {
				fields.push(field);
			});
		});
		return fields;
	},
	getField : function(id) {
		var field = null;
		jQuery.each(this.getFields(), function(index, aField) {
			if (aField.getId() == id) {
				field = aField;
				return false;
			}
		});
		return field;
	},
	validate : function() {
		var validated = true;
		var validation = '';
		jQuery.each(this.getFields(), function(index, field) {
			var field_validation = field.validate();
			
			if (field_validation != "") {
				validation = validation + '\n\n' + field_validation;
				validated = false;
			}
		});
		
		if (validated == false) {
			this.displayValidationError(validation);
			jQuery('html, body').animate({scrollTop:jQuery('#' + this.id).offset().top}, 'slow');
		}
		
		this.registerDisabledFields();
		return validated;
	},
	displayValidationError : function(message) {
		message = message.replace(/&quot;/g, '"');
		message = message.replace(/&amp;/g, '&');
		alert(message);
	},
	registerDisabledFields : function() {
		var disabledFields = "";
		jQuery.each(this.getFields(), function(index, field) {
			if (field.isDisabled()) {
				disabledFields += "|" + field.getId();
			}
		});
		jQuery('#' + this.id + '_disabled_fields').val(disabledFields);

		var disabledFieldsets = "";
		jQuery.each(this.getFieldsets(), function(index, fieldset) {
			if (fieldset.isDisabled()) {
				disabledFieldsets += "|" + fieldset.getId();
			}
		});
		jQuery('#' + this.id + '_disabled_fieldsets').val(disabledFieldsets);
	}
};


// This represents a fieldset
var FormFieldset = function(id){
	this.id = id;
	this.fields = new Array();
	this.disabled = false;
	this.formId = "";
};

FormFieldset.prototype = {
	getId : function() {
		return this.id;
	},
	getHTMLId : function() {
		return this.formId + '_' + this.id;
	},
	setFormId : function(formId) {
		this.formId = formId;
	},
	addField : function(field) {
		this.fields.push(field);
		field.setFormId(this.formId);
	},
	getField : function(id) {
		var field = null;
		jQuery.each(this.fields, function(index, aField) {
			if (aField.getId() == id) {
				field = aField;
				return false;
			}
		});
		return field;
	},
	getFields : function() {
		return this.fields;
	},
	hasField : function(id) {
		var hasField = false;
		jQuery.each(this.fields, function(index, field) {
			if (field.getId() == id) {
				hasField = true;
				return false;
			}
		});
		return hasField;
	},
	enable : function() {
		this.disabled = false;
		jQuery("#" + this.getHTMLId()).fadeIn();
		jQuery.each(this.fields, function(index, field) {
			field.enable();
		});
	},
	disable : function() {
		this.disabled = true;
		jQuery("#" + this.getHTMLId()).fadeOut();
		jQuery.each(this.fields, function(index, field) {
			field.disable();
		});
	},
	isDisabled : function() {
		return this.disabled;
	}
};

// This represents a field. It can be overloaded to fit to different fields
// types
var FormField = function(id){
	this.id = id;
	this.validationMessageEnabled = false;
	this.hasConstraints = false;
	this.formId = "";
};

FormField.prototype = {
	getId : function() {
		return this.id;
	},
	getHTMLId : function() {
		return this.formId + "_" + this.id;
	},
	setFormId : function(formId) {
		this.formId = formId;
	},
	HTMLFieldExists : function() {
		return jQuery('#' + this.getHTMLId()).length > 0;
	},
	enable : function() {
		if (this.HTMLFieldExists()) {
			jQuery('#' + this.getHTMLId()).prop('disabled', false);
		}
		jQuery("#" + this.getHTMLId() + "_field").fadeIn();
		this.liveValidate();
	},
	disable : function() {
		if (this.HTMLFieldExists()) {
			jQuery('#' + this.getHTMLId()).prop('disabled', true);
		}
		jQuery("#" + this.getHTMLId() + "_field").fadeOut();
		this.clearErrorMessage();
	},
	isDisabled : function() {
		if (this.HTMLFieldExists()) {
			var element = jQuery('#' + this.getHTMLId());
			var disabled = element.prop('disabled');
			if (disabled == false) {
				var field = jQuery('#' + this.getHTMLId() + '_field');
				if (field) {
					var display = field.css('display');
					if (display != null) {
						return display == "none";
					} else {
						return false;
					}
				} else {
					var display = element.css('display');
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
	},
	getValue : function() {
		if (!this.HTMLFieldExists()) {
			alert(this.getHTMLId() + ' not exists, use get_js_specialization_code function in FormField and return field.getValue JS function contain the value');
		}

		var field = jQuery('#' + this.getHTMLId());
		if (field.is(":checkbox")){
			return field.prop("checked");
		}
		else {
			return field.val();
		}
	},
	setValue : function(value) {
		var field = jQuery('#' + this.getHTMLId());
		if (field.is(":checkbox")){
			return field.prop("checked", value);
		}
		else {
			return field.val(value);
		}
	},
	enableValidationMessage : function() {
		this.validationMessageEnabled = true;
	},
	displayErrorMessage : function(message) {
		if (!this.validationMessageEnabled) {
			return;
		}
		
		if (jQuery('#' + this.getHTMLId() + '_field').length && jQuery('#onblurContainerResponse' + this.getHTMLId()).length) {
			
			jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-right').addClass('constraint-status-error');
			jQuery('#onblurMessageResponse' + this.getHTMLId()).html(message);
			
			jQuery("#onblurMessageResponse" + this.getHTMLId()).fadeIn(500);
		}
	},
	displaySuccessMessage : function() {
		if (!this.validationMessageEnabled) {
			return;
		}
		
		if (jQuery('#' + this.getHTMLId() + '_field').length && jQuery('#onblurContainerResponse' + this.getHTMLId()).length) {
			
			jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-error').addClass('constraint-status-right');
			jQuery("#onblurMessageResponse" + this.getHTMLId()).hide();
		}
	},
	clearErrorMessage : function() {
		if (jQuery('#' + this.getHTMLId() + '_field').length && jQuery('#onblurContainerResponse' + this.getHTMLId()).length) {

			jQuery('#' + this.getHTMLId() + '_field').removeClass('constraint-status-right').removeClass('constraint-status-error');
			jQuery('#onblurMessageResponse' + this.getHTMLId()).html('');
			jQuery("#onblurMessageResponse" + this.getHTMLId()).fadeOut(200);
		}
	},
	liveValidate : function() {
		if (!this.isDisabled() && this.hasConstraints) {
			var errorMessage = this.doValidate();
			if (errorMessage != "") {
				this.displayErrorMessage('<i class="fa fa-minus-circle"></i> ' + errorMessage);
			} else {
				this.displaySuccessMessage();
			}
		}
	},
	validate : function() {
		if (!this.isDisabled() && this.hasConstraints) {
			var errorMessage = this.doValidate();
			if (errorMessage != "") {
				this.enableValidationMessage();
				this.displayErrorMessage('<i class="fa fa-minus-circle"></i> ' + errorMessage);
			} 
			return errorMessage;
		}
		return "";
	},
	doValidate : function() {
		return '';
	}
};

jQuery(document).ready(function() {
	//Validation delete on input or textarea or select focus
	jQuery('input,textarea,select').focus(function() {
		jQuery(this).parent().parent().removeClass('constraint-status-error').removeClass('constraint-status-right');
		jQuery(this).parent().children('.text-status-constraint').hide();
	});
	
	//Allow only valid characters in date inputs
	jQuery('input[class="input-date"]').keyup(function() {
		var testCaretPattern = new RegExp("^[0-9-]$");
		
		var val = jQuery(this).val();
		var index = this.selectionStart - 1;
		
		if (!testCaretPattern.test(val.charAt(index))) {
			jQuery(this).val(val.substr(0, (index)) + val.substr(index + 1));
		}
	});
	
	//Allow only valid characters in number inputs
	jQuery('input[type="number"]').keyup(function() {
		var testValPattern = new RegExp("^[0-9]+([\.|,]([0-9]{1,2})?)?$");
		var testCaretPattern = new RegExp("^[0-9\.,]$");
		
		var val = jQuery(this).val();
		
		if (!testCaretPattern.test(val.charAt(val.length - 1)) || !testValPattern.test(val)) {
			jQuery(this).val(val.slice(0, -1));
		}
	});
	
	//Allow only valid characters in tel inputs
	jQuery('input[type="tel"]').keyup(function() {
		var testCaretPattern = new RegExp("^[0-9-\+ ]$");
		
		var val = jQuery(this).val();
		var index = this.selectionStart - 1;
		
		if (!testCaretPattern.test(val.charAt(index))) {
			jQuery(this).val(val.substr(0, (index)) + val.substr(index + 1));
		}
	});
});
