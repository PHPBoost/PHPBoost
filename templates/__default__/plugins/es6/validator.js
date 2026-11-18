/**
 * #### Constraints ####
 */
const integerIntervalValidator = (value, lbound, rbound) => {
    const parsedValue = parseInt(value, 10);
    return parsedValue === value && parsedValue >= lbound && parsedValue <= rbound;
};

const integerMinValidator = (value, lbound) => {
    const parsedValue = parseInt(value, 10);
    return parsedValue === value && parsedValue >= lbound;
};

const integerMaxValidator = (value, rbound) => {
    const parsedValue = parseInt(value, 10);
    return parsedValue === value && parsedValue <= rbound;
};

const lengthIntervalValidator = (value, lbound, rbound) => {
    const length = value.length;
    return length >= lbound && length <= rbound;
};

const lengthMinValidator = (value, lbound) => {
    return value.length >= lbound;
};

const lengthMaxValidator = (value, rbound) => {
    return value.length <= rbound;
};

const notEmptyFormFieldValidator = (fieldId, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (parseInt(value, 10) !== 0 && (!value || value === '' || value === false)) {
            return message;
        }
    }
    return '';
};

const minPossibleValuesFormFieldValidator = (htmlId, minInput, message) => {
    const fieldsInput = Array.from(document.querySelectorAll(`input[id^=field_name_${htmlId}]`))
        .map(input => input.value)
        .filter(value => value);
    return fieldsInput.length < minInput ? message : '';
};

const maxPossibleValuesFormFieldValidator = (htmlId, maxInput, message) => {
    const fieldsInput = Array.from(document.querySelectorAll(`input[id^=field_name_${htmlId}]`))
        .map(input => input.value)
        .filter(value => value);
    return fieldsInput.length > maxInput ? message : '';
};

const uniquePossibleValuesFormFieldValidator = (htmlId, message) => {
    const fieldsInput = Array.from(document.querySelectorAll(`input[id^=field_name_${htmlId}]`))
        .map(input => input.value)
        .filter(value => value);
    const uniqueFieldsInput = Array.from(new Set(fieldsInput));
    return fieldsInput.length !== uniqueFieldsInput.length ? message : '';
};

const regexFormFieldValidator = (fieldId, regexPattern, options, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '') {
            const regex = new RegExp(regexPattern, options);
            if (!regex.test(value)) {
            return message;
            }
        }
    }
    return '';
};

const integerIntervalFormFieldValidator = (fieldId, lbound, rbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !integerIntervalValidator(value, lbound, rbound)) {
            return message;
        }
    }
    return '';
};

const integerMinFormFieldValidator = (fieldId, lbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !integerMinValidator(value, lbound)) {
            return message;
        }
    }
    return '';
};

const integerMaxFormFieldValidator = (fieldId, rbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !integerMaxValidator(value, rbound)) {
            return message;
        }
    }
    return '';
};

const lengthIntervalFormFieldValidator = (fieldId, lbound, rbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !lengthIntervalValidator(value, lbound, rbound)) {
            return message;
        }
    }
    return '';
};

const lengthMinFormFieldValidator = (fieldId, lbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !lengthMinValidator(value, lbound)) {
            return message;
        }
    }
    return '';
};

const lengthMaxFormFieldValidator = (fieldId, rbound, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '' && !lengthMaxValidator(value, rbound)) {
            return message;
        }
    }
    return '';
};

const maxSizeFilePickerFormFieldValidator = (fieldId, maxSize, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field && document.getElementById(field.getHTMLId()).files[0]) {
        const value = document.getElementById(field.getHTMLId()).files[0].size;
        if (value !== '' && !integerMaxValidator(value, maxSize)) {
            return message;
        }
    }
    return '';
};

const displayNameExistValidator = async (fieldId, message, userId) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        const response = await fetch(`${PATH_TO_ROOT}/kernel/framework/ajax/user_xmlhttprequest.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ display_name: value, user_id: userId, token: TOKEN }),
        });
        const returnData = await response.text();
        return returnData === '1' ? message : '';
    }
    return '';
};

const loginExistValidator = async (fieldId, message, userId) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        const response = await fetch(`${PATH_TO_ROOT}/kernel/framework/ajax/user_xmlhttprequest.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ login: value, user_id: userId, token: TOKEN }),
        });
        const returnData = await response.text();
        return returnData === '1' ? message : '';
    }
    return '';
};

const mailExistValidator = async (fieldId, message, userId) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        const response = await fetch(`${PATH_TO_ROOT}/kernel/framework/ajax/user_xmlhttprequest.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ mail: value, user_id: userId, token: TOKEN }),
        });
        const returnData = await response.text();
        return returnData === '1' ? message : '';
    }
    return '';
};

const userExistValidator = async (fieldId, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '') {
            const response = await fetch(`${PATH_TO_ROOT}/kernel/framework/ajax/user_xmlhttprequest.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ display_name: value, token: TOKEN }),
            });
            const returnData = await response.text();
            return returnData !== '1' ? message : '';
        }
    }
    return '';
};

const urlExistsValidator = async (fieldId, message) => {
    const field = HTMLForms.getField(fieldId);
    if (field) {
        const value = field.getValue();
        if (value !== '') {
            const response = await fetch(`${PATH_TO_ROOT}/kernel/framework/ajax/dispatcher.php?url=/url_validation/`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ url_to_check: value, token: TOKEN }),
            });
            const returnData = await response.json();
            return returnData.is_valid !== 1 ? message : '';
        }
    }
    return '';
};

/**
 * #### Multiple Field Constraints ####
 */
const equalityFormFieldValidator = (fieldId, fieldId2, message) => {
    const field1 = HTMLForms.getField(fieldId);
    const field2 = HTMLForms.getField(fieldId2);
    if (field1 && field2) {
        if (field1.getValue() !== field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
            return message;
        }
    }
    return '';
};

const inequalityFormFieldValidator = (fieldId, fieldId2, message) => {
    const field1 = HTMLForms.getField(fieldId);
    const field2 = HTMLForms.getField(fieldId2);
    if (field1 && field2) {
        if (field1.getValue() === field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
            return message;
        }
    }
    return '';
};

const inclusionFormFieldValidator = (fieldId, fieldId2, message) => {
    const field1 = HTMLForms.getField(fieldId);
    const field2 = HTMLForms.getField(fieldId2);
    if (field1 && field2) {
        const value1 = field1.getValue().toLowerCase();
        const value2 = field2.getValue().toLowerCase();
        if (value1 !== '' && value2 !== '' && value2.includes(value1)) {
            return message;
        }
    }
    return '';
};
