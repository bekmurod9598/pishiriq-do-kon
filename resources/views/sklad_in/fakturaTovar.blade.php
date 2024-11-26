<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">FAKTURAGA TOVAR BIRIKTRISH OYNASI
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
                                    Mavjud Fakturalar ro'yxati
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
                                            <th style="background-color: #87CEEB; color: white;">Faktura</th>
                                            <th style="background-color: #87CEEB; color: white;">Postavshik</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta kursi</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">Amallar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($fakturas as $faktura)
                                        <tr>
                                            <td>{{ $faktura->id }}</td>
                                            <td>{{ $faktura->faktura }}</td> <!-- Type qiymatini ko'rsatish -->
                                            <td>{{ $faktura->consignor->consignor ?? '-' }}</td> <!-- cosignor qiymatini ko'rsatish -->
                                            <td>{{ $faktura->valyuta->valyuta ?? '-'}}</td> <!-- valyuta qiymatini ko'rsatish -->
                                            <td>{{ number_format($faktura->valyuta_kurs, '0','', ' ')     }}</td>
                                            <td>{{ $faktura->created_at }}</td>
                                            <td>
                                                <!-- Tahrirlash va chop etish tugmasi -->
                                                @if(empty($faktura->closed_at))
                                                <button onclick="openModalWithData({{ $faktura->id }})" class="btn btn-primary btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#editModal" title="Tovar tushirish">
                                                    <i class="fa-solid fa-pen-to-square"></i></button>
                                                @else
                                                <button onclick="viewFaktura({{ $faktura->id }})" class="btn btn-info btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#viewModal" title="Tovarlarni ko'rish">
                                                    <i class="fa-solid fa-eye"></i></button>
                                                @endif
                                                <button id="btn_printt" class="btn btn-warning btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#printModal" title="Chop etish">
                                                    <i class="fa-solid fa-print"></i></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Hozircha hech qanday faktura mavjud emas.</td>
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
    <!--fakturaga tovar tushirish-->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">Fakturaga tovar biriktirish</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body">
                        <div class="row">
                            <div class="col-xl-2 col-sm-2">
                                <label for="faktura">Faktura</label>
                                <input type="text" class="form-control" id="faktura" name="faktura" readonly>
                            </div>
    
                            <div class="col-xl-2 col-sm-2 mb-xl-0">
                                <label for="madel">Tovar modeli</label>
                                <select name="madel" id="madel" class="form-control">
                                </select>
                            </div>
                            @error('madel')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
    
                            <div class="col-xl-2 col-sm-2 mb-xl-0">
                                <label for="kirim_narx">Kirim narxi (1 dona uchun)</label>
                                <input type="text" class="form-control" id="kirim_narx" name="kirim_narx">
                            </div>
                            @error('kirim_narx')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            
                            <div class="col-xl-2 col-sm-2 mb-xl-0">
                                <label for="sotuv_narx">Sotuv narxi (1 dona uchun)</label>
                                <input type="text" class="form-control" id="sotuv_narx" name="sotuv_narx">
                            </div>
                            @error('sotuv_narx')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
    
                            <div class="col-xl-2 col-sm-2 mb-xl-0">
                                <label for="soni">Tovar soni</label>
                                <input type="number" class="form-control" id="soni" name="soni">
                            </div>
                            @error('soni')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
    
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}" />
                            <input type="hidden" name="faktura_id" id="faktura_id" />
                            <div class="col-xl-2 col-sm-2 mb-xl-0">
                                <br>
                                <button class="btn btn-outline-primary" style="margin-top: 5px;" onclick="add_product()"><i class="fa-solid fa-notes-medical"></i>  Qo'shish</button>
                            </div>
    
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3" id="table_field">
    
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('close_faktura', ['id' => 1]) }}" method="POST">
                        @csrf
                            <input type="hidden" name="faktura_id0" id="faktura_id0" />
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                            <button type="submit" class="btn btn-primary light m-r-5" title="Yangi fakturani saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>
    
    <!--faktura  tovarlarini ko'rish-->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">Faktura: <span id="title_field" class="text-white"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body">
                        <div class="row">
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}" />
                            <input type="hidden" name="faktura_id" id="faktura_id" />
    
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
</x-layouts.main>
    <script>
    
    //modal oynani shakllantirish va ochish
    function openModalWithData(fakturaId){
        // Modalni tozalash
        $('#faktura').html('Yuklanmoqda...');
    
        // 1-chi AJAX so'rovi (Faktura ma'lumotini olish)
        $.ajax({
            url: "{{ route('faktura_tovars.edit', ':id') }}".replace(':id', fakturaId),
            type: 'GET', // So'rov turi
            success: function(response) {
                // Modal oynani to'ldirish
                $('#faktura').val(response.faktura.faktura); // Faktura 
                $('#faktura_id').val(fakturaId); // Faktura id
                $('#faktura_id0').val(fakturaId); // Faktura id
    
                $('#madel').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $.each(response.madels, function(index, madel) {
                    $('#madel').append('<option value="' + madel.id + '">' + madel.id + '. ' + madel.type.type + ' ' + madel.brand.brand + ' ' + madel.madel + '</option>');
                });
    
                // 2-chi AJAX so'rovi (Faktura tovar ma'lumotini olish)
                $.ajax({
                    url: "{{ route('faktura_tovars.show', ':id') }}".replace(':id', fakturaId),
                    // url: '/faktura-tovar/' + fakturaId,
                    type: 'GET', // So'rov turi
                    success: function(res) {
                        getFakturaTovarData(res); // Tovar ma'lumotlarini qayta ishlash
                    },
                    error: function(xhr, status, error) {
                        console.error("Tovar ma'lumotlarini olishda xato: ", error);
                        alert("Tovar ma'lumotlarini olishda xato yuz berdi.");
                    }
                });
    
            },
            error: function(xhr, status, error) {
                console.error("Faktura ma'lumotini olishda xato: ", error);
                alert("Faktura ma'lumotini olishda xato yuz berdi.");
            }
        });
    }
    
    function viewFaktura(fakturaId){
        // Modalni tozalash
        $('#faktura').html('Yuklanmoqda...');
        // 1-chi AJAX so'rovi (Faktura ma'lumotini olish)
        $.ajax({
            url: "{{ route('faktura_tovars.edit', ':id') }}".replace(':id', fakturaId),
            type: 'GET', // So'rov turi
            success: function(response) {
                // Modal oynani to'ldirish
                $('#title_field').text(response.faktura.faktura); // Faktura 
    
                // 2-chi AJAX so'rovi (Faktura tovar ma'lumotini olish)
                $.ajax({
                    url: "{{ route('faktura_tovars.show', ':id') }}".replace(':id', fakturaId),
                    // url: '/faktura-tovar/' + fakturaId,
                    type: 'GET', // So'rov turi
                    success: function(res) {
                        getFakturaTovarData(res, 'view'); // Tovar ma'lumotlarini qayta ishlash
                    },
                    error: function(xhr, status, error) {
                        console.error("Tovar ma'lumotlarini olishda xato: ", error);
                        alert("Tovar ma'lumotlarini olishda xato yuz berdi.");
                    }
                });
    
            },
            error: function(xhr, status, error) {
                console.error("Faktura ma'lumotini olishda xato: ", error);
                alert("Faktura ma'lumotini olishda xato yuz berdi.");
            }
        });
    }
        
    function getFakturaTovarData(response, key) {
        let kirimNarxiTotal = 0;
        let sotuvNarxiTotal = 0;
        let soniTotal = 0;
        let summaTotal = 0;
    
        let tableContent = `
            <table class="table table-bordered text-center">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Faktura</th>
                        <th>Tovar</th>
                        <th>Kirim narxi</th>
                        <th>Sotuv narxi</th>
                        <th>Soni</th>
                        <th>Summasi</th>
                        <th>Xodim</th>
                        <th>Vaqt</th>
                        <th>O'chirish</th>
                    </tr>
                </thead>
                <tbody>`;
    
        // Har bir faktura tovarini jadvalga qo'shish
        $.each(response.faktura_tovars, function (index, fakturaTovar) {
            let summa = parseFloat(fakturaTovar.soni * fakturaTovar.kirim_narx); // Summani hisoblash
    
            kirimNarxiTotal += parseFloat(fakturaTovar.kirim_narx);
            sotuvNarxiTotal += parseFloat(fakturaTovar.sotuv_narx);
            soniTotal += parseFloat(fakturaTovar.soni);
            summaTotal += parseFloat(summa);
            
            let button = key && key == 'view' ? '-' :  `<button class="btn btn-outline-danger" onclick="deleteRow(${fakturaTovar.id}, ${response.faktura_id})">
                            <i class="fa-solid fa-trash"></i>  O'chirish
                        </button>`;
            tableContent += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${fakturaTovar.faktura}</td>
                    <td>${fakturaTovar.madel_id + '. ' + fakturaTovar.madel}</td>
                    <td>${formatNumberWithSpaces(fakturaTovar.kirim_narx)}</td>
                    <td>${formatNumberWithSpaces(fakturaTovar.sotuv_narx)}</td>
                    <td>${formatNumberWithSpaces(fakturaTovar.soni)}</td>
                    <td>${formatNumberWithSpaces(summa.toFixed(2))}</td>
                    <td>${fakturaTovar.name}</td>
                    <td>${new Date(fakturaTovar.created_at).toLocaleString()}</td>
                    <td>
                        ${button}
                    </td>
                </tr>`;
        });
    
        // Footer qismi qo'shiladi
        tableContent += `
            </tbody>
            <tfoot class="table-secondary">
                <tr>
                    <td>#</td>
                    <td colspan="2"><strong>Jami:</strong></td>
                    <td><strong>${formatNumberWithSpaces(kirimNarxiTotal.toFixed(2))}</strong></td>
                    <td><strong>${formatNumberWithSpaces(sotuvNarxiTotal.toFixed(2))}</strong></td>
                    <td><strong>${formatNumberWithSpaces(soniTotal)}</strong></td>
                    <td><strong>${formatNumberWithSpaces(summaTotal.toFixed(2))}</strong></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>`;
    
        // Jadvalni div elementiga o'zlashtirish
        if(key)
            $('#table_field_view').html(tableContent);
        else
            $('#table_field').html(tableContent);
    }
    
        
    function add_product(){
        let fakturaId = $('#faktura_id').val();
        // alert(fakturaId);
        let madel = $('#madel').val();
        let user_id = $('#user_id').val();
        let soni = $('#soni').val();
        let kirim_narx = $('#kirim_narx').val();
        let sotuv_narx = $('#sotuv_narx').val();
        if(fakturaId == '' || madel == '' || soni == '' || kirim_narx == '' || sotuv_narx == '')
            alert('Ma`lumotlar to`liq kiritilmadi');
        else
          $.ajax({
             url: '{{ route('faktura_tovars.store') }}',  //Marshrutni to'g'ridan-to'g'ri PHP bilan ulaymiz
             type: 'POST', //  So'rov turi
             data: {
                 _token: '{{ csrf_token() }}',  //CSRF tokenini qo'shish
                 faktura_id: fakturaId,
                 madel: madel,
                 soni: soni,
                 kirim_narx: kirim_narx,
                 sotuv_narx: sotuv_narx,
                 user_id: user_id
             },
             success: function(response) {
                $('#soni').val('');
                $('#kirim_narx').val('');
                $('#sotuv_narx').val('');
                getFakturaTovarData(response);
            },

             error: function(xhr, status, error) {
                    alert('xatolik:'+error);
             }
         });
    }
    
    function deleteRow(tovarId, fakturaId){
          $.ajax({
            url: '{{ route('faktura_tovars.update', ':id') }}'.replace(':id', tovarId),
            type: 'POST',  // PUT metodi orqali yangilash
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token qo'shish
                _method: 'PUT',  // Laravel PUT so'rovini tanishi uchun _method dan foydalaniladi
                faktura_id: fakturaId
            },
            success: function(response) {
                getFakturaTovarData(response);  // Yangilangan ma'lumotlarni qayta ishlash
            },
            error: function(xhr, status, error) {
                alert("Xatolik: " + error);
            }
        });

    }
    
    $(document).ready(function() {
    });

    function digits_float(target) {
        let val = $(target).val().replace(/[^0-9\.]/g, '');
        if (val.indexOf(".") !== -1) {
            val = val.substring(0, val.indexOf(".") + 3);
        }
        val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
        $(target).val(val);
    }

    $(function($) {
        const inputSelectors = ['#kirim_narx', '#sotuv_narx'];
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
</script>
