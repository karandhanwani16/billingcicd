class BankRow {
    constructor(id, bank_name, account_no, branch_name, ifsc) {
        this.id = id;
        this.bank_name = bank_name;
        this.account_no = account_no;
        this.branch_name = branch_name;
        this.ifsc = ifsc;
    }
}



class BankTable {
    constructor() {
        this.currentId = 1;
        this.bankRows = [];
    }

    incrementId() {
        this.currentId++;
    }
    decrementId() {
        this.currentId--;
    }

    insertRow(bank_name, account_no, branch_name, ifsc) {
        let tempObject = new BankRow(this.currentId, bank_name, account_no, branch_name, ifsc);
        this.bankRows.push(tempObject);
        this.incrementId();
    }
    removeRow(id) {
        this.bankRows = this.bankRows.filter(function (item) {
            return item.id != id;
        });
    }
    displayRows() {
        let finalResult = "";
        let count = 1;
        this.bankRows.forEach((row) => {
            finalResult += "<tr>";
            finalResult += "<td class='edit--link' data-id='" + row.id + "'>" + count + "</td>";
            finalResult += "<td>" + row.bank_name + "</td>";
            finalResult += "<td>" + row.account_no + "</td>";
            finalResult += "<td>" + row.branch_name + "</td>";
            finalResult += "<td>" + row.ifsc + "</td>";
            finalResult += "<td><div data-id='" + row.id + "' class='delete-btn'>Delete</div></td>";
            finalResult += "</tr>";
            count++;
        });
        return finalResult;
    }

}