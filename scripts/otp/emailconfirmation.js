let otpInputs = document.querySelectorAll(".otp-inp");
let submitBtn = document.querySelector(".submit-btn");
let resendBtn = document.querySelector(".resend-btn");

let currentInput = 0;

otpInputs.forEach(inp => {
    let arrayInputId = parseInt(inp.attributes["data-id"].value);
    if (arrayInputId == currentInput) {
       inp.focus();
    }
    inp.addEventListener("keyup",e => {
          var key = e.keyCode || e.charCode;
          //if key is not backspace
          if (key != 8) {
              transferFocus(inp.attributes["data-id"].value,otpInputs);
          }
    });
  });
  
  function transferFocus(current_input_id,otp_array){
      otp_array.forEach(i => {
          
    if (parseInt(i.attributes["data-id"].value)  == (parseInt(current_input_id) + 1 )) {
        i.focus();
    }
  });
}

submitBtn.addEventListener("click",e => {
  let isOtpInputValid = isValid();
  if (isOtpInputValid) {
      let finalOtp = "" ;
      otpInputs.forEach(i => {
          finalOtp += i.value;
      });
      // let isOtpValid = parseInt(validateEmailOtp(finalOtp,selectedEmail));
      validateEmailOtp(finalOtp,selectedEmail);
      
  }
});

resendBtn.addEventListener("click",e => {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          let result = this.responseText;
          if (parseInt(result) == 0) {
           alert("cannot resend OTP");
          }else {
              location.reload();
          }
      }
  };
  xhttp.open("GET","services/authentication/emailOtpResend.php?email="+selectedEmail, true);
  xhttp.send();
});
 
function validateEmailOtp(otp,email) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    let isOtpValid = parseInt(this.responseText);
    if (isOtpValid == 1) {
            window.location = "phoneverification.php?phone="+mainPhoneVariable;
        }else{
            //if the otp is invalid or
            alert("otp is invalid");
        }
    }
    };
    xhttp.open("GET","services/authentication/emailOtpValidation.php?otp="+otp+"&email="+email, true);
    xhttp.send();
}

function isValid() {

  isValidResult = true;
  let isOtpEmpty = false;
  otpInputs.forEach(i => {
      if (i.value=="") {
          i.classList.add("error-inp");
          if (!isOtpEmpty) {
              i.focus();
              isOtpEmpty = true;
          }
      }
      else{
          i.classList.remove("error-inp");
      }
      });
      if (isOtpEmpty) {
          isValidResult=false;
      }
  return isValidResult;
}

// Set the date we're counting down to
//    var countDownDate = new Date("Feb 6, 2021 15:37:25").getTime();
// Update the count down every 1 second
var x = setInterval(function() {

// Get today's date and time
var now = new Date().getTime();

// Find the distance between now and the count down date
var distance = countDownDate - now;

//var distance = -1;

// Time calculations for minutes and seconds
var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
var seconds = Math.floor((distance % (1000 * 60)) / 1000);

let timerText = document.querySelector(".timer-text");

// Display the result in the element with id="demo"
timerText.innerHTML =  `Wait for ${minutes} : ${seconds} mins. before resending OTP`;
resendBtn.classList.add("disabled-btn");
submitBtn.classList.remove("disabled-btn");
// If the count down is finished, write some text
if (distance < 0) {
clearInterval(x);
submitBtn.classList.add("disabled-btn");
resendBtn.classList.remove("disabled-btn");
timerText.innerHTML = "";
}

}, 1000);
