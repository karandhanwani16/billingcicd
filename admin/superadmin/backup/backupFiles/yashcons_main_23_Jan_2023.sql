

CREATE TABLE `credit_note` (
  `supplier_id` bigint NOT NULL,
  `firm_id` bigint NOT NULL,
  `firm_bank_id` bigint NOT NULL,
  `credit_note_id` bigint NOT NULL,
  `credit_note_no` bigint NOT NULL,
  `credit_note_date` date NOT NULL,
  `credit_note_ref` varchar(200) NOT NULL,
  `credit_note_other_ref` varchar(200) NOT NULL,
  `credit_note_place_of_supply` varchar(200) NOT NULL,
  `credit_note_sgst_percentage` int NOT NULL,
  `credit_note_cgst_percentage` int NOT NULL,
  `credit_note_igst_percentage` int NOT NULL,
  `credit_note_total` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;






CREATE TABLE `credit_note_edit_request` (
  `credit_note_edit_request_id` bigint NOT NULL,
  `credit_note_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `credit_note_edit_request_timestamp` varchar(100) NOT NULL,
  `credit_note_edit_request_used` varchar(10) NOT NULL,
  `credit_note_edit_request_permission_granted` varchar(10) NOT NULL,
  `credit_note_edit_request_comment` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;






CREATE TABLE `credit_note_products` (
  `credit_note_products_id` bigint NOT NULL,
  `credit_note_id` bigint NOT NULL,
  `credit_note_products_name` varchar(500) NOT NULL,
  `credit_note_products_prefix` varchar(500) NOT NULL,
  `credit_note_products_postfix` varchar(500) NOT NULL,
  `credit_note_products_value` varchar(50) NOT NULL,
  `credit_note_products_percentage` varchar(10) NOT NULL,
  `credit_note_products_hsn` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;






CREATE TABLE `debit_note` (
  `supplier_id` bigint NOT NULL,
  `firm_id` bigint NOT NULL,
  `firm_bank_id` bigint NOT NULL,
  `debit_note_id` bigint NOT NULL,
  `debit_note_no` bigint NOT NULL,
  `debit_note_date` date NOT NULL,
  `debit_note_ref` varchar(200) NOT NULL,
  `debit_note_other_ref` varchar(200) NOT NULL,
  `debit_note_place_of_supply` varchar(200) NOT NULL,
  `debit_note_sgst_percentage` int NOT NULL,
  `debit_note_cgst_percentage` int NOT NULL,
  `debit_note_igst_percentage` int NOT NULL,
  `debit_note_total` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO debit_note VALUES("5","1","1","1","28","2023-01-09","-","-","HARYANA","0","0","0","7706.00");
INSERT INTO debit_note VALUES("11","2","2","2","20","2023-01-10","-","-","MAHARASHTRA","0","0","0","9460.00");
INSERT INTO debit_note VALUES("16","2","2","3","21","2023-01-11","-","-","Maharastra","0","0","0","12832.00");
INSERT INTO debit_note VALUES("14","2","2","4","22","2023-01-11","-","-","Maharastra","0","0","0","1705.00");





CREATE TABLE `debit_note_edit_request` (
  `debit_note_edit_request_id` bigint NOT NULL,
  `debit_note_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `debit_note_edit_request_timestamp` varchar(100) NOT NULL,
  `debit_note_edit_request_used` varchar(10) NOT NULL,
  `debit_note_edit_request_permission_granted` varchar(10) NOT NULL,
  `debit_note_edit_request_comment` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;






CREATE TABLE `debit_note_products` (
  `debit_note_products_id` bigint NOT NULL,
  `debit_note_id` bigint NOT NULL,
  `debit_note_products_name` varchar(500) NOT NULL,
  `debit_note_products_prefix` varchar(500) NOT NULL,
  `debit_note_products_postfix` varchar(500) NOT NULL,
  `debit_note_products_value` varchar(50) NOT NULL,
  `debit_note_products_percentage` varchar(10) NOT NULL,
  `debit_note_products_hsn` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO debit_note_products VALUES("1","1","FOR THE MONTH DECEMBER\'22","","","7706","","996111");
INSERT INTO debit_note_products VALUES("2","2","DECEMBER\'22 ANNEXURE ATTACH","","","9460","","998599");
INSERT INTO debit_note_products VALUES("3","3","REIMBURSEMENT OF DAMAGED GOODS OF FREE WILL FOR DECEMBER\'22","","","12832","","998599");
INSERT INTO debit_note_products VALUES("4","4","DECEMBER\'22 ANNEXURE ATTACH","","","1705","","998599");





CREATE TABLE `firm` (
  `firm_id` bigint NOT NULL,
  `firm_name` varchar(1000) NOT NULL,
  `firm_gst` varchar(20) NOT NULL,
  `firm_address` varchar(1500) NOT NULL,
  `firm_state` varchar(20) NOT NULL,
  `firm_state_code` varchar(20) NOT NULL,
  PRIMARY KEY (`firm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO firm VALUES("1","YASH ENTERPRISES","27AHCPG1323K1ZO","GROUND FLR, SHOP NO 1,MARGASEERSH BLDG,RAM WADI,MANPADA ROAD,DOMBIVILI,THANE-421201","MAHARASHTRA","27");
INSERT INTO firm VALUES("2","SHREE ENTERPRISES","27ALNPG9864A1ZV","BUNGLOW NO-4,LAXMI PARK,KANCHANGAON,90FT ROAD,THAKURLI EAST,THANE-421201","MAHARASHTRA","27");
INSERT INTO firm VALUES("3","YASH CONSUMER PRODUCTS PVT LTD.","27AABCY4016E1ZL","SHOP NO -1,MARGASHIRSH BLDG,RAMWADI,BELOW CHIRAYU HOSPITAL,MANPADA,DOMBIVILI-EAST,THANE421201","MAHARASHTRA","27");





CREATE TABLE `firm_bank` (
  `firm_bank_id` bigint NOT NULL,
  `firm_id` bigint NOT NULL,
  `firm_bank_name` varchar(200) NOT NULL,
  `firm_bank_account_no` varchar(200) NOT NULL,
  `firm_bank_branch_name` varchar(200) NOT NULL,
  `firm_bank_ifsc` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO firm_bank VALUES("1","1","AXIS BANK","91902000698061","DOMBIVILI","UTIB0003580");
INSERT INTO firm_bank VALUES("2","2","ICICI BANK","148905001037","DOMBIVILI WEST","ICIC0001489");
INSERT INTO firm_bank VALUES("3","3","ICICI BABK","100005004916","TEENHATHNAKA","ICIC0001000");





CREATE TABLE `forgot_verification` (
  `user_id` bigint NOT NULL,
  `last_requested_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verification_code` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;






CREATE TABLE `invoice` (
  `supplier_id` bigint NOT NULL,
  `firm_id` bigint NOT NULL,
  `firm_bank_id` bigint NOT NULL,
  `invoice_id` bigint NOT NULL,
  `invoice_no` bigint NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_ref` varchar(200) NOT NULL,
  `invoice_other_ref` varchar(200) NOT NULL,
  `invoice_place_of_supply` varchar(200) NOT NULL,
  `invoice_sgst_percentage` int NOT NULL,
  `invoice_cgst_percentage` int NOT NULL,
  `invoice_igst_percentage` int NOT NULL,
  `invoice_total` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO invoice VALUES("11","2","2","2","144","2023-01-10","","-","Maharastra","9","9","0","26532.00");
INSERT INTO invoice VALUES("12","2","2","3","145","2023-01-10","-","-","NEW DELHI","0","0","18","20066.00");
INSERT INTO invoice VALUES("13","2","2","4","146","2023-01-10","-","-","NEW DELHI","0","0","18","15057.00");
INSERT INTO invoice VALUES("14","2","2","5","147","2023-01-10","-","-","Maharastra","9","9","0","23175.00");
INSERT INTO invoice VALUES("15","2","2","6","148","2023-01-10","-","-","NEW DELHI","0","0","18","36614.00");
INSERT INTO invoice VALUES("16","2","2","7","149","2023-01-11","-","-","Maharastra","9","9","0","9440.00");
INSERT INTO invoice VALUES("16","2","2","8","150","2023-01-11","-","-","Maharastra","9","9","0","152790.00");
INSERT INTO invoice VALUES("17","2","2","9","151","2023-01-11","-","-","NEW DELHI","0","0","18","28521.00");
INSERT INTO invoice VALUES("18","3","3","10","57","2023-01-02","-","-","Maharastra","9","9","0","2349162.00");
INSERT INTO invoice VALUES("19","3","3","11","58","2023-01-02","-","-","Maharastra","9","9","0","513667.00");
INSERT INTO invoice VALUES("20","3","3","12","59","2023-01-04","-","-","Maharastra","9","9","0","994681.00");
INSERT INTO invoice VALUES("21","3","3","13","60","2023-01-11","-","-","Maharastra","9","9","0","312612.00");
INSERT INTO invoice VALUES("1","1","1","14","220","2023-01-02","-","-","HIMACHAL PRADESH","0","0","18","345546.00");
INSERT INTO invoice VALUES("2","1","1","15","221","2023-01-02","-","-","Maharastra","9","9","0","1511454.00");
INSERT INTO invoice VALUES("3","1","1","16","222","2023-01-04","-","-","GUJARAT","0","0","18","1506859.00");
INSERT INTO invoice VALUES("4","1","1","17","223","2023-01-04","-","-","Maharastra","9","9","0","893667.00");
INSERT INTO invoice VALUES("2","1","1","18","224","2023-01-05","-","-","Maharastra","9","9","0","1933878.00");
INSERT INTO invoice VALUES("5","1","1","19","225","2023-01-09","-","-","HARYANA","0","0","18","30739.00");
INSERT INTO invoice VALUES("6","1","1","20","226","2023-01-10","-","-","Maharastra","9","9","0","55460.00");
INSERT INTO invoice VALUES("7","1","1","21","227","2023-01-10","-","-","GAJARAT","0","0","18","31578.00");
INSERT INTO invoice VALUES("8","1","1","22","228","2023-01-10","-","-","NEW DELHI","0","0","18","5039.00");
INSERT INTO invoice VALUES("9","1","1","23","229","2023-01-10","-","-","KARNATAKA","0","0","18","406516.00");
INSERT INTO invoice VALUES("10","1","1","24","230","2023-01-11","-","-","Maharastra","9","9","0","353617.00");
INSERT INTO invoice VALUES("22","1","1","25","231","2023-01-12","-","-","TELANGANA","0","0","18","50279.00");
INSERT INTO invoice VALUES("2","1","1","26","232","2023-01-13","-","-","Maharastra","9","9","0","418741.00");
INSERT INTO invoice VALUES("23","1","1","27","233","2023-01-13","-","-","Maharastra","9","9","0","207851.00");
INSERT INTO invoice VALUES("24","1","1","28","234","2023-01-14","-","-","WEST BENGAL","0","0","18","7751.00");
INSERT INTO invoice VALUES("25","1","1","29","235","2023-01-16","-","-","Maharastra","9","9","0","348736.00");
INSERT INTO invoice VALUES("26","3","3","30","61","2023-01-19","-","-","Maharastra","9","9","0","1645508.00");





CREATE TABLE `invoice_edit_request` (
  `invoice_edit_request_id` bigint NOT NULL,
  `invoice_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `invoice_edit_request_timestamp` varchar(100) NOT NULL,
  `invoice_edit_request_used` varchar(10) NOT NULL,
  `invoice_edit_request_permission_granted` varchar(10) NOT NULL,
  `invoice_edit_request_comment` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO invoice_edit_request VALUES("1","1","11","23-01-12 10:59:21","true","true","WRONG PLACE OF SUPPLY");
INSERT INTO invoice_edit_request VALUES("2","1","11","23-01-12 11:23:29","true","true","ADMIN TESTING");
INSERT INTO invoice_edit_request VALUES("3","10","11","23-01-12 13:00:11","true","true","REMOVE @6%");
INSERT INTO invoice_edit_request VALUES("4","11","11","23-01-12 13:08:37","true","true","CHANGE PERCENTAGE 8.25");
INSERT INTO invoice_edit_request VALUES("5","26","11","23-01-16 17:27:51","true","true","EDIT IGST WITH CGST ");
INSERT INTO invoice_edit_request VALUES("6","26","11","23-01-16 17:35:57","true","true","gst ");
INSERT INTO invoice_edit_request VALUES("7","21","11","23-01-18 23:59:56","true","true","supply");
INSERT INTO invoice_edit_request VALUES("8","30","11","23-01-19 12:22:47","true","true","REMOVE SERVICE CHARGES COLUMN");
INSERT INTO invoice_edit_request VALUES("9","30","11","23-01-19 12:33:56","true","true","EDIT SERVICE CHARGES COLUMN");
INSERT INTO invoice_edit_request VALUES("10","30","11","23-01-19 17:11:33","true","true","testing");
INSERT INTO invoice_edit_request VALUES("11","30","10","23-01-23 20:25:54","false","true","Hello");





CREATE TABLE `invoice_products` (
  `Invoice_products_id` bigint NOT NULL,
  `invoice_id` bigint NOT NULL,
  `Invoice_products_name` varchar(500) NOT NULL,
  `Invoice_products_prefix` varchar(500) NOT NULL,
  `Invoice_products_postfix` varchar(500) NOT NULL,
  `Invoice_products_value` varchar(50) NOT NULL,
  `Invoice_products_percentage` varchar(10) NOT NULL,
  `Invoice_products_hsn` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO invoice_products VALUES("2","2","FOR MONTH DECEMBER\'22","-","-","22485","0","998599");
INSERT INTO invoice_products VALUES("3","3","FOR MONTH DECEMBER\'22","-","-","17005","0","998599");
INSERT INTO invoice_products VALUES("4","4","FOR THE MONTH DECEMBER\'22","-","-","12760","0","998599");
INSERT INTO invoice_products VALUES("5","5","FOR MONTH DECEMBER\'22","-","-","19640","0","998599");
INSERT INTO invoice_products VALUES("6","6","FOR THE MONTH DECEMBER\'22","-","-","31029","0","998599");
INSERT INTO invoice_products VALUES("7","7","FREE WILL H.O. CHARGES DECEMBER\'22","-","-","8000","0","998599");
INSERT INTO invoice_products VALUES("8","8","FREE WILL SALES COMMISSION FOR THE MONTH DECEMBER\'22","-","-","129483","0","998599");
INSERT INTO invoice_products VALUES("9","9","FOR THE MONTH DECEMBER\'22","-","-","24170","0","998599");
INSERT INTO invoice_products VALUES("10","10","FOR THE MONTH OF DECEMBER\'22","ON PRIMARY SALE","-","33180242.76","6","998599");
INSERT INTO invoice_products VALUES("11","11","COMMISSION FOR MONTH OF DECEMBER\'22","-","-","5276498.40","8.25","998599");
INSERT INTO invoice_products VALUES("12","12","COLLECTION CHARGES FOR THE MONTH OF DECEMBER\'22","-","ANNEXURE ATTACH","12042144","7","998599");
INSERT INTO invoice_products VALUES("13","13","COMMISION ON COLLECTION DECEMBER\'22","-","-","5359387","4","998599");
INSERT INTO invoice_products VALUES("14","13","COMMISION ON DEPOT SALE","-","-","1685013","3","998599");
INSERT INTO invoice_products VALUES("15","14","PAYMENT RECEIVED IN MONTH OF DECEMBER\'22","MEMO VALUE","ANNEXURE ATTACH","3660444","8","998599");
INSERT INTO invoice_products VALUES("16","15","COLLECTION CHARGES FOR THE MONTH OF DECEMBER\'22 (NEW)","COLLECTION (VALUE ON MEMO)","ANNEXURE ATTACH","73193861","1.75","998599");
INSERT INTO invoice_products VALUES("17","16","PAYMENT RECEIVED IN THE MONTH OF DECEMBER\'22","MEMO VALUE","ANNEXURE ATTACH","19646136","6.5","998599");
INSERT INTO invoice_products VALUES("18","17","FOR THE MONTH OF DECEMBER\'22","-","-","13769904","5.5","998599");
INSERT INTO invoice_products VALUES("19","18","ADDITIONAL MARGIN ON PRIMARY SALE SEPT TO DEC\'22","-","ANNEXURE ATTACH","218517371","0.75","998599");
INSERT INTO invoice_products VALUES("20","19","COMMISION FOR SALE(1) FOR MONTH OF DECEMBER\'22","-","-","26050","0","996111");
INSERT INTO invoice_products VALUES("21","20","CSP REIMBURSEMENT FOR THE MONTH DECEMBER\'22","-","-","47000","0","998599");
INSERT INTO invoice_products VALUES("23","22","TOWARDS AFTER SALES SERVICE CHARGES FOR ITEMS SALE AT CSD MUMBAI DEPOT DURING THE M/O DECEMBER\'22","-","-","4270","0","998599");
INSERT INTO invoice_products VALUES("24","23","REIMBURSEMENT FOR THE MONTH OF DECEMBER\'22","-","-","344505","0","998599");
INSERT INTO invoice_products VALUES("25","24","FOR THE MONTH DECEMBER\'22","-","-","299675","0","998599");
INSERT INTO invoice_products VALUES("26","25","CLAIMS FOR THE MONTH OF DECEMBER\'22","-","-","42609","0","998599");
INSERT INTO invoice_products VALUES("27","26","DATA COLLECTION CHARGES FOR DECEMBER\'22      PO NO.778518,778519,778520","PO VALUE","ANNEXURE ATTACH","70973062","0.5","998599");
INSERT INTO invoice_products VALUES("28","27","FOR THE MONTH OF DECEMBER\'22","-","-","176145","0","998599");
INSERT INTO invoice_products VALUES("29","28","SERVICE CHARGES (1) ,OCT\'22 TO DEC\'22","-","-","6569","0","998599");
INSERT INTO invoice_products VALUES("30","29","FOR THE MONTH OF DECEMBER\'22","-","-","2955387","10","998599");
INSERT INTO invoice_products VALUES("31","30","DATA SHARING & GUIDANCE","-","-","36641857.28","0.5","998599");
INSERT INTO invoice_products VALUES("32","30","MANPOWER & MERCHANDISING","-","-","36641857.28","3","998599");
INSERT INTO invoice_products VALUES("33","30","PAYMENT RELATED SERVICES","FOR THE MONTH DECEMBER\'22","ANNEXURE ATTACH","22406666.06","0.5","998599");
INSERT INTO invoice_products VALUES("34","21","MUMBAI OCT TO DEC\'22, SALE-9118","NORTH & EAST OCT TO DEC\'22, SALE-17643","-","26761","0","998311");





CREATE TABLE `logs` (
  `log_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_type` varchar(200) NOT NULL,
  `log_action` varchar(200) NOT NULL,
  `log_user` varchar(200) NOT NULL,
  `log_description` varchar(7900) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO logs VALUES("2023-01-12 10:41:28","firm","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 10:45:13","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 10:56:55","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 10:59:21","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 1");
INSERT INTO logs VALUES("2023-01-12 11:03:50","firm","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:07:21","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 1 by 11");
INSERT INTO logs VALUES("2023-01-12 11:08:37","firm","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:10:06","Invoice","updated","Sandesh sir","Invoice Id: 1");
INSERT INTO logs VALUES("2023-01-12 11:13:30","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:17:58","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:20:33","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:23:29","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 1");
INSERT INTO logs VALUES("2023-01-12 11:23:32","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 2 by 11");
INSERT INTO logs VALUES("2023-01-12 11:23:46","Invoice","updated","Sandesh sir","Invoice Id: 1");
INSERT INTO logs VALUES("2023-01-12 11:25:13","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:27:17","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:27:56","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:28:33","supplier","deleted","Sandesh sir","deleted Supplier Id: 7");
INSERT INTO logs VALUES("2023-01-12 11:30:42","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:32:35","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:34:57","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:35:37","supplier","updated","Sandesh sir","updated Supplier Id: 9");
INSERT INTO logs VALUES("2023-01-12 11:39:50","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:41:30","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:43:05","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:45:06","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:46:31","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:49:34","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:51:33","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:53:19","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 11:56:44","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:02:27","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:04:04","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:06:39","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:07:01","supplier","updated","Sandesh sir","updated Supplier Id: 21");
INSERT INTO logs VALUES("2023-01-12 12:32:34","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:37:14","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:40:34","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:43:54","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:46:13","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:48:55","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:51:02","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:53:05","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 12:59:25","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 13:00:11","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 10");
INSERT INTO logs VALUES("2023-01-12 13:00:15","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 3 by 11");
INSERT INTO logs VALUES("2023-01-12 13:02:40","Invoice","updated","Sandesh sir","Invoice Id: 10");
INSERT INTO logs VALUES("2023-01-12 13:07:17","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 13:08:37","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 11");
INSERT INTO logs VALUES("2023-01-12 13:08:47","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 4 by 11");
INSERT INTO logs VALUES("2023-01-12 13:09:27","Invoice","updated","Sandesh sir","Invoice Id: 11");
INSERT INTO logs VALUES("2023-01-12 13:11:57","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 13:14:29","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 15:01:02","DebitNote","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 15:05:19","DebitNote","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 15:32:27","DebitNote","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-12 15:34:22","DebitNote","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-13 18:24:51","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-13 18:46:21","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 10:26:42","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 10:31:39","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 10:36:51","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 10:40:11","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 10:50:59","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 15:20:37","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 15:39:08","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 15:42:01","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 15:44:03","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-14 18:32:53","Invoice","deleted","","deleted Invoice Id: 24");
INSERT INTO logs VALUES("2023-01-14 18:40:31","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-16 17:11:33","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-16 17:13:23","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-16 17:24:53","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-16 17:27:51","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 26");
INSERT INTO logs VALUES("2023-01-16 17:28:13","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 5 by 11");
INSERT INTO logs VALUES("2023-01-16 17:30:04","Invoice","updated","Sandesh sir","Invoice Id: 26");
INSERT INTO logs VALUES("2023-01-16 17:35:57","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 26");
INSERT INTO logs VALUES("2023-01-16 17:35:59","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 6 by 11");
INSERT INTO logs VALUES("2023-01-16 17:41:50","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-16 17:48:59","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-16 17:58:16","Invoice","updated","Sandesh sir","Invoice Id: 26");
INSERT INTO logs VALUES("2023-01-17 10:03:39","Invoice","deleted","Sandesh sir","deleted Invoice Id: 26");
INSERT INTO logs VALUES("2023-01-17 10:03:41","Invoice","deleted","Sandesh sir","deleted Invoice Id: 25");
INSERT INTO logs VALUES("2023-01-17 10:03:41","Invoice","deleted","Sandesh sir","deleted Invoice Id: 24");
INSERT INTO logs VALUES("2023-01-17 10:03:42","Invoice","deleted","Sandesh sir","deleted Invoice Id: 23");
INSERT INTO logs VALUES("2023-01-17 10:03:44","Invoice","deleted","Sandesh sir","deleted Invoice Id: 22");
INSERT INTO logs VALUES("2023-01-17 10:03:46","Invoice","deleted","Sandesh sir","deleted Invoice Id: 21");
INSERT INTO logs VALUES("2023-01-17 10:03:50","Invoice","deleted","Sandesh sir","deleted Invoice Id: 20");
INSERT INTO logs VALUES("2023-01-17 10:03:51","Invoice","deleted","Sandesh sir","deleted Invoice Id: 19");
INSERT INTO logs VALUES("2023-01-17 10:03:52","Invoice","deleted","Sandesh sir","deleted Invoice Id: 18");
INSERT INTO logs VALUES("2023-01-17 10:03:53","Invoice","deleted","Sandesh sir","deleted Invoice Id: 17");
INSERT INTO logs VALUES("2023-01-17 10:03:53","Invoice","deleted","Sandesh sir","deleted Invoice Id: 16");
INSERT INTO logs VALUES("2023-01-17 10:03:54","Invoice","deleted","Sandesh sir","deleted Invoice Id: 15");
INSERT INTO logs VALUES("2023-01-17 10:03:55","Invoice","deleted","Sandesh sir","deleted Invoice Id: 14");
INSERT INTO logs VALUES("2023-01-17 10:03:55","Invoice","deleted","Sandesh sir","deleted Invoice Id: 13");
INSERT INTO logs VALUES("2023-01-17 10:03:56","Invoice","deleted","Sandesh sir","deleted Invoice Id: 12");
INSERT INTO logs VALUES("2023-01-17 10:03:57","Invoice","deleted","Sandesh sir","deleted Invoice Id: 11");
INSERT INTO logs VALUES("2023-01-17 10:04:02","Invoice","deleted","Sandesh sir","deleted Invoice Id: 10");
INSERT INTO logs VALUES("2023-01-17 10:15:48","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 10:21:14","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 10:28:38","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 10:46:56","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 11:42:13","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 11:51:10","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 11:59:09","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 12:03:59","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 12:10:56","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 12:15:40","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 12:33:10","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 12:41:45","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 13:02:26","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 14:25:10","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-17 14:32:33","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 14:40:35","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 14:54:02","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 14:59:00","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 15:04:11","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-17 15:48:54","supplier","created","","");
INSERT INTO logs VALUES("2023-01-17 15:51:43","Invoice","upload","","");
INSERT INTO logs VALUES("2023-01-18 23:59:56","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 21");
INSERT INTO logs VALUES("2023-01-19 00:00:00","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 7 by 11");
INSERT INTO logs VALUES("2023-01-19 12:09:31","supplier","created","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-19 12:16:04","Invoice","upload","Sandesh sir","");
INSERT INTO logs VALUES("2023-01-19 12:22:47","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 30");
INSERT INTO logs VALUES("2023-01-19 12:22:53","Edit Request","Accepted","","Request Created by  for Edit Request Id 8 by ");
INSERT INTO logs VALUES("2023-01-19 12:32:40","Invoice","updated","Sandesh sir","Invoice Id: 30");
INSERT INTO logs VALUES("2023-01-19 12:33:56","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 30");
INSERT INTO logs VALUES("2023-01-19 12:34:01","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 9 by 11");
INSERT INTO logs VALUES("2023-01-19 12:37:24","Invoice","updated","Sandesh sir","Invoice Id: 30");
INSERT INTO logs VALUES("2023-01-19 17:11:33","Edit Request","created","Sandesh sir","Request Created by 11 for Invoice Id 30");
INSERT INTO logs VALUES("2023-01-19 17:12:21","Edit Request","Accepted","Sandesh sir","Request Created by 11 for Edit Request Id 10 by 11");
INSERT INTO logs VALUES("2023-01-19 17:12:28","Invoice","updated","Sandesh sir","Invoice Id: 30");
INSERT INTO logs VALUES("2023-01-19 17:19:02","Invoice","updated","Sandesh sir","Invoice Id: 21");
INSERT INTO logs VALUES("2023-01-23 20:24:12","Invoice","upload","karan dhanwani","");
INSERT INTO logs VALUES("2023-01-23 20:25:13","Invoice","upload","karan dhanwani","");
INSERT INTO logs VALUES("2023-01-23 20:25:31","Invoice","deleted","karan dhanwani","deleted Invoice Id: 32");
INSERT INTO logs VALUES("2023-01-23 20:25:38","Invoice","deleted","karan dhanwani","deleted Invoice Id: 31");
INSERT INTO logs VALUES("2023-01-23 20:25:54","Edit Request","created","karan dhanwani","Request Created by 10 for Invoice Id 30");
INSERT INTO logs VALUES("2023-01-23 20:25:57","Edit Request","Accepted","karan dhanwani","Request Created by 10 for Edit Request Id 11 by 10");





CREATE TABLE `supplier` (
  `supplier_id` bigint NOT NULL,
  `firm_id` bigint NOT NULL,
  `supplier_name` varchar(1000) NOT NULL,
  `supplier_address` varchar(1500) NOT NULL,
  `supplier_gst_no` varchar(20) NOT NULL,
  `supplier_state` varchar(20) NOT NULL,
  `supplier_state_code` varchar(20) NOT NULL,
  `supplier_hsn_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO supplier VALUES("1","1","SESA CARE PVT LTD","KHASRA NO-333/71/2/3 AND 652/77/1,NAHAN ROAD,MAUJA DHAULAKUAN TEHSIL PAONTA SAHIB,SIRMAUR,HIMACHAL PRADESH-173001.","02ABACS7064C1ZS","HIMACHAL PRADESH","02","998599");
INSERT INTO supplier VALUES("2","1","NIVEA INDIA PRIVATE LIMITED","4 TH FLR,PHOENIX MARKET CITY,KURLA WEST MUMBAI-40070","27AACCN1990P1ZV","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("3","1","BADSHAH MASALA PRIVATE LTD","SHED NO.A1-179,GIDC,UMBERGAON,VALSAD,GUJARAT.","24AAFCJ0494B1ZY","GUJARAT","24","998599");
INSERT INTO supplier VALUES("4","1","WONDERCHEF HOME APPLIANCES PVT LTD","3RD FLR,B-WING,UNIT NO.303,SUPEREME BUSINESS PARK,HIRANANDANI GARDENS,POWAI,MUMBAI-400076","27AAACW8651N1ZO","MAHARSHTRA","27","998599");
INSERT INTO supplier VALUES("5","1","BIKANERWALA FOODS PVT LTD","2272-2275,HSIIDC INDUSTRIAL EASTATE,PHASE II,RAI.SONIPAT,HARYANA-131029","06AAACB061P1ZX","HARYANA ","06","996111");
INSERT INTO supplier VALUES("6","1","HINDUSTAN UNILIVER LTD.","UNILEVER HOUSE,BD SAWANT MARG, CHAKALA,ANDHERI%u20AC MUMBAI","27AAACH1004N1ZU","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("7","1","SIYARAM SILK MILLS LTD","PLOT NO.367,OPP-CASTROL, SILVASSA.DADRA & NAGAR HAVELI, GUJARAT-396230.","26AAACS6995D1Z4","GUJARAT","26","998311");
INSERT INTO supplier VALUES("8","1","LMN MARKETING PVT.LTD.","B-2/109,MAYUR APARTMENT, SECTOR -9,ROHINI, NEW DELHI-110085","07AABCL7928C1ZL","NEW DELHI","07","998599");
INSERT INTO supplier VALUES("9","1","ANHEUSER BUSCH INBEV INDIA LIMITED","GREEN HEART BLDG,MANYATA TECH PARK,PHASE IV,NAGAVARA, BANGLORE-560045","29AAICS2238R1ZL","KARNATAKA","29","998599");
INSERT INTO supplier VALUES("10","1","KEWALRAJ & COMPANY","E/3,CUFF CASTLE,CUFF PARADE COLABA,MUMBAI-1 Maharashtra","27AAIFK7371A2ZD","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("11","2","THE VIBRANT CORP.","B-803,IMPERIAL HEIGHTS, BEST COLONY,GOREGAON (WEST) MUMBAI-62","27ADBPC0982D1Z1","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("12","2","G G MARKETING","49/15,FIRST FLR,EAST PATEL NAGAR, NEW DELHI-110008.","07AAJPG0707H1Z2","NEW DELHI","07","998599");
INSERT INTO supplier VALUES("13","2","MANISHA INTERNATIONAL PVT LTD.","1-A,KHASRA NO.275,1ST FLR, WESTERN MARG,SAIDULAJAB, NEW DELHI-110030","07AAACM8865G1Z7","NEW DELHI","07","998599");
INSERT INTO supplier VALUES("14","2","SHIVRAJ KAPPOOR & BROTHERS","2 ND FLR,NAKSHATRA MALL, RANADE ROAD,DADAR WEST MUMBAI-28","27AAAFS7003F1ZJ","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("15","2","SEVEN SEAS MARKETING","M-35,C.R.PARK,NEW DELHI","07BUMPM5101R1Z4","NEW DELHI-07","07","998599");
INSERT INTO supplier VALUES("16","2","ANJALI ASSOCIATES","323,UDYOG MANDIR NO-1,7-C BHAGOJI KEER MARG,MAHIM WEST ,MUMBAI","27AAAFA3516F1ZZ","MAHARSHTRA","27","998599");
INSERT INTO supplier VALUES("17","2","ELITE CONSULTANCY SERVICES","M-35,C.R PARK,NEW DELHI-110019","07AAQPM7954F1Z6","NEW DELHI","07","998599");
INSERT INTO supplier VALUES("18","3","RAYMOND CONSUMER CARE LTD","C/O,RPM LOGISTICS PVT LTD,  BLDG I-5,SAIDHARA COMPLEX KUSKE  VILLAGE,NASHIK MUMBAI HIGHWAY BHIWANDI-421302","27AAJCR2207E1ZN","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("19","3","UNIVERSAL NUTRISCIENCE PVT.LTD","2ND FLR,FLEET HOUSE, ANDHERI-KURLA ROAD, GAMDEVI-ANDHERI EAST MUMBAI","27AACCU8525F1Z8","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("20","3","VIP CLOTHING LTD","C-6,ROAD NO-22,MIDC,ANDHERI EAST,MUMBAI","27AABCM1549A1ZX","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("21","3","HYGIENIC RESEARCH INSTITUTE PVT.LTD","602,6 TH FLR,SUPREME CHAMBERS,OFF VEERA DESAI ROAD,ANDHERI WEST,MUMBAI SUBURBAN-400053","27AABCH1547F1ZU","MAHARSHTRA","27","998599");
INSERT INTO supplier VALUES("22","1","RR COLLECTIVE","CAFE THOUGHT COMM STUDIO,H NO 1-89/A/3,FLAT NO 501,4,5,ANVI CLASSIC,VITTAL RAO NAGAR ROAD,MADHAPUR,HYDRABAD,RANGAREDDY,TELANGANA,500081.","36ABEFR3354F1Z9","TELANGANA","36","998599");
INSERT INTO supplier VALUES("23","1","SAMS FRUIT PRODUCTS PRIVATE LIMITED","3B,MAPKHAN ROAD COMPOUND,MAPKHAN NAGAR,NEAR MAROL FIRE BRIGAD,MAROL MARISHI ROAD,MAROL NAKA,ANDHERI(EAST),MUMBAI","27AAICS4092D1ZA","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("24","1","CLASSIC QUILTS PRIVATE LIMITED","GD-250,SALT LAKE CITY,SECTOR-III,NORTH 24 ","19AABCC9766D1ZF","WEST BENGAL","19","998599");
INSERT INTO supplier VALUES("25","1","THE SUPREME INDUSTRIES LIMITED","UNIT-2,GATE NO-474,VILLAGE URSE,TAL-MAVAL,DIST-PUNE-410506","27AAACT1344F1ZO","MAHARASHTRA","27","998599");
INSERT INTO supplier VALUES("26","3","KELLOGG INDIA PRIVATE LIMITED","1001-1002,10 TH FLR,HIRANANDANI KNOWLEDGE PARK,HIRANANDANI PARK,POWAI,MUMBAI-400076.","27AAACK1748A1ZZ","MAHARASHTRA","27","998599");





CREATE TABLE `users` (
  `user_id` bigint NOT NULL,
  `user_email` varchar(300) NOT NULL,
  `user_password` varchar(300) NOT NULL,
  `user_first_name` varchar(200) NOT NULL,
  `user_last_name` varchar(200) NOT NULL,
  `user_type` varchar(200) NOT NULL,
  `user_last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO users VALUES("10","karandhanwani20@gmail.com","$2y$10$py/hZ8rtUKI5E/7QCaG9Tebly5.lhqWBLxsDfRPwjU4ugky6f.6jK","karan","dhanwani","super_admin","2022-03-04 03:25:51");
INSERT INTO users VALUES("11","sandesh@yashconsumer.co.in","$2y$10$nFVqsKZ5wcIvs5f9k3sxLOIXBBOUZT5jD7Y87nXV7iUAAs5lRadLu","Sandesh","sir","super_admin","2022-12-03 15:46:44");
INSERT INTO users VALUES("14","sandesh_ghagare@yashcppl.com","$2y$10$mXMVZ5aPjwh0RXFpdfd/Fug40tpuqHlt4bCpoowS1rVjpwc2mOQvK","Sandesh","Ghagare","salescord","2022-04-18 19:22:41");



