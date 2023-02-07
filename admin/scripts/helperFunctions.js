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

function getCorrespondingErrorText(error_array, element_id, error_text) {
    error_array.forEach(error_element => {
        let attributeValue = error_element.attributes["data-id"];
        if (attributeValue) {
            if (attributeValue.value == element_id) {
                error_element.innerHTML = error_text;
                error_element.style.display = "block";
            }

        }
    });
}

let errorMessages = {
    blankMessage: "Cannot leave this field blank",
    emailMessage: "Please enter valid email address",
    passwordMessage: "Password must be same",
};


function hideErrorText(error_array, element_id) {
    error_array.forEach(error_element => {
        let attributeValue = error_element.attributes["data-id"];
        if (attributeValue) {
            if (attributeValue.value == element_id) {
                error_element.style.display = "none";
            }
        }
    });
}



//alert start
let alertCont = document.querySelector(".alert--cont");

function showAlert(alert_text, alert_type) {
    alertCont.innerHTML = `<div class="alert ${alert_type}">${alert_text}</div>`;
    let sampleVar = setTimeout(hideAlert, 2500);
}

function showAlert2(alert_texts, alert_types) {
    let mainString = "";
    let marginTop = 12;
    for (let i = 0; i < alert_texts.length; i++) {
        mainString += `<div style='top:${marginTop}px' class="alert ${alert_types[i]}">${alert_texts[i]}</div>`;
        marginTop = marginTop + 60;
    }
    alertCont.innerHTML = mainString;

    // let sampleVar = setTimeout(hideAlert, 10000);
}


function hideAlert() {
    alertCont.innerHTML = "";
}
//alert end


//add loading state


const addLoadingState = (element) => {
    element.classList.add("loading--btn");
    element.innerHTML = "uploading...";
};
const removeLoadingState = (element) => {
    element.classList.remove("loading--btn");
    element.innerHTML = "Submit";
};

const addLoadingStateWithText = (element, text) => {
    element.classList.add("loading--btn");
    element.innerHTML = text;
};
const removeLoadingStateWithText = (element, text) => {
    element.classList.remove("loading--btn");
    element.innerHTML = text;
};



//handle popup

function closePopup() {
    popupCont.style.display = "none";
    popupUnderlay.style.display = "none";
}

function openPopup() {
    popupCont.style.display = "block";
    popupUnderlay.style.display = "block";
}



// adding year and month ddl logic

let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const d = new Date();

function loadMonthData() {
    let monthDdl = document.querySelector("#ddlsalemonth");
    let currentMonth = d.getMonth();
    monthDdl.innerHTML = "<option value=''>Select Month</option>";
    for (let i = 0; i < months.length; i++) {
        if (i == currentMonth) {
            monthDdl.innerHTML += `<option selected value="${i + 1}">${months[i]}</option>`;
        } else {
            monthDdl.innerHTML += `<option value="${i + 1}">${months[i]}</option>`;
        }
    }
}

function loadYearData() {
    let currentYear = d.getFullYear();
    let yearDdl = document.querySelector("#ddlsaleyear");
    let bandwidth = 3;
    let startYear = currentYear - bandwidth;
    let endYear = currentYear + bandwidth;
    yearDdl.innerHTML = "<option value=''>Select Year</option>";
    for (let i = startYear; i <= endYear; i++) {
        if (i == currentYear) {
            yearDdl.innerHTML += `<option selected value="${i}">${i}</option>`;
        } else {
            yearDdl.innerHTML += `<option value="${i}">${i}</option>`;
        }
    }
}


function refreshPage() {
    setTimeout(function () {
        location.reload();
    }, 1500);
}

const removeSpecialCharacter = (string) => string.replace(/[^a-zA-Z ]/g, "");


function loadDataIntoDdlGeneral(data, dropdownList, selectEmptyText, type, selectedValue) {
    dropdownList.innerHTML = "<option value=''>" + selectEmptyText + "</option>";

    if (selectedValue !== "") {
        data.forEach(entry => {
            if (entry[0] == selectedValue) {
                dropdownList.innerHTML += `<option selected='true' value="${entry[0]}">${entry[1]}</option>`;
            } else {
                dropdownList.innerHTML += `<option value="${entry[0]}">${entry[1]}</option>`;
            }
        });
    } else {
        data.forEach(entry => {
            dropdownList.innerHTML += `<option value="${entry[0]}">${entry[1]}</option>`;
        });
    }
}