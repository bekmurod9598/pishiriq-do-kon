<x-layouts.main>
    <style>
        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2mm;
            margin: 0 auto;
            width: 58mm;
            background: #fff;
        }
    
        /* Tanlangan matn uchun stil */
        #invoice-POS ::selection {
            background: #f31544;
            color: #fff;
        }
        #invoice-POS ::-moz-selection {
            background: #f31544;
            color: #fff;
        }
    
        h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }
    
        p {
            text-align: center;
            font-size: 0.7em;
            color: #666;
            line-height: 1.2em;
        }
    
        #top .logo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
            background-size: 60px 60px;
        }
    
        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }
    
        .info {
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-weight: 500;
            letter-spacing: 1px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    
        #top, #mid, #bot, #table {
            font-weight: 500;
            letter-spacing: 1px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    
        .title {
            float: right;
        }
    
        .title p {
            text-align: right;
        }
    
        table {
            width: 100%;
            border-collapse: collapse;
        }
    
        td {
            padding: 5px 0 5px 15px;
            border: 1px solid #EEE;
        }
    
        .tabletitle {
            padding: 5px;
            font-size: 0.5em;
            background: #eee;
        }
    
        .service {
            border-bottom: 1px solid #eee;
        }
    
        .item {
            width: 24mm;
        }
    
        .itemtext {
            font-size: 0.5em;
        }
    
        #legalcopy {
            text-align: center;
            margin-top: 5mm;
        }
    
        .divider {
            border: none;
            border-top: 1px dashed #000;
            color: #000;
            background-color: transparent;
            height: 5px;
            width: 100%;
            margin: 10px 0;
        }
    </style>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">TO'LOVLAR OYNASI</h5>
                </li>
            </ol>
        </div>

        <div class="row p-2">
             <div id="alert-container"></div>
             <!--<div class="row">-->
                 <div class="col-xxl-3 col-xl-4">
                         <div class="card h-auto">
    							<div class="card-header">
    								<h4 class="heading mb-0">To'lov qo'shish</h4>
    							</div>
    							<div class="card-body">
    								<form class="finance-hr" action="{{ route('payments.store') }}" method="POST" id="paymentForm">
    								    @csrf
    									<div class="form-group mb-3">
    										<label class="text-secondary font-w500">Mijoz<span class="text-danger">*</span>
    									    </label>
    									    <select name="client" id="client" class="form-control select2">
    									        <option value="">Mijozni tanlang</option>
    									        @foreach($clients as $client)
    									        <option value="{{ $client->id }}">{{ $client->client }}</option>
    									        @endforeach
                                            </select>
                                            <div class="text-danger" id="client_field" style="display:none;">
                                            </div>
    									</div>
    									<div class="col-xl-12 col-sm-12 mb-3">
                                            <label for="naqd">Naqd</label>
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="text" class="form-control mb-xl-0 mb-3" name="naqd" id="naqd">
                                            <div class="text-danger" id="naqd_field" style="display:none;">
                                            </div>
                                        </div>
    									<div class="col-xl-12 col-sm-12 mb-3">
                                            <label for="plastik">Plastik</label>
                                            <input type="text" class="form-control mb-xl-0 mb-3" name="plastik" id="plastik">
                                            <div class="text-danger" id="plastik_field" style="display:none;">
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
    										<label for="valyuta" class="text-secondary font-w500">Valyuta<span class="text-danger">*</span>
    									    </label>
    									    <select name="valyuta" id="valyuta" class="form-control">
    									        @foreach($valyutas as $valyuta)
    									        <option value="{{ $valyuta->id }}">{{ $valyuta->valyuta }}</option>
    									        @endforeach
                                            </select>
                                            <div class="text-danger" id="valyuta_field" style="display:none;">
                                            </div>
    									</div>
                                        <div class="col d-flex justify-content-center gap-3">
        									<button class="btn btn-outline-danger" style="margin-top: 5px;" onclick="clearForm(0)"><i class="fa-solid fa-trash"></i>  Tozalash</button>
        									<button type="submit" class="btn btn-outline-success" style="margin-top: 5px;" ><i class="fa-solid fa-notes-medical"></i>  Saqlash</button>
    								    </div>
    								</form>
    							</div>
    						</div>
                 </div>
                 
                 <div class="col-xxl-9 col-xl-8">
                    <div class="card">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li>
                                    <h5 class="bc-title text-primary">
                                        Mijoz savdolari
                                    </h5>
                                </li>
                            </ol>
                            <li class="nav-item" role="presentation">
                            </li>
                        </div>
                        <div class="card-body">
                            <div class="people-list dz-scroll">
                                <div class="table-responsive" id="table_field_view">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             <!--</div>-->
        </div>
    </div>

    <!--to'lov qo'shish-->
    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi to'lov qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    
     <!--to'lovni chop etish-->
    <div class="modal fade" id="printModal">
        <div class="modal-dialog modal-l" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Savdoni chop etish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body" id="modal_body_print">
                    <div class="row">
                        <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3" id="table_field_print0">
                            <div id="invoice-POS" style="font-weight: bold; color: black;">
    
                                <center id="top">
                                    <!--<div class="logo"></div>-->
                                    <div class="info"> 
                                        <img width="67" height="67" src="https://img.icons8.com/external-others-inmotus-design/67/external-Y-virtual-keyboard-others-inmotus-design-3.png" alt="external-Y-virtual-keyboard-others-inmotus-design-3"/>
                                        <div style="font-family: Arial; font-size: 30px; color: black; width: 100%; font-weight: bold; letter-spacing: 4px; text-align: center; margin: 0; padding: 0; line-height: 1;">YASIN</div>
                                        <div style="font-family: Arial; font-size: 14px; color: black; width: 100%; font-weight: bolder; font-style: italic; letter-spacing: 1px; text-align: center; margin: 0; padding: 0; line-height: 1; margin-top: 5px;">Materials</div>
                                    </div>
                                </center>
                                
                                <div id="mid">
                                    <div class="info">
                                        <hr class="divider">
                                        <div style="font-size: 12px; text-align: left; width: 100%; margin-top: 10px; font-family: Arial;">
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">To'lov №</span>
                                                <span id="savdo_raqam_field" style="text-indent: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Sana:</span>
                                                <span id="sana_field" style="text-indent: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Mijoz:</span>
                                                <span id="mijoz_field" style="text-indent: 8px;"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="bot">
                                    <div id="table">
                                    </div>
                                        <div style="font-size: 12px; text-align: center; width: 100%; margin-top: 10px; font-family: Arial;">
                                            <div class="service" style="padding: 5px;">
                                                <strong>To'lovlar</strong>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">To'lov (naqd)</span>
                                                <span id="t_naqd_field" style="padding-right: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">To'lov (plastik)</span>
                                                <span id="t_plastik_field" style="padding-right: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Qarzdorlik</span>
                                                <span id="qarz_field" style="padding-right: 8px;"></span>
                                            </div>
                                        </div>
                                    <hr class="divider">
                                    <div id="legalcopy">
                                        <p class="legal" style="text-align: center; font-family: Arial; letter-spacing: 1px;">
                                            <strong>To'lovingiz uchun tashakkur!</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                    <button id="printBtn" type="button" class="btn btn-warning light m-r-5" title="Chop etish">
                        <i class="fa-solid fa-print me-1"></i>Chop etish
                    </button>

                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
