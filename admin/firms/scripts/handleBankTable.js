// getting the variables
let banktable = new BankTable();
let addRow = document.querySelector(".add-row");
let bankTableBody = document.querySelector("#firmBankTable");


let bankName = document.querySelector("#txtbankname");
let accoutNo = document.querySelector("#txtaccountno");
let branchName = document.querySelector("#txtbranchname");
let ifsc = document.querySelector("#txtifsc");



// event listeners
addRow.addEventListener("click", e => {
    if (bankName.value == "" || accoutNo.value == "" || branchName.value == "" || ifsc.value == "") {
        alert("Please add proper bank details");
    } else {
        // insert into the table
        banktable.insertRow(bankName.value, accoutNo.value, branchName.value, ifsc.value);
        refreshView();
        // clear inputs
        // clearInputs();
    }
});

// main code
// general functions or utils

function clearInputs() {
    bankName.value = "";
    accoutNo.value = "";
    branchName.value = "";
    ifsc.value = "";
}


function refreshView() {
    bankTableBody.innerHTML = banktable.displayRows();
    addDeleteFunctionality();
    // addEditFunctionality();
}

function addDeleteFunctionality() {
    let deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener("click", e => {
            let id = deleteButton.attributes['data-id'].value;
            banktable.removeRow(id);
            refreshView();
        });
    });
}
