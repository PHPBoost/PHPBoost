class HTMLForms {
    static forms = [];

    static add(form) {
        return this.forms.push(form);
    }

    static get(id) {
        return this.forms.find(form => form.getId() === id);
    }

    static has(id) {
        return this.get(id) !== null;
    }

    static getFieldset(id) {
        for (const form of this.forms) {
            const fieldset = form.getFieldset(id);
            if (fieldset !== null) {
                return fieldset;
            }
        }
        return null;
    }

    static getField(id) {
        for (const form of this.forms) {
            const field = form.getField(id);
            if (field !== null) {
                return field;
            }
        }
        return null;
    }
}

// Shortcuts
const $HF = HTMLForms.get;
const $FFS = HTMLForms.getFieldset;
const $FF = HTMLForms.getField;

class HTMLForm {
    constructor(id) {
        this.id = id;
        this.fieldsets = [];
    }

    getId() {
        return this.id;
    }

    addFieldset(fieldset) {
        this.fieldsets.push(fieldset);
        fieldset.setFormId(this.id);
    }

    getFieldset(id) {
        return this.fieldsets.find(fieldset => fieldset.getId() === id);
    }

    getFieldsets() {
        return this.fieldsets;
    }

    hasFieldset(id) {
        return this.fieldsets.some(fieldset => fieldset.getId() === id);
    }

    getFields() {
        return this.fieldsets.flatMap(fieldset => fieldset.getFields());
    }

    getField(id) {
        return this.getFields().find(field => field.getId() === id);
    }

    validate() {
        let validated = true;
        let validation = '';

        this.getFields().forEach(field => {
            const fieldValidation = field.validate();
            if (fieldValidation !== "") {
                validation += `\n\n${fieldValidation}`;
                validated = false;
            }
        });

        if (!validated) {
            this.displayValidationError(validation);
            document.querySelector('html, body').scrollTop = document.querySelector(`#${this.id}`).offsetTop;
        }

        this.registerDisabledFields();
        return validated;
    }

    displayValidationError(message) {
        message = message.replace(/&quot;/g, '"').replace(/&amp;/g, '&');
        alert(message);
    }

    registerDisabledFields() {
        const disabledFields = this.getFields().filter(field => field.isDisabled()).map(field => field.getId()).join('|');
        document.querySelector(`#${this.id}_disabled_fields`).value = disabledFields;

        const disabledFieldsets = this.getFieldsets().filter(fieldset => fieldset.isDisabled()).map(fieldset => fieldset.getId()).join('|');
        document.querySelector(`#${this.id}_disabled_fieldsets`).value = disabledFieldsets;
    }
}

class FormFieldset {
    constructor(id) {
        this.id = id;
        this.fields = [];
        this.disabled = false;
        this.formId = "";
    }

    getId() {
        return this.id;
    }

    getHTMLId() {
        return `${this.formId}_${this.id}`;
    }

    setFormId(formId) {
        this.formId = formId;
    }

    addField(field) {
        this.fields.push(field);
        field.setFormId(this.formId);
    }

    getField(id) {
        return this.fields.find(field => field.getId() === id);
    }

    getFields() {
        return this.fields;
    }

    hasField(id) {
        return this.fields.some(field => field.getId() === id);
    }

    enable() {
        this.disabled = false;
        document.querySelector(`#${this.getHTMLId()}`).style.display = 'block';
        this.fields.forEach(field => field.enable());
    }

    disable() {
        this.disabled = true;
        document.querySelector(`#${this.getHTMLId()}`).style.display = 'none';
        this.fields.forEach(field => field.disable());
    }

    isDisabled() {
        return this.disabled;
    }
}

class FormField {
    constructor(id) {
        this.id = id;
        this.validationMessageEnabled = false;
        this.hasConstraints = false;
        this.formId = "";
    }

    getId() {
        return this.id;
    }

    getHTMLId() {
        return `${this.formId}_${this.id}`;
    }

    setFormId(formId) {
        this.formId = formId;
    }

    HTMLFieldExists() {
        return document.querySelector(`#${this.getHTMLId()}`) !== null;
    }

    enable() {
        if (this.HTMLFieldExists()) {
            document.querySelector(`#${this.getHTMLId()}`).disabled = false;
        }
        document.querySelector(`#${this.getHTMLId()}_field`).style.display = 'block';
        this.liveValidate();
    }

    disable() {
        if (this.HTMLFieldExists()) {
            document.querySelector(`#${this.getHTMLId()}`).disabled = true;
        }
        document.querySelector(`#${this.getHTMLId()}_field`).style.display = 'none';
        this.clearErrorMessage();
    }

