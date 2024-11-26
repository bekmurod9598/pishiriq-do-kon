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
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">SAVDOLAR OYNASI
                    </h5>
                </li>
            </ol>
        </div>

        <div class="row p-2">
             {{-- alert chiqarish uchu --}}
             <x-alert />
            <div class="col-12">
                <div class="card h-auto">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li>
                                <h5 class="bc-title text-primary">
                                    Mavjud savdolar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            {{-- <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Faktura qo'shish
                            </button> --}}
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
                                    <thead>
                                        <tr >
                                            <th style="background-color: #87CEEB; color: white;">#</th>
                                            <th style="background-color: #87CEEB; color: white;">Mijoz</th>
                                            <th style="background-color: #87CEEB; color: white;">Naqd</th>
                                            <th style="background-color: #87CEEB; color: white;">Plastik</th>
                                            <th style="background-color: #87CEEB; color: white;">Nasiya</th>
                                            <th style="background-color: #87CEEB; color: white;">Chegirma</th>
                                            <th style="background-color: #87CEEB; color: white;">Jami</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">Amallar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body_field">
                                        @forelse ($uniks as $unik)
                                            <tr>
                                                <td>{{ $unik['id'] ?? '-' }}</td>
                                                <td>{{ $unik['client'] ?? '-' }}</td>
                                                <td>{{ number_format($unik['naqd'] ?? '0', '2', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['plastik'] ?? '0', '2', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['nasiya'] ?? '0', '2', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['chegirma'] ?? '0', '2', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['jami'] ?? '0', '2', ',', ' ') }}</td>
                                                <td>{{ $unik['valyuta'] ?? '-' }}</td>
                                                <td>{{ $unik['vaqt'] ?? '-' }}</td>
                                                <td>
                                                    <!-- Tahrirlash va chop etish tugmasi -->
                                                    @if($unik['status'] == 1)
                                                        <button onclick="openModalWithData({{ $unik['id'] }})" class="btn btn-primary btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#editModal" title="Savdoni o'zgartirish">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    @else
                                                        <button onclick="openPrintArea({{ $unik['id'] }})" class="btn btn-warning btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#printModal" title="Chop etish">
                                                            <i class="fa-solid fa-print"></i>
                                                        </button>
                                                    @endif
                                                    <button onclick="viewModalWithData('{{ $unik['id'] }}')" class="btn btn-info btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#viewModal" title="Ko'rish">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    @if (date("Y-m-d")==date("Y-m-d", strtotime($unik['vaqt'])))
                                                        <button type="button" class="btn btn-danger btn-icon-xxs" onclick="openDeleteModal({{ $unik['id'] }})" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Savdoni o'chirish">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    @else
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9">Hozircha hech qanday savdo mavjud emas.</td> <!-- colspanni 9 ga o'zgartirdim, chunki ustunlar soni oshdi -->
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--savdoni shakllantirish-->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-l" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Savdoga mijoz biriktirish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form id="salesTovarForm" action="{{ route('sales_tovars.store') }}" method="POST">
                @csrf
                    <div class="modal-body" id="modal_body">
                        <div class="row">
                            <!--qiymati kerakli maydonlar-->
                            <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id}}">
                            <input type="hidden" id="unik" name="unik">
                            <input type="hidden" id="hidden_jami" name="hidden_jami">
                            
                            <div class="col-xl-12 col-sm-12 mb-xl-0">
                                <label for="client">Mioz</label>
                                <select name="client" id="client" class="form-control select2 mb-3">
                                   
                                </select>
                            </div>
                            @error('client')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3  mt-3">
                                <label for="naqd">Naqd</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="naqd" id="naqd" value="{{ old('naqd') }}">
                            </div>
                            @error('naqd')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="plastik">Plastik</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="plastik" id="plastik" value="{{ old('plastik') }}">
                            </div>
                            @error('plastik')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="nasiya">Nasiya</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="nasiya" id="nasiya" value="{{ old('nasiya') }}">
                            </div>
                            @error('nasiya')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3" id="nasiya_sanasi_field" style="display: none;">
                                <label for="nasiya_sanasi">Nasiya sanasi:</label>
                                <input type="date" class="form-control mb-xl-0 mb-3" name="nasiya_sanasi" id="nasiya_sanasi" value="{{ old('nasiya_sanasi') }}">
                            </div>
                            @error('nasiya_sanasi')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="chegirma">Chegirma</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="chegirma" id="chegirma" value="{{ old('chegirma') }}">
                            </div>
                            <div class="text-danger" id="chegirma_feedback" style="display:none;">
                            </div>
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-3">
                                <label for="valyuta">Valyuta</label>
                                <select name="valyuta" id="valyuta" class="form-control mb-3">
                                </select>
                            </div>
                            @error('valyuta')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Savdoni saqlash"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!--savdoni ko'rish-->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Savdoni ko'rish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body" id="modal_body">
                    <div class="row">
                        <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3" id="table_field_view">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                </div>
            </div>
        </div>
    </div>
    
     <!--savdoni chop etish-->
    <div class="modal fade" id="printModal">
        <div class="modal-dialog modal-l" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Savdoni ko'rish</h5>
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
                                                <span style="text-indent: 8px;">Savdo №</span>
                                                <span id="savdo_raqam_field" style="text-indent: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Sana:</span>
                                                <span style="text-indent: 8px;">{{ date("d.m.Y H:i") }}</span>
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
                                                <span id="naqd_field" style="padding-right: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">To'lov (plastik)</span>
                                                <span id="plastik_field" style="padding-right: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Nasiya</span>
                                                <span id="nasiya_field" style="padding-right: 8px;"></span>
                                            </div>
                                            <div class="service" style="display: flex; justify-content: space-between; padding: 0px;">
                                                <span style="text-indent: 8px;">Chegirma</span>
                                                <span id="chegirma_field" style="padding-right: 8px;"></span>
                                            </div>
                                        </div>
                                    <hr class="divider">
                                    <div id="legalcopy">
                                        <p class="legal" style="text-align: center; font-family: Arial; letter-spacing: 1px;">
                                            <strong>Xaridingiz uchun tashakkur!</strong>
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
    
    <!-- o'chirish -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-l" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Savdoni o'chirish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form id="deleteForm" action="{{ route('sales_tovars.update_sale') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="modal_body">
                        <div class="row">
                            <!-- kerakli maydonlar -->
                            <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" id="unik_id_field" name="unik_id_field">
    
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-3">
                                <label for="client">O'chirish uchun izoh kiriting:</label>
                                <textarea class="form-control" id="reason" name="reason"></textarea>
                            </div>
                            @error('reason')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-danger light m-r-5" title="Savdoni o'chirib tashlash">
                            <i class="fa fa-trash me-1"></i> O'chirish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
