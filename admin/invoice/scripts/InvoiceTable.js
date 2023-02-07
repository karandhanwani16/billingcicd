class InvoiceRow {
    constructor(id, mainText, prefixText, postfixText, totalValue, percentage, hsn) {
        this.id = id;
        this.mainText = mainText;
        this.prefixText = prefixText;
        this.postfixText = postfixText;
        this.totalValue = totalValue;
        this.percentage = percentage;
        if (percentage == 0 || percentage == "") {
            this.rowValue = totalValue;
        } else {
            this.rowValue = Math.round(totalValue * (percentage / 100));
        }
        this.hsn = hsn;
    }
}



class InvoiceTable {
    constructor() {
        this.currentId = 1;
        this.invoiceRows = [];
        this.grossTotal = 0;
        this.sGstPercentage = 0;
        this.cGstPercentage = 0;
        this.iGstPercentage = 0;
        this.sGst = 0;
        this.cGst = 0;
        this.iGst = 0;
        // this.roundOffValue = 0;
        this.roundOff = 0;
        this.total = 0;
    }

    setCgst(value) {
        this.cGstPercentage = parseFloat(value);
    }
    setSgst(value) {
        this.sGstPercentage = parseFloat(value);
    }
    setIgst(value) {
        this.iGstPercentage = parseFloat(value);
    }

    incrementId() {
        this.currentId++;
    }
    decrementId() {
        this.currentId--;
    }

    insertRow(mainText, prefixText, postfixText, totalValue, percentage, hsn) {
        let tempObject = new InvoiceRow(this.currentId, mainText, prefixText, postfixText, totalValue, percentage, hsn);
        this.invoiceRows.push(tempObject);
        this.incrementId();
    }
    removeRow(id) {
        this.invoiceRows = this.invoiceRows.filter(function (item) {
            return item.id != id;
        });
    }

    displayRows() {
        let finalResult = "";
        let count = 1;
        this.invoiceRows.forEach((row) => {
            finalResult += "<tr data-id='" + row.id + "'>";
            finalResult += "<td>" + count + "</td>";
            finalResult += "<td>" + row.mainText + "</td>";
            finalResult += "<td>" + getMultipleLineText(row.prefixText) + "</td>";
            finalResult += "<td>" + row.totalValue + "</td>";
            finalResult += "<td>" + getMultipleLineText(row.postfixText) + "</td>";
            finalResult += "<td>" + row.percentage + "</td>";
            finalResult += "<td>" + row.hsn + "</td>";
            finalResult += "<td>" + row.rowValue + "</td>";
            // add action buttons
            finalResult += "<td>";
            // delete btn
            finalResult += "<div data-id='" + row.id + "' class='delete-btn'>Delete</div>";
            // edit btn
            finalResult += "<div data-id='" + row.id + "' class='edit-btn'>Edit</div>";
            // save btn
            finalResult += "<div data-id='" + row.id + "' class='save-btn hide-btn'>save</div>";
            // cancel btn
            finalResult += "<div data-id='" + row.id + "' class='cancel-btn hide-btn'>cancel</div>";
            finalResult += "</td>";
            finalResult += "</tr>";
            count++;
        });
        return finalResult;
    }
    calculateAllMetrics() {
        this.calculateGrossTotal();
        this.calculateCGST();
        this.calculateSGST();
        this.calculateIGST();
        this.calculateRoundOff();
        this.calculateTotal();
    }
    calculateGrossTotal() {
        this.grossTotal = 0;
        this.invoiceRows.forEach((row) => {
            this.grossTotal += parseFloat(row.rowValue);
        });
    }
    calculateCGST() {
        this.cGst = parseFloat(this.grossTotal) * (this.cGstPercentage / 100);
        this.cGst = this.cGst.toFixed(2);
    }
    calculateSGST() {
        this.sGst = parseFloat(this.grossTotal) * (this.sGstPercentage / 100);
        this.sGst = this.sGst.toFixed(2);
    }
    calculateIGST() {
        this.iGst = parseFloat(this.grossTotal) * (this.iGstPercentage / 100);
        this.iGst = this.iGst.toFixed(2);
    }
    calculateRoundOff() {
        let oldvalue = parseFloat(this.grossTotal) + parseFloat(this.cGst) + parseFloat(this.sGst) + parseFloat(this.iGst);
        let newvalue = Math.round(parseFloat(this.grossTotal) + parseFloat(this.cGst) + parseFloat(this.sGst) + parseFloat(this.iGst));
        this.roundOff = (newvalue - oldvalue).toFixed(2);
    }
    calculateTotal() {
        this.total = parseFloat(this.grossTotal) + parseFloat(this.cGst) + parseFloat(this.sGst) + parseFloat(this.iGst) + parseFloat(this.roundOff);
        this.total = this.total.toFixed(2);
    }

    // update function
    updateRow(id, rowPayload) {
        this.invoiceRows.forEach((row) => {
            if (row.id == id) {
                row.mainText = rowPayload.main;
                row.prefixText = rowPayload.prefix;
                row.postfixText = rowPayload.postfix;
                row.totalValue = rowPayload.total;
                row.percentage = rowPayload.commissionPercentage;
                if (rowPayload.commissionPercentage == 0 || rowPayload.commissionPercentage == "") {
                    row.rowValue = rowPayload.total;
                } else {
                    row.rowValue = Math.round(rowPayload.total * (rowPayload.commissionPercentage / 100));
                }
                row.hsn = rowPayload.hsn;
            }
        });
    }

}


function getMultipleLineText(inputText) {
    let result = "";
    let resultArray = inputText.split(".");
    result = "<ul>";
    resultArray.forEach(element => {
        result += `<li>${element}</li>`;
    });
    result += "</ul>";
    return result;
}