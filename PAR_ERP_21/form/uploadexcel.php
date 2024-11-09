<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <!-- Bootstrap CSS v5.2.1 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <style>

    </style>

</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <br>
        <div class="container-fluid">
            <!-- ส่วนหัวเรื่อง  เดือน ปีงบประมาณ สำนัก เงินฝาก-->
            <div class="row h5 ">
                <div class="col-md-6 text-start  ">
                    <div id="view_month" class="mb-1"></div>
                    <div id="view_dep"></div>
                </div>
                <div class="col-md-6 text-end ">
                    <div id="view_year" class="mb-1"></div>
                    <div id="view_type"></div>
                </div>
            </div>
            <!-- จบส่วนหัวเรื่อง  เดือน ปีงบประมาณ สำนัก เงินฝาก-->
            <table class="table table-sm text-nowrap" id="mytable">

            </table>
            <input type="hidden" name="row_count" id="row_count">
            <input type="hidden" name="excel_data" id="excel_data">

            <input type="hidden" name="file_name" id="file_name" value="">
            <input type="hidden" name="year" id="year" value="">
            <input type="hidden" name="dep" id="dep" value="">
            <input type="hidden" name="month" id="month" value="">
            <input type="hidden" name="type" id="type" value="">

        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>

    <script>
        $(document).ready(function() {
            $("#mytable").hide();
            // When a file is selected
            $('#FILE_DATA').change(function(e) {
                var file = e.target.files[0]; // Get the file
                if (file) {
                    console.log("Selected file:", file.name);
                    // Create an instance of ExcelToJSON and parse the file
                    var excelToJSON = new ExcelToJSON();
                    excelToJSON.parseExcel(file);
                }

                $("#file_name").val(file.name);
            });
        });




        var ExcelToJSON = function() {
            this.parseExcel = function(file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var data = e.target.result;
                    var workbook = XLSX.read(data, {
                        type: 'binary'
                    });

                    workbook.SheetNames.forEach(function(sheetName) {
                        // Convert each sheet to JSON and log it
                        var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                        var json_object = JSON.stringify(XL_row_object);
                        console.log("Sheet: " + sheetName);
                        //console.log(json_object); // Output JSON to console
                        var jsdecode = JSON.parse(json_object);
                        //console.log(jsdecode);
                        var datajson = [];
                        var dataheader = [];

                        $(Object.entries(jsdecode[0])).each(function(k, v) {
                            dataheader.push(v);
                        })
                        $(Object.entries(jsdecode[1])).each(function(k, v) {
                            dataheader.push(v);
                        })

                        $("#year").val(dataheader[1][1]);
                        $("#dep").val(dataheader[0][1] + dataheader[3][1]);
                        $("#month").val(dataheader[0][0]);
                        $("#type").val(dataheader[4][1]);

                        $("#view_year").text(dataheader[1][1]);
                        $("#view_dep").text(dataheader[0][1] + dataheader[3][1]);
                        $("#view_month").text(dataheader[0][0]);
                        $("#view_type").text(dataheader[4][1]);

                        console.log(dataheader);

                        $(jsdecode).each(function(k, v) {
                            if (Object.values(v).includes('END')) {
                                return false;
                            } else {
                                if (k >= 4) {
                                    const updatedRow = {};

                                    for (const [key, value] of Object.entries(v)) {
                                        if (key.includes("บัญชีการจ่ายเงินเดือนลูกจ้างชั่วคราวเงินนอกงบประมาณ")) {
                                            updatedRow["__EMPTY_NO"] = value;
                                        } else if (key == "__EMPTY") {
                                            updatedRow["__EMPTY_0"] = value; // New key name

                                        } else {
                                            updatedRow[key] = value; // Keep other keys as they are

                                        }
                                    }

                                    for (let i = 0; i <= 13; i++) {
                                        const key = `__EMPTY_${i}`;
                                        if (updatedRow[key] === undefined) {
                                            updatedRow[key] = '-';
                                        }
                                    }
                                    datajson.push(updatedRow);


                                }
                            }

                        })

                        var datatosend = [];
                        var count = 0;
                        $(datajson).each(function(k, v) {
                            if (!v.__EMPTY_NO.toString().includes('รวม')) {
                                count++;

                                datatosend.push(v);
                            }
                        })

                        $("#excel_data").val(JSON.stringify(datatosend));
                        $("#row_count").val(count);
                        $("#mytable").show();
                        $("#mytable").DataTable({
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/th.json',
                            },
                            paging: false,
                            data: datajson,
                            scrollX: true,
                            ordering: false,
                            columns: [{
                                    title: 'ลำดับที่',
                                    data: '__EMPTY_NO'
                                },
                                {
                                    title: 'เลขบัตรประชาชน',
                                    data: '__EMPTY_0'
                                },
                                {
                                    title: 'เลขที่บัญชีธนาคาร',
                                    data: '__EMPTY_1'
                                },
                                {
                                    title: 'รายชื่อผู้รับเงิน',
                                    data: '__EMPTY_2'
                                },
                                {
                                    title: 'ตำแหน่ง',
                                    data: '__EMPTY_3'
                                },
                                {
                                    title: 'สังกัด',
                                    data: '__EMPTY_4'
                                },
                                {
                                    title: 'อัตราค่าจ้าง\tเดือนละ',
                                    data: '__EMPTY_5'
                                },
                                {
                                    title: 'รวมยอดจ่ายจริง',
                                    data: '__EMPTY_6'
                                },
                                {
                                    title: 'ประกันสังคม',
                                    data: '__EMPTY_7'
                                },
                                {
                                    title: 'สหกรณ์',
                                    data: '__EMPTY_8'
                                },
                                {
                                    title: 'หนี้ธนาคาร กรุงไทย-ออมสิน',
                                    data: '__EMPTY_9'
                                },
                                {
                                    title: 'กยศ.',
                                    data: '__EMPTY_10'
                                },
                                {
                                    title: 'หนี้ สบน.',
                                    data: '__EMPTY_11'
                                },
                                {
                                    title: 'รับจริง',
                                    data: '__EMPTY_12'
                                },
                                {
                                    title: 'หมายเหตุ',
                                    data: '__EMPTY_13'
                                }

                            ],
                            columnDefs: [{
                                render: function(data, type, row) {
                                    return data == "-" ? data : Number(data.toFixed(2)).toLocaleString('en-US', {
                                        minimumFractionDigits: 2
                                    });
                                },
                                className: 'text-nowrap',
                                targets: [6, 7, 8, 9, 10, 11, 12, 13]
                            }],
                            rowCallback: function(row, data) {
                                if (data.__EMPTY_NO && data.__EMPTY_NO.toString().includes('รวม')) {
                                    $(row).addClass("bg-dark-subtle");

                                }
                            }
                        });

                    });
                };


                reader.onerror = function(ex) {
                    console.log(ex);
                };

                reader.readAsBinaryString(file); // Read the file as a binary string
            };
        };
    </script>


</body>

</html>