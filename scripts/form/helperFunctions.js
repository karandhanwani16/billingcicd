function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function requiredValidator(input_element) {
    if (input_element.value == "") {
        return false;
    }
    return true;
}

function emailValidator(input_element) {
    if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(input_element.value)) {
        return true;
    }
    return false;
}

function validatePassword(password_input_value) {
    let passwordValidObject = {
        isValid: true,
        passwordErrors: [],
    };

    if (!(/[a-z]/.test(password_input_value))) {
        passwordValidObject.isValid = false;
        passwordValidObject.passwordErrors.push("Password must have atleast one lowercase letter");
    }
    if (!(/[A-Z]/.test(password_input_value))) {
        passwordValidObject.isValid = false;
        passwordValidObject.passwordErrors.push("Password must have atleast one uppercase letter");
    }
    if (!(/[0-9]/.test(password_input_value))) {
        passwordValidObject.isValid = false;
        passwordValidObject.passwordErrors.push("Password must have atleast one numeric value 0-9");
    }
    if (!(/[!@#$%^&*_+-=;:.?]/.test(password_input_value))) {
        passwordValidObject.isValid = false;
        passwordValidObject.passwordErrors.push("Password must have atleast one special character ! @ # $ % ^ & * _ + - = ; : . ?");
    }
    if (password_input_value.length < 6 || password_input_value.length > 32) {
        passwordValidObject.isValid = false;
        passwordValidObject.passwordErrors.push("Password must be between 6 to 32 characters");
    }
    return passwordValidObject;
}