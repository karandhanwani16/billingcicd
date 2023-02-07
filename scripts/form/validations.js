let inputs = document.querySelectorAll("input");
    
let normalInputs = document.querySelectorAll("input[type='text']");
let errorTexts = document.querySelectorAll(".error-text");
let emailInputs = document.querySelectorAll("input[type='email']");
let radioInputs = document.querySelectorAll("input[type='radio']");
let passwordInput = document.querySelector(".password");
let confirmPasswordInput = document.querySelector(".confirmpassword");
let dateInput = document.querySelector("input[type='date']");


function getCorrespondingErrorText(error_array,element_id,error_text) {
    error_array.forEach(error_element => {
        let attributeValue=error_element.attributes["data-id"];
        if (attributeValue) {
            if (attributeValue.value==element_id) {
                error_element.innerHTML=error_text;
                error_element.style.display="block";
            }
            
        }
    });
}

let errorMessages =  {
blankMessage:"Cannot leave this field blank",
emailMessage:"Please enter valid email address",
passwordMessage:"Password must be same",
};


function hideErrorText(error_array,element_id) {
    error_array.forEach(error_element => {
        let attributeValue=error_element.attributes["data-id"];
        if (attributeValue) {
            if (attributeValue.value==element_id) {
                error_element.style.display="none";
            }
        }
    });
}

function isValid() {
    isValidResult = true;

    inputs.forEach(input => {
        if (input.type!="radio") {
            
            let isFilled = requiredValidator(input);
            let elementId = input.attributes["data-id"].value;
            if (!isFilled) {
                getCorrespondingErrorText(errorTexts,elementId,errorMessages.blankMessage);
                input.focus();
                input.classList.add("error-inp");
                isValidResult = false;
            }
            else{
                hideErrorText(errorTexts,elementId);
                input.classList.remove("error-inp");
            }
        }
    });

    let radioSelected = false;
    document.getElementsByName("gender").forEach(radioElement => {
        if (radioElement.checked) {
            radioSelected = true;
        }
    });
    if (!radioSelected) {
        document.getElementById("errorgender").style.display="block";
        isValidResult = false;
    }else{
        document.getElementById("errorgender").style.display="none";
    }
    
    if (passwordInput.value!=confirmPasswordInput.value) {
        confirmPasswordInput.focus();
        document.getElementById("txtconfirmpassword").innerHTML="Passwords must match";
        document.getElementById("txtconfirmpassword").style.display="block";
        isValidResult = false;
    }else{
        document.getElementById("txtconfirmpassword").style.display="none";
    }


    let passwordValidation = validatePassword(passwordInput.value);
              if (!passwordValidation.isValid) {
                  passwordInput.focus();
                  isValidResult=false;
              }
    return isValidResult;
}

let errorMessagesCont = document.querySelector(".error-messages-cont");

passwordInput.addEventListener("keyup", e=>{
        let input_value = passwordInput.value;
         if (input_value != "") {
              let passwordValidation = validatePassword(input_value);
              if (passwordValidation.isValid) {
                errorMessagesCont.style.display = "none";
                passwordInput.classList.add("validated-inp");
                passwordInput.classList.remove("error-inp");
              }else{
                errorMessagesCont.style.display = "block";

                errorMessagesCont.innerHTML = "";
                for (var i = 0; i < passwordValidation.passwordErrors.length; i++) {
                    errorMessagesCont.innerHTML += "<div class='error'>"+passwordValidation.passwordErrors[i]+"</div>";
                }

                  passwordInput.classList.add("error-inp");
                passwordInput.classList.remove("validated-inp");
                }
         }
    });
