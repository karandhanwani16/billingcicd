// getting the variables
let banktable = new InvoiceTable();
let addRow = document.querySelector(".add-row");
let bankTableBody = document.querySelector("#invoiceTable");



let mainCharges = document.querySelector("#txtmaincharges");
let prefixText = document.querySelector("#txtprefix");
let totalValue = document.querySelector("#txttotalvalue");
let postfixText = document.querySelector("#txtpostfix");
let comissionPercentage = document.querySelector("#txtcomissionpercentage");
let hsn = document.querySelector("#txthsn");



// main code


// Utility Functions 
function clearInputs() {
    mainCharges.value = "";
    prefixText.value = "";
    totalValue.value = "";
    postfixText.value = "";
    comissionPercentage.value = "";
}

function refreshView() {
    bankTableBody.innerHTML = banktable.displayRows();
    banktable.calculateAllMetrics();
    invoiceObject.total = banktable.total;
    enterMetricsIntoHtml();
    addDeleteFunctionality();
    addEditFunctionality();
}

function addEditInputs(id) {
    let InvoiceTableCont = document.querySelector("#invoiceTable");
    let rows = InvoiceTableCont.children;

    for (var i = 0; i < rows.length; i++) {
        let currentRow = rows[i];
        let currentRowId = currentRow.attributes["data-id"].value;
        if (id == currentRowId) {
            let mainTextCell = currentRow.children[1];
            mainTextCell.innerHTML = "<input type='txt' value='" + mainTextCell.innerHTML + "' id='txteditmaincharges' placeholder='Main Text/Charges' class='inp'/>";
            let prefixTextCell = currentRow.children[2];
            prefixTextCell.innerHTML = `<input type='txt' value="${getActualValueFromList(prefixTextCell.innerHTML)}" id='txteditprefix' placeholder='Prefix Comment' class='inp'/>`;
            let totalTextCell = currentRow.children[3];
            totalTextCell.innerHTML = "<input type='txt' value='" + totalTextCell.innerHTML + "' id='txtedittotalvalue' placeholder='Total value' class='inp'/>";
            let postTextCell = currentRow.children[4];
            postTextCell.innerHTML = `<input type='txt' value="${getActualValueFromList(postTextCell.innerHTML)}" id='txteditpost' placeholder='Postfix Comment' class='inp'/>`;
            let commissionPercentageCell = currentRow.children[5];
            commissionPercentageCell.innerHTML = "<input type='txt' value='" + commissionPercentageCell.innerHTML + "' id='txteditcomissionpercentage' placeholder='Comission Percentage' class='inp'/>";
            let hsnCell = currentRow.children[6];
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

function enterMetricsIntoHtml() {
    document.getElementById("lblcgst").innerHTML = banktable.cGst;
    document.getElementById("lblsgst").innerHTML = banktable.sGst;
    document.getElementById("lbligst").innerHTML = banktable.iGst;
    document.getElementById("lblroundoff").innerHTML = banktable.roundOff;
    document.getElementById("lbltotal").innerHTML = banktable.total;
}

function getActualValueFromList(list) {
    let finalString = "";
    finalString = list.replaceAll('<ul>', '');
    finalString = finalString.replaceAll('</ul>', '');
    finalString = finalString.replaceAll('</li><li>', '.');
    finalString = finalString.replaceAll('<li>', '');
    finalString = finalString.replaceAll('</li>', '');
    return finalString;
}

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


// adding Listeners to Buttons
addRow.addEventListener("click", e => {
    if (mainCharges.value == "" || totalValue.value == "" || comissionPercentage.value == "" || hsn.value == "") {
        alert("Please add proper invoice details");
    } else {
        // insert into the table
        banktable.insertRow(mainCharges.value, prefixText.value, postfixText.value, totalValue.value, comissionPercentage.value, hsn.value);
        refreshView();
        // clear inputs
        clearInputs();
    }
});

function addSaveFunctionality(id) {
    let saveButtons = document.querySelectorAll(".save-btn");
    saveButtons.forEach(saveButton => {
        saveButton.addEventListener("click", e => {
            let id = saveButton.attributes['data-id'].value;
            let rowPayload = {
                "main": document.querySelector("#txteditmaincharges").value,
                "prefix": document.querySelector("#txteditprefix").value,
                "total": document.querySelector("#txtedittotalvalue").value,
                "postfix": document.querySelector("#txteditpost").value,
                "commissionPercentage": document.querySelector("#txteditcomissionpercentage").value,
                "hsn": document.querySelector("#txtedithsn").value
            };
            banktable.updateRow(id, rowPayload);
            refreshView();
        });
    });

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

function addCancelFunctionality() {
    let cancelButtons = document.querySelectorAll(".cancel-btn");
    cancelButtons.forEach(cancelButton => {
        cancelButton.addEventListener("click", e => {
            // let id = cancelButton.attributes['data-id'].value;
            // banktable.removeRow(id);
            refreshView();
        });
    });
}

