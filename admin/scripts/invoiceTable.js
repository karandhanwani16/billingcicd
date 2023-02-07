class TableRow {
    constructor(
        id,
        product_id,
        product_name,
        product_description,
        product_quantity,
        product_unit_name,
        product_unit_id,
        product_price,
        product_deduction
    ) {
        this.id = id;
        this.product_id = product_id;
        this.product_name = product_name;
        this.product_description = product_description;
        this.product_quantity = product_quantity;
        this.product_unit_id = product_unit_id;
        this.product_unit_name = product_unit_name;
        this.product_price = product_price;
        this.product_deduction = product_deduction;
        this.product_total = (product_price * product_quantity) - this.product_deduction;
    }
}

class InvoiceTable {
    constructor() {
        this.currentId = 1;
        this.invoiceRows = [];
        this.subTotal = 0;
        this.tax = 0;
        this.netTotal = 0;
        this.taxPercentage = 2.5;
    }

    incrementId() {
        this.currentId++;
    }
    decrementId() {
        this.currentId--;
    }

    insertRow(
        product_id,
        product_name,
        product_description,
        product_quantity,
        product_unit_name,
        product_unit_id,
        product_price,
        product_deduction
    ) {
        let tempObject = new TableRow(
            this.currentId,
            product_id,
            product_name,
            product_description,
            product_quantity,
            product_unit_name,
            product_unit_id,
            product_price,
            product_deduction
        );
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
            finalResult += "<tr>";
            finalResult += "<td class='edit--link' data-id='" + row.id + "'>" + count + "</td>";
            if (row.product_description !== "") {
                finalResult += "<td data-id='" + row.product_id + "' data-description='" + row.product_description + "' >" + row.product_name + " - " + row.product_description + "</td>";
            } else {
                finalResult += "<td data-id='" + row.product_id + "' data-description='" + row.product_description + "' >" + row.product_name + "</td>";
            }
            finalResult += "<td>" + row.product_quantity + "</td>";
            if (row.product_unit_id == 0) {
                finalResult += "<td  data-id=''>" + row.product_unit_name + "</td>";
            } else {
                finalResult += "<td  data-id='" + row.product_unit_id + "'>" + row.product_unit_name + "</td>";
            }
            finalResult += "<td>" + row.product_price + "</td>";
            if (row.product_deduction == 0) {
                finalResult += "<td> " + row.product_deduction + "</td>";
            } else {
                finalResult += "<td>- " + row.product_deduction + "</td>";

            }
            finalResult += "<td>" + row.product_total + "</td>";
            finalResult +=
                "<td><div data-id='" +
                row.id +
                "' class='delete-btn'>Delete</div></td>";
            finalResult += "</tr>";
            count++;
        });
        return finalResult;
    }
    displayReturnRows() {
        let finalResult = "";
        let count = 1;
        this.invoiceRows.forEach((row) => {
            finalResult += "<tr>";
            finalResult += "<td class='edit--link' data-id='" + row.id + "'>" + count + "</td>";
            if (row.product_description !== "") {
                finalResult += "<td data-id='" + row.product_id + "' data-description='" + row.product_description + "' >" + row.product_name + " - " + row.product_description + "</td>";
            } else {
                finalResult += "<td data-id='" + row.product_id + "' data-description='" + row.product_description + "' >" + row.product_name + "</td>";
            }
            finalResult += "<td>" + row.product_quantity + "</td>";
            if (row.product_unit_id == 0) {
                finalResult += "<td  data-id=''>" + row.product_unit_name + "</td>";
            } else {
                finalResult += "<td  data-id='" + row.product_unit_id + "'>" + row.product_unit_name + "</td>";
            }
            finalResult += "<td>" + row.product_price + "</td>";
            finalResult += "<td>" + row.product_total + "</td>";
            finalResult +=
                "<td><div data-id='" +
                row.id +
                "' class='delete-btn'>Delete</div></td>";
            finalResult += "</tr>";
            count++;
        });
        return finalResult;
    }
    calculateSubtotal() {
        // console.log(this.invoiceRows);
        // if (this.invoiceRows.length == 0) {
        this.subTotal = 0;
        // } else {
        this.invoiceRows.forEach(row => {
            this.subTotal += row.product_total;
            // console.log(row.product_total);
            // console.log(this.subTotal);
        });
        // }
        return this.roundToTwo(this.subTotal);
    }
    calculateTax() {
        this.tax = this.subTotal * (this.taxPercentage / 100);
        return this.roundToTwo(this.tax);
    }
    calculateNetTotal() {
        // this.netTotal = this.subTotal + this.tax;
        this.netTotal = this.subTotal;
        return this.roundToTwo(this.netTotal);
    }

    roundToTwo(num) {
        return +(Math.round(num + "e+2") + "e-2");
    }
}