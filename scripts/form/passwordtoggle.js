//this script is to toggle password icon and textmode
let passwordToggle = document.querySelector(".password-toggle");
let password = document.querySelector(".password");
let confirmPassword = document.querySelector(".confirmpassword");

let iconUrl = {
            showpassword:"assets/icons/show-password.svg",
            hidepassword:"assets/icons/hide-password.svg"
        };

let isPasswordVisible = false;



    passwordToggle.addEventListener("click",e =>{
        let toggleType = passwordToggle.attributes["data-type"].value;

        if (isPasswordVisible) {
            passwordToggle.src = iconUrl.showpassword;
            password.type = "password";
            if (toggleType=="signup") {
                confirmPassword.type = "password";
            }
            isPasswordVisible = false;
        }else{
            passwordToggle.src = iconUrl.hidepassword;
            password.type = "text";
            if (toggleType=="signup") {
                confirmPassword.type = "text";
            }
            isPasswordVisible = true;
        } 
    });