<script src="{{ asset('js/functions.js') }}"></script>
    <script>
    
    function viewModalWithData(unik){
        // alert(unik);
        $.ajax({
                url: "{{ route('sales_tovars.show', ':id') }}".replace(':id', unik),
                // url: '/faktura-tovar/' + fakturaId,
                type: 'GET', // So'rov turi
                success: function(response) {
                    createSalesTable(response, '#table_field_view', 'readonly'); // Tovar ma'lumotlarini qayta ishlash
                },
                error: function(xhr, status, error) {
                    console.error("Tovar ma'lumotlarini olishda xato: ", error);
                    alert("Tovar ma'lumotlarini olishda xato yuz berdi.");
                }
            });
    }
    
    //modal oynani shakllantirish va ochish
    function openModalWithData(unik){
    
        $.ajax({
            url: "{{ route('sales_tovars.edit', ':id') }}".replace(':id', unik),
            type: 'GET', // So'rov turi
            success: function(response) {
    
                $('#client').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $('#client').append('<option value="16">Накд савдо мижоз</option>');
                $.each(response.clients, function(index, client) {
                    $('#client').append('<option value="' + client.id + '">' + client.client + '</option>');
                });
                $('#client').select2({
                    dropdownParent: $('#editModal')
                });
                
                $('#valyuta').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $.each(response.valyutas, function(index, valyuta) {
                    $('#valyuta').append('<option value="' + valyuta.id + '">' + valyuta.valyuta + '</option>');
                });
                
                const jami = parseFloat(response.summa);
                $('#hidden_jami').val(jami);
    
                $('#unik').val(unik);
            },
            error: function(xhr, status, error) {
                console.error("Mijoz va valyuta ma'lumotini olishda xato: ", error);
                alert("Mijoz va valyuta ma'lumotini olishda xato yuz berdi.");
            }
        });
    }
    
    //chop etish uchun oynani shakllantirish va ochish
    function openPrintArea(unik){
    
        $.ajax({
                url: "{{ route('sales_tovars.show', ':id') }}".replace(':id', unik),
                // url: '/faktura-tovar/' + fakturaId,
                type: 'GET', // So'rov turi
                success: function(response) {
                    $('#naqd_field').text(formatNumberWithSpaces(response.sales[0].naqd ?? 0));
                    $('#plastik_field').text(formatNumberWithSpaces(response.sales[0].plastik ?? 0));
                    $('#nasiya_field').text(formatNumberWithSpaces(response.sales[0].nasiya ?? 0));
                    $('#chegirma_field').text(formatNumberWithSpaces(response.sales[0].chegirma ?? 0));
                    $('#savdo_raqam_field').text(response.sales[0].unik);
                    $('#mijoz_field').text(response.sales[0].client);
                    createPrintArea(response, '#table', 'readonly'); // Tovar ma'lumotlarini qayta ishlash
                },
                error: function(xhr, status, error) {
                    console.error("Tovar ma'lumotlarini olishda xato: ", error);
                    alert("Tovar ma'lumotlarini olishda xato yuz berdi.");
                }
            });
    }
    
    function openDeleteModal(unik_id) {
        $('#unik_id_field').val(unik_id);
    }
        
    $(document).ready(function() {
        $('#salesTovarForm').on('submit', function (e) {
            e.preventDefault(); // Sahifani qayta yuklashni oldini olish
            $('.text-danger').html(''); // Xatolik xabarlarini tozalash
    
            var formData = $(this).serialize(); // Formadagi ma'lumotlarni to'plash
    
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Formaning action URLini oladi
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.message); // Muvaffaqiyatli xabar chiqarish
                        location.reload();
                    }
                },
                error: function(xhr) {
                    // Xatoliklarni qayta ishlash
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Xatolikni ko'rsatish uchun kerakli input maydonlarini topamiz va invalid-feedback chiqaramiz
                            var input = $('#' + key);
                            // input.addClass('is-invalid'); // Xato bo'lgan maydonni qizil rangda ko'rsatish uchun
                            input.siblings('.invalid-feedback').text(value[0]); // Xatolik matnini invalid-feedback'ga qo'yish
                        });
                    }
                    else if(xhr.status === 423){
                        let errorMessage = xhr.responseJSON.errors;
                        if (errorMessage.chegirma) {
                            $('#chegirma_feedback').text(errorMessage.chegirma).show(); // Xatolikni ko'rsatish
                        } 
                        else {
                            $('#chegirma_feedback').hide(); // Xato bo'lmasa, yashirish
                        }
                        
                    }
                    else if(xhr.status === 424){
                        let errorMessage = xhr.responseJSON.errors;
                        if (errorMessage.chegirma) {
                            $('#chegirma_feedback').text(errorMessage.chegirma).show(); // Xatolikni ko'rsatish
                        } 
                        else {
                            $('#chegirma_feedback').hide(); // Xato bo'lmasa, yashirish
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
        });
        
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


    function digits_float(target) {
    let val = $(target).val().replace(/[^0-9\.]/g, '');
    if (val.indexOf(".") !== -1) {
        val = val.substring(0, val.indexOf(".") + 3);
    }
    val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    $(target).val(val);
}

    function calculateDiscount() {
        // Naqd, Plastik va Nasiya qiymatlarini olamiz
        let naqd = parseFloat($('#naqd').val().replace(/\s/g, '')) || 0;
        let plastik = parseFloat($('#plastik').val().replace(/\s/g, '')) || 0;
        let nasiya = parseFloat($('#nasiya').val().replace(/\s/g, '')) || 0;
        let jami = parseFloat($('#hidden_jami').val()) || 0; // Jami summa data atributidan olinadi
    
        // Hisoblash: Jami summadan naqd, plastik va nasiya qiymatlarini ayiramiz
        let discount = jami - (naqd + plastik + nasiya);
        
        // Agar nasiya maydoniga kiritilgan qiymat 0 dan katta bo'lsa, nasiya sanasi maydonini ko'rsatamiz
        if (nasiya > 0) {
            $('#nasiya_sanasi_field').show();
        } 
        else {
            $('#nasiya_sanasi_field').hide();
        }
    
        // Chegirma maydoniga hisoblangan qiymatni formatlab qo'yamiz
        $('#chegirma').val(formatNumberWithSpaces(discount));
    }
    
    $(function($) {
        const inputSelectors = ['#naqd', '#plastik', '#nasiya', '#chegirma'];
    
        // Input maydonlar uchun 'input' hodisasini kuzatamiz
        $('body').on('input', inputSelectors.join(', '), function(e) {
            digits_float(this);
            calculateDiscount(); // Har bir kiritishda chegirma yangilanadi
        });
    
        // Har bir input maydon uchun boshlang'ich formatlash
        inputSelectors.forEach(function(selector) {
            digits_float(selector);
        });
    });
    
    function formatNumberWithSpaces(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
    
    function createPrintArea(data, tableField, key) {
    // Yuklanmoqda xabarini ko‘rsatish
    $(tableField).html('Yuklanmoqda...');

    var totalSoni = 0;
    var totalSumma = 0;
    var totalChiqimNarx = 0;

    // HTML boshlang'ich qismi
    var divHTML = `
        <div style="font-size: 11px; text-align: center; width: 100%; font-family: Arial;">
            <div style="display: flex; justify-content: center; border-bottom: 1px solid #000; padding: 5px 0;">
                <div style="flex: 1; text-align: center; font-weight: bold; margin-top: 10px;">Maxsulotlar</div>
            </div>
    `;

    // Ma'lumotlar asosida struktura shakllantirish
    if (data.sale_tovar || data.sale_tovar.length > 0) {
        let i = 0;
        $.each(data.sale_tovar, function (index, item) {
            i++;
            divHTML += `
                <div style="display: flex; flex-direction: column; align-items: start; padding: 5px 0;">
                    <div style="text-align: left; padding-left: 8px;">${i}. ${item.madel}</div>
                    <div style="text-align: right; width: 100%; padding-right: 8px; border-bottom: 1px solid #eee;">
                        ${item.soni} x ${formatNumberWithSpaces(item.chiqim_narx)} = ${formatNumberWithSpaces(item.summa.toFixed(2))}
                    </div>
                </div>
            `;

            // Umumiy soni va summa qiymatlarini hisoblash
            totalSoni += parseFloat(item.soni);
            totalChiqimNarx += parseFloat(item.chiqim_narx);
            totalSumma += parseFloat(item.summa);
        });
    } else {
        // Agar ma'lumot bo'lmasa
        divHTML += `<div style="text-align: center; padding: 10px 0;">Mahsulot mavjud emas!</div>`;
    }

    // Jami qiymat uchun footer qismi
    divHTML += `
        <div style="display: flex; justify-content: space-between; padding: 5px 0; border-top: 1px solid #000;">
            <div style="text-align: left; font-weight: bold;">Jami</div>
            <div style="text-align: right; font-weight: bold;">${formatNumberWithSpaces(totalSumma.toFixed(2))}</div>
        </div>
    `;

    // HTML ni yopish
    divHTML += `</div>`;

    // Natijani ko'rsatilgan elementga joylashtirish
    $(tableField).html(divHTML);
}


</script>
