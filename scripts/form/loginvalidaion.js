let inputs = document.querySelectorAll("input");
let errorTexts = document.querySelectorAll(".error-text");
let emailInput = document.querySelector(".login--email");
let passwordInput = document.querySelector(".password");

let loginBtn = document.querySelector(".submit-btn");



loginBtn.addEventListener("click", e => {
    if (isValid()) {
        validateCredientials(loginBtn, emailInput.value, passwordInput.value);
    }
});


function isValid() {
    isValidResult = true;

    inputs.forEach(input => {
        if (input.type != "radio") {

            let isFilled = requiredValidator(input);
            let elementId = input.attributes["data-id"].value;
            if (!isFilled) {
                getCorrespondingErrorText(errorTexts, elementId, errorMessages.blankMessage);
                input.focus();
                input.classList.add("error-inp");
                isValidResult = false;
            } else {
                hideErrorText(errorTexts, elementId);
                input.classList.remove("error-inp");
            }
        }
    });

    //EMAIL validations
    let elementId = emailInput.attributes["data-id"].value;
    if (!emailValidator(emailInput)) {
        getCorrespondingErrorText(errorTexts, elementId, errorMessages.emailMessage);
        emailInput.focus();
        emailInput.classList.add("error-inp");
        isValidResult = false;
    } else {
        hideErrorText(errorTexts, elementId);
        emailInput.classList.remove("error-inp");
    }



    return isValidResult;
}


function validateCredientials(btnElement, email, password) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            removeLoadingStateWithText(btnElement, "Submit");
            var result = JSON.parse(this.responseText);
            if (result.status == "success") {
                window.location = result.link;
            } else {
                showAlert(result.message, result.status);
            }
        }
    };
    addLoadingStateWithText(btnElement, "Validating Credentials...");
    xhttp.open("POST", "services/authentication/loginCheck.php", true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send("password=" + password + "&email=" + email);
}