<script>

    $(document).ready(function() {
        $('.select2').select2({
        });
        $('#paymentForm').on('submit', function (e) {
            e.preventDefault(); // Sahifani qayta yuklashni oldini olish
            $('.text-danger').html(''); // Xatolik xabarlarini tozalash
        
            var formData = $(this).serialize(); // Formadagi ma'lumotlarni to'plash
        
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Formaning action URLini oladi
                data: formData,
                success: function(response) {
                    if (response.success) {
                        if (response.message && response.message.indexOf('Tulov') === 0) {
                            $('#naqd_field').html(response.message).show();
                            // alert(response.message);
                        }
                        else {
                            //formani tozalash
                            clearForm(response.client);
                            
                            //Mijoz ma'lumotlarini yangilash
                            $.ajax({
                                url: "{{ route('payments.show', ':id') }}".replace(':id', response.client),
                                type: 'GET', // So'rov turi
                                success: function(res) {
                                    createSalesTable(res); // To'lov ma'lumotlarini qayta ishlash
                                },
                                error: function(xhr, status, error) {
                                    console.error("To'lov ma'lumotlarini olishda xato: ", error);
                                    alert("To'lov ma'lumotlarini olishda xato yuz berdi.");
                                }
                            });
                            
                            let successAlert = `
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Muvaffaqiyat!</strong> To'lov muvaffaqiyatli saqlandi.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                
                            // Alertni sahifaga qo'shish
                            $('#alert-container').html(successAlert);
                
                            // Alertni avtomatik ravishda yopish (ixtiyoriy)
                            setTimeout(function() {
                                $('#alert-container .alert').alert('close');
                            }, 3000); // 3 soniyada yopiladi
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validatsiya xatolari mavjud bo'lsa
                        let errors = xhr.responseJSON.errors;
                        
                        // Har bir xatolikni tegishli maydonga chiqarish
                        if (errors.client) {
                            $('#client_field').html(errors.client[0]).show();
                        }
                        if (errors.naqd) {
                            $('#naqd_field').html(errors.naqd[0]).show();
                        }
                        if (errors.plastik) {
                            $('#plastik_field').html(errors.plastik[0]).show();
                        }
                        if (errors.valyuta) {
                            $('#valyuta_field').html(errors.valyuta[0]).show();
                        }
                        if (errors.user_id) {
                            $('#user_id_field').html(errors.user_id[0]).show();
                        }
                    }
                }
            });
        });

        $("#search").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#body_field tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })  
            
        $('#client').change(function() {
            let client_id = $(this).val();
            if(client_id != '' && client_id != null)
                $.ajax({
                    url: "{{ route('payments.show', ':id') }}".replace(':id', client_id),
                    type: 'GET', // So'rov turi
                    success: function(res) {
                        createSalesTable(res); // To'lov ma'lumotlarini qayta ishlash
                    },
                    error: function(xhr, status, error) {
                        console.error("To'lov ma'lumotlarini olishda xato: ", error);
                        alert("To'lov ma'lumotlarini olishda xato yuz berdi.");
                    }
                });
            else
                $('#table_field_view').empty();
        });
    });
    
    //chop etish uchun oynani shakllantirish va ochish
    function printItem(id){
    
        $.ajax({
                url: "{{ route('payments.edit', ':id') }}".replace(':id', id),
                // url: '/faktura-tovar/' + fakturaId,
                type: 'GET', // So'rov turi
                success: function(response) {
                    $('#t_naqd_field').text(formatNumberWithSpaces(response.payment.naqd ?? 0));
                    $('#t_plastik_field').text(formatNumberWithSpaces(response.payment.plastik ?? 0));
                    $('#qarz_field').text(formatNumberWithSpaces(response.qarz.toFixed(2)));
                    $('#savdo_raqam_field').text(response.payment.payment_id);
                    $('#sana_field').text(response.payment.created_at);
                    $('#mijoz_field').text(response.payment.client);
                    // createPrintArea(response, '#table', 'readonly'); // Tovar ma'lumotlarini qayta ishlash
                },
                error: function(xhr, status, error) {
                    console.error("Tovar ma'lumotlarini olishda xato: ", error);
                    alert("Tovar ma'lumotlarini olishda xato yuz berdi.");
                }
            });
    }
    
    document.getElementById("printBtn").addEventListener("click", function() {
        var printContents = document.getElementById("modal_body_print").innerHTML;

        var printWindow = window.open("", "_blank", "width=600,height=600,top=200,left=600");
        printWindow.document.open();
        printWindow.document.write(`
            <html>
                <head>
                    <title>Chop etish</title>
                    ${Array.from(document.styleSheets)
                        .map(sheet => sheet.href ? `<link rel="stylesheet" href="${sheet.href}">` : "")
                        .join("\n")}
                    
                </head>
                <body onload="window.print(); window.close();">
                    ${printContents}
                </body>
            </html>
        `);
        printWindow.document.close();
    });
    
    function createSalesTable(data) {
        $('#table_field_view').html('Yuklanmoqda...');
    
        let totalNaqd = 0;
        let totalPlastik = 0;
        let totalNaqdT = 0;
        let totalPlastikT = 0;
        let totalNasiya = 0;
        let totalQarz = 0;
    
        // Jadvalning boshlang'ich HTML qismi
        let tableHTML = '<table class="table table-bordered" id="salesTable" style="text-align:center; font-size:14px;">';
        tableHTML += '<thead><tr class="table-primary">';
        tableHTML += '<th>#</th>';
        tableHTML += '<th>Savdo raqam</th>';
        tableHTML += '<th>Naqd</th>';
        tableHTML += '<th>Plastik</th>';
        tableHTML += '<th>Nasiya</th>';
        tableHTML += '<th>Holat</th>';
        tableHTML += '<th>Qarz summasi</th>';
        tableHTML += '<th>Yopilish vaqti</th>';
        tableHTML += '</tr></thead><tbody>';
    
        // Ma'lumotlar asosida jadvalni shakllantirish
        if (data.sales.length > 0) {
            $.each(data.sales, function(index, item) {
                // Qiymatlarni jamlash
                totalNaqd += parseFloat(item.naqd);
                totalPlastik += parseFloat(item.plastik);
                totalNasiya += parseFloat(item.nasiya);
                totalQarz += parseFloat(item.qarz_summasi);
    
                tableHTML += '<tr>';
                tableHTML += '<td>' + item.i + '</td>'; // Ketma-ket soni
                tableHTML += '<td>' + item.unik + '</td>'; // Sotuv ID
                tableHTML += '<td>' + formatNumberWithSpaces(item.naqd) + '</td>'; // Naqd to'lov summasi
                tableHTML += '<td>' + formatNumberWithSpaces(item.plastik) + '</td>'; // Plastik to'lov summasi
                tableHTML += '<td>' + formatNumberWithSpaces(item.nasiya) + '</td>'; // Nasiya summasi
                tableHTML += '<td>' + item.holat + '</td>'; // To'lov holati
                tableHTML += '<td>' + formatNumberWithSpaces(item.qarz_summasi) + '</td>'; // Qarz summasi
                tableHTML += '<td>' + (item.yopilish_vaqti ? item.yopilish_vaqti : '—') + '</td>'; // Yopilish vaqti
                tableHTML += '</tr>';
            });
        } else {
            // Agar ma'lumot bo'lmasa
            tableHTML += '<tr><td colspan="8">Ma\'lumot mavjud emas</td></tr>';
        }
    
        // Jadvalning footer qismi
        tableHTML += '</tbody><tfoot>';
        tableHTML += '<tr class="table-warning">';
        tableHTML += '<th colspan="2">Jami</th>';
        tableHTML += '<th>' + formatNumberWithSpaces(totalNaqd.toFixed(2)) + '</th>';
        tableHTML += '<th>' + formatNumberWithSpaces(totalPlastik.toFixed(2)) + '</th>';
        tableHTML += '<th>' + formatNumberWithSpaces(totalNasiya.toFixed(2)) + '</th>';
        tableHTML += '<th></th>'; // Holat ustuni uchun bo'sh qator
        tableHTML += '<th>' + formatNumberWithSpaces(totalQarz.toFixed(2)) + '</th>';
        tableHTML += '<th></th>'; // Yopilish vaqti ustuni uchun bo'sh qator
        tableHTML += '</tr>';
        tableHTML += '</tfoot></table>';
        
        //to'lovlarni ko'rish
        tableHTML += '<h4 class="text-primary m-3">To\'lovlari</h4>';
        tableHTML += '<table class="table table-bordered" id="paymentTable" style="text-align:center; font-size:14px;">';
        tableHTML += '<thead><tr class="table-primary">';
        tableHTML += '<th>#</th>';
        tableHTML += '<th>Savdo raqam</th>';
        tableHTML += '<th>Naqd</th>';
        tableHTML += '<th>Plastik</th>';
        tableHTML += '<th>Valyuta</th>';
        tableHTML += '<th>To\'lov vaqti</th>';
        tableHTML += '<th>Amallar</th>';
        tableHTML += '</tr></thead><tbody>';
        
        // Ma'lumotlar asosida jadvalni shakllantirish
        if (data.payments.length > 0) {
            $.each(data.payments, function(index, item) {
                // Hozirgi sanani olish (faqat yil, oy, va kun)
                let today = new Date();
                let todayDate = today.toISOString().slice(0, 10); // "YYYY-MM-DD" formatida olish
                
                // created_at-ni bugungi sana ekanligini tekshirish
                let button = item.created_at && item.created_at.slice(0, 10) === todayDate
                    ? '<button onclick="deleteItem(\'' + item.id + '\')" class="btn btn-danger btn-icon-xxs" title="O\'chirish"><i class="fa-solid fa-trash"></i></button>'
                    : '';

                button += ' <button onclick="printItem(\'' + item.id + '\')" class="btn btn-warning btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#printModal" title="Chop etish"><i class="fa-solid fa-print"></i></button>';
                // Qiymatlarni jamlash
                totalNaqdT += parseFloat(item.naqd);
                totalPlastikT += parseFloat(item.plastik);
    
                tableHTML += '<tr>';
                tableHTML += '<td>' + (index+1) + '</td>'; // Ketma-ket soni
                tableHTML += '<td>' + item.unik_id + '</td>'; // Sotuv ID
                tableHTML += '<td>' + formatNumberWithSpaces(item.naqd) + '</td>'; // Naqd to'lov summasi
                tableHTML += '<td>' + formatNumberWithSpaces(item.plastik) + '</td>'; // Plastik to'lov summasi
                tableHTML += '<td>' + formatNumberWithSpaces(item.valyuta) + '</td>'; // Qarz summasi
                tableHTML += '<td>' + item.created_at + '</td>'; // Yopilish vaqti
                tableHTML += '<td>' + button + '</td>'; // Yopilish vaqti
                tableHTML += '</tr>';
            });
        } else {
            // Agar ma'lumot bo'lmasa
            tableHTML += '<tr><td colspan="6">To\'lov mavjud emas</td></tr>';
        }
    
        // Jadvalning footer qismi
        tableHTML += '</tbody><tfoot>';
        tableHTML += '<tr class="table-warning">';
        tableHTML += '<th colspan="2">Jami</th>';
        tableHTML += '<th>' + formatNumberWithSpaces(totalNaqdT.toFixed(2)) + '</th>';
        tableHTML += '<th>' + formatNumberWithSpaces(totalPlastikT.toFixed(2)) + '</th>';
        tableHTML += '<th></th>'; // Holat ustuni uchun bo'sh qator
        tableHTML += '<th></th>'; // Yopilish vaqti ustuni uchun bo'sh qator
        tableHTML += '<th></th>'; // button ustuni uchun bo'sh qator
        tableHTML += '</tr>';
        tableHTML += '</tfoot></table>';
    
        $('#table_field_view').html(tableHTML);
    }

    function deleteItem(id){
        $.ajax({
            url: '{{ route('payments.update', ':id') }}'.replace(':id', 100),
            type: 'POST',  // PUT metodi orqali yangilash
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token qo'shish
                _method: 'PUT',  // Laravel PUT so'rovini tanishi uchun _method dan foydalaniladi
                id: id
            },
            success: function(response) {
                createSalesTable(response);
            },
            error: function(xhr, status, error) {
                alert("Xatolik: " + error);
            }
        });
    }
    
    function digits_float(target) {
        let val = $(target).val().replace(/[^0-9\.]/g, '');
        if (val.indexOf(".") !== -1) {
            val = val.substring(0, val.indexOf(".") + 3);
        }
        val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        $(target).val(val);
    }

    $(function($) {
        const inputSelectors = ['#naqd', '#plastik'];
        $('body').on('input', inputSelectors.join(', '), function(e) {
            digits_float(this);
        });
        inputSelectors.forEach(function(selector) {
            digits_float(selector);
        });
    });
    
    function formatNumberWithSpaces(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
    
    function clearForm(client_id){
        // Formani tozalash uchun
        $('#paymentForm')[0].reset();

        // Select2 elementlarini tozalash (agar Select2 ishlatilsa)
        $('#client').val(client_id).trigger('change');
        $('#valyuta').val(1).trigger('change');

        // Xato xabarlarini ham tozalaymiz
        $('#client_field').hide();
        $('#naqd_field').hide();
        $('#plastik_field').hide();
        $('#valyuta_field').hide();
    }

</script>
