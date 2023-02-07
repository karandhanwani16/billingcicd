class CreditNoteRow {
    constructor(id, mainText, amount, hsn) {
        this.id = id;
        this.mainText = mainText;
        this.amount = amount;
        this.hsn = hsn;
    }
}



class CreditNoteTable {
    constructor() {
        this.currentId = 1;
        this.creditnoteRows = [];
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

    insertRow(mainText, hsn, amount) {
        let tempObject = new CreditNoteRow(this.currentId, mainText, hsn, amount);
        this.creditnoteRows.push(tempObject);
        this.incrementId();
    }
    removeRow(id) {
        this.creditnoteRows = this.creditnoteRows.filter(function (item) {
            return item.id != id;
        });
    }
    displayRows() {
        let finalResult = "";
        let count = 1;
        this.creditnoteRows.forEach((row) => {
            finalResult += "<tr data-id='" + row.id + "'>";
            finalResult += "<td class='edit--link' data-id='" + row.id + "'>" + count + "</td>";
            finalResult += "<td>" + row.mainText + "</td>";
            finalResult += "<td>" + row.amount + "</td>";
            finalResult += "<td>" + row.hsn + "</td>";
            // 
            finalResult += "<td>";
            finalResult += "<div data-id='" + row.id + "' class='delete-btn'>Delete</div>";
            // edit btn
            finalResult += "<div data-id='" + row.id + "' class='edit-btn'>Edit</div>";
            // save btn
            finalResult += "<div data-id='" + row.id + "' class='save-btn hide-btn'>save</div>";
            // cancel btn
            finalResult += "<div data-id='" + row.id + "' class='cancel-btn hide-btn'>cancel</div>";
            // 
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
        this.creditnoteRows.forEach((row) => {
            this.grossTotal += parseFloat(row.amount);
        });
    }
    calculateCGST() {
        this.cGst = parseFloat(this.grossTotal) * (this.cGstPercentage / 100);
        this.cGst = parseFloat(this.cGst.toFixed(2));
    }
    calculateSGST() {
        this.sGst = parseFloat(this.grossTotal) * (this.sGstPercentage / 100);
        this.sGst = parseFloat(this.sGst.toFixed(2));
    }
    calculateIGST() {
        this.iGst = parseFloat(this.grossTotal) * (this.iGstPercentage / 100);
        this.iGst = parseFloat(this.iGst.toFixed(2));
    }
    calculateRoundOff() {
        let oldvalue = parseFloat(this.grossTotal) + this.cGst + this.sGst + this.iGst;
        let newvalue = Math.round(parseFloat(this.grossTotal) + this.cGst + this.sGst + this.iGst);
        this.roundOff = (newvalue - oldvalue).toFixed(2);
    }
    calculateTotal() {
        this.total = parseFloat(this.grossTotal) + this.cGst + this.sGst + this.iGst + parseFloat(this.roundOff);
        this.total = this.total.toFixed(2);
    }
    // update function
    updateRow(id, rowPayload) {
        this.creditnoteRows.forEach((row) => {
            if (row.id == id) {
                row.mainText = rowPayload.main;
                row.amount = rowPayload.total;
                row.hsn = rowPayload.hsn;
            }
        });
    }

}