    isDisabled() {
        if (this.HTMLFieldExists()) {
            const element = document.querySelector(`#${this.getHTMLId()}`);
            if (!element.disabled) {
                const field = document.querySelector(`#${this.getHTMLId()}_field`);
                if (field) {
                    return field.style.display === "none";
                } else {
                    return element.style.display === "none";
                }
            }
            return true;
        }
        return false;
    }

    getValue() {
        if (!this.HTMLFieldExists()) {
            alert(`${this.getHTMLId()} not exists, use get_js_specialization_code function in FormField and return field.getValue JS function contain the value`);
        }

        const field = document.querySelector(`#${this.getHTMLId()}`);
        if (field.type === "checkbox") {
            return field.checked;
        } else {
            return field.value || '';
        }
    }

    setValue(value) {
        const field = document.querySelector(`#${this.getHTMLId()}`);
        if (field.type === "checkbox") {
            field.checked = value;
        } else {
            field.value = value;
        }
    }

    enableValidationMessage() {
        this.validationMessageEnabled = true;
    }

    displayErrorMessage(message) {
        if (!this.validationMessageEnabled) return;

        const field = document.querySelector(`#${this.getHTMLId()}_field`);
        const messageContainer = document.querySelector(`#onblurMessageResponse${this.getHTMLId()}`);
        if (field && messageContainer) {
            field.classList.remove('constraint-status-right');
            field.classList.add('constraint-status-error');
            messageContainer.innerHTML = message;
            messageContainer.style.display = 'block';
        }
    }

    displaySuccessMessage() {
        if (!this.validationMessageEnabled) return;

        const field = document.querySelector(`#${this.getHTMLId()}_field`);
        const messageContainer = document.querySelector(`#onblurMessageResponse${this.getHTMLId()}`);
        if (field && messageContainer) {
            field.classList.remove('constraint-status-error');
            field.classList.add('constraint-status-right');
            messageContainer.style.display = 'none';
        }
    }

    clearErrorMessage() {
        const field = document.querySelector(`#${this.getHTMLId()}_field`);
        const messageContainer = document.querySelector(`#onblurMessageResponse${this.getHTMLId()}`);
        if (field && messageContainer) {
            field.classList.remove('constraint-status-right', 'constraint-status-error');
            messageContainer.innerHTML = '';
            messageContainer.style.display = 'none';
        }
    }

    liveValidate() {
        if (!this.isDisabled() && this.hasConstraints) {
            const errorMessage = this.doValidate();
            if (errorMessage !== "") {
            this.displayErrorMessage(`<i class="fa fa-minus-circle"></i>&nbsp;${errorMessage}`);
            } else {
            this.displaySuccessMessage();
            }
        }
    }

    validate() {
        if (!this.isDisabled() && this.hasConstraints) {
            const errorMessage = this.doValidate();
            if (errorMessage !== "") {
            this.enableValidationMessage();
            this.displayErrorMessage(`<i class="fa fa-minus-circle"></i>&nbsp;${errorMessage}`);
            }
            return errorMessage;
        }
        return "";
    }

    doValidate() {
        return '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input, textarea, select').forEach(element => {
        element.addEventListener('focus', () => {
            const parent = element.parentElement.parentElement;
            parent.classList.remove('constraint-status-error', 'constraint-status-right');
            parent.querySelector('.text-status-constraint').style.display = 'none';
        });
    });

    document.querySelectorAll('input.input-date').forEach(element => {
        element.addEventListener('keyup', () => {
            const testCaretPattern = /^[0-9-]$/;
            const val = element.value;
            const index = element.selectionStart - 1;

            if (!testCaretPattern.test(val.charAt(index))) {
                element.value = val.substr(0, index) + val.substr(index + 1);
            }
        });
    });

    document.querySelectorAll('input[type="number"]').forEach(element => {
        element.addEventListener('keyup', () => {
            const testValPattern = /^[0-9]+([\.|,]([0-9]{1,2})?)?$/;
            const testCaretPattern = /^[0-9\.,]$/;
            const val = element.value;

            if (!testCaretPattern.test(val.charAt(val.length - 1)) || !testValPattern.test(val)) {
                element.value = val.slice(0, -1);
            }
        });
    });

    document.querySelectorAll('input[type="tel"]').forEach(element => {
        element.addEventListener('keyup', () => {
            const testCaretPattern = /^[0-9-\+ ]$/;
            const val = element.value;
            const index = element.selectionStart - 1;

            if (!testCaretPattern.test(val.charAt(index))) {
                element.value = val.substr(0, index) + val.substr(index + 1);
            }
        });
    });
});