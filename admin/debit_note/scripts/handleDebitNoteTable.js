// getting the variables
let debitnotetable = new DebitNoteTable();
let addRow = document.querySelector(".add-row");
let bankTableBody = document.querySelector("#debitnoteTable");



let mainCharges = document.querySelector("#txtmaincharges");
let amount = document.querySelector("#txtamount");
let hsn = document.querySelector("#txthsn");




// main code
// general functions or utils

function clearInputs() {
    mainCharges.value = "";
    amount.value = "";
}


function refreshView() {
    bankTableBody.innerHTML = debitnotetable.displayRows();
    debitnotetable.calculateAllMetrics();
    debitnoteObject.total = debitnotetable.total;
    enterMetricsIntoHtml();
    addDeleteFunctionality();
    addEditFunctionality();
}


function addDeleteFunctionality() {
    let deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener("click", e => {
            let id = deleteButton.attributes['data-id'].value;
            debitnotetable.removeRow(id);
            refreshView();
        });
    });
}



function enterMetricsIntoHtml() {
    document.getElementById("lblcgst").innerHTML = debitnotetable.cGst;
    document.getElementById("lblsgst").innerHTML = debitnotetable.sGst;
    document.getElementById("lbligst").innerHTML = debitnotetable.iGst;
    document.getElementById("lblroundoff").innerHTML = debitnotetable.roundOff;
    document.getElementById("lbltotal").innerHTML = debitnotetable.total;
}
// edit functionality support functions

function addEditInputs(id) {
    let InvoiceTableCont = document.querySelector("#debitnoteTable");
    let rows = InvoiceTableCont.children;

    for (var i = 0; i < rows.length; i++) {
        let currentRow = rows[i];
        let currentRowId = currentRow.attributes["data-id"].value;
        if (id == currentRowId) {
            let mainTextCell = currentRow.children[1];
            mainTextCell.innerHTML = `<input type='txt' value="${mainTextCell.innerHTML}" id='txteditmaincharges' placeholder='Main Text/Charges' class='inp'/>`;
            let totalTextCell = currentRow.children[2];
            totalTextCell.innerHTML = "<input type='txt' value='" + totalTextCell.innerHTML + "' id='txteditamount' placeholder='Amount' class='inp'/>";
            let hsnCell = currentRow.children[3];
            hsnCell.innerHTML = "<input type='txt' value='" + hsnCell.innerHTML + "' id='txtedithsn' placeholder='HSN' class='inp'/>";
        }
        // console.log(mainTextCell);
    }
}

function showCancelBtn(id) {
    let cancelButtons = document.querySelectorAll(".cancel-btn");
    cancelButtons.forEach(cancelButton => {
        let currentId = cancelButton.attributes['data-id'].value;
        if (currentId == id) {
            cancelButton.classList.remove("hide-btn");
        }
    });
}

function showSaveBtn(id) {
    let saveButtons = document.querySelectorAll(".save-btn");
    saveButtons.forEach(saveButton => {
        let currentId = saveButton.attributes['data-id'].value;
        if (currentId == id) {
            saveButton.classList.remove("hide-btn");
        }
    });
}

function hideDeleteBtn(id) {
    let deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(deleteButton => {
        let currentId = deleteButton.attributes['data-id'].value;
        if (currentId == id) {
            deleteButton.classList.add("hide-btn");
        }
    });
}




// add functionality main function 

function addEditFunctionality() {
    let editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(editButton => {
        editButton.addEventListener("click", e => {
            let id = editButton.attributes['data-id'].value;
            // hide delete btn
            hideDeleteBtn(id);
            // show save btn
            showSaveBtn(id);
            // show cancel btn
            showCancelBtn(id);
            // hide edit btn
            editButton.classList.add("hide-btn");
            // add edit inputs to the row
            addEditInputs(id);
            // add Cancel Button Listeners
            addCancelFunctionality();
            // add Save Button Listeners
            addSaveFunctionality(id);
            // refreshView();
        });
    });
}

function addSaveFunctionality(id) {
    let saveButtons = document.querySelectorAll(".save-btn");
    saveButtons.forEach(saveButton => {
        saveButton.addEventListener("click", e => {
            let id = saveButton.attributes['data-id'].value;
            let rowPayload = {
                "main": document.querySelector("#txteditmaincharges").value,
                "total": document.querySelector("#txteditamount").value,
                "hsn": document.querySelector("#txtedithsn").value
            };
            debitnotetable.updateRow(id, rowPayload);
            refreshView();
        });
    });

}


function addCancelFunctionality() {
    let cancelButtons = document.querySelectorAll(".cancel-btn");
    cancelButtons.forEach(cancelButton => {
        cancelButton.addEventListener("click", e => {
            refreshView();
        });
    });
}


// event listeners
addRow.addEventListener("click", e => {
    if (mainCharges.value == "" || amount.value == "") {
        alert("Please add proper details");
    } else {
        // insert into the table
        debitnotetable.insertRow(mainCharges.value, amount.value, hsn.value);
        refreshView();
        // clear inputs
        clearInputs();
    }
});
