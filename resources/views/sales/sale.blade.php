<x-layouts.main>
    <style>
        .row {
            display: flex;
            height: 100vh; /* Bo'limlar butun sahifa balandligini olishi uchun */
        }

        .column {
            flex: 1; /* Har bir bo'lim teng bo'lishi uchun */
            /*padding: 20px;*/
            /*border: 1px solid #000;*/
        }

        .left {
            /*background-color: #f0f0f0;*/
        }

        .right {
            /*background-color: #e0e0e0;*/
        }
    </style>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">YASIN
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
                                    Mahsulotlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="btn tp-btn btn-primary btn-rounded  btn-lg" onclick="nextUnik()"><span
                            	class="btn-icon-start text-primary"><i class="fa fa-shopping-cart"></i>
                            </span>{{ $unik ?? 0 }}</button>
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                <div class="row">
                                    <!--chap bo'lak-->
                                    <div class="col-xxl-6 col-xl-6">
                                        <table class="table table-bordered" style="text-align:center; font-size:14px;">
                                            <thead style="background-color: skyblue; color: black;">
                                                <tr class="table-primary">
                                                    <th>ID</th>
                                                    <th>Mahsulot nomi</th>
                                                    <th>Miqdori</th>
                                                    <th>Sotuv narxi</th>
                                                    <th>Valyuta</th>
                                                    <th>Qo'sish</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body_field">
                                                @if(!empty($message) && count($message) > 0)
                                                    @foreach($message as $item)
                                                        <tr>
                                                            <td style="white-space: wrap; width: 5%;">{{ $item['id'] }}</td>
                                                            <td style="white-space: wrap; width: 55%;">{{ $item['tname'] }}</td>
                                                            <td style="white-space: wrap; width: 10%;">{{ $item['soni'] }}</td>
                                                            <td style="white-space: wrap; width: 15%;">{{ number_format($item['sotuv_narx'], 2, ',', ' ') }}</td>
                                                            <td style="white-space: wrap; width: 10%;">{{ $item['valyuta'] }}</td>
                                                            <td style="white-space: wrap; width: 5%;">
                                                                <button onclick="addItem('{{ $item['madel_id'] }}', '{{ $item['tname'] }}', '{{ $item['soni'] }}')" class="btn btn-primary btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#basicModal" title="Savatchaga qo'shish">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6">Hech qanday natija topilmadi.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!--o'ng bo'lak-->
                                    <div class="col-xxl-6 col-xl-6" id="table_field">
                                        @if(!empty($sale_tovar) && count($sale_tovar) > 0)
                                            <table class="table table-bordered" style="text-align:center; font-size:14px;">
                                                <thead style="background-color: skyblue; color: black;">
                                                    <tr class="table-primary">
                                                        <th>#</th>
                                                        <th>Tovar</th>
                                                        <th>Soni</th>
                                                        <th>Chiqim narxi</th>
                                                        <th>Chegirma</th>
                                                        <th>Jami</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalSoni = 0;
                                                        $totalChiqimNarxi = 0;
                                                        $totalChegirma = 0;
                                                        $totalSumma = 0;
                                                    @endphp
                                        
                                                    @foreach($sale_tovar as $item)
                                                        @php
                                                            $totalSoni += $item['soni'];
                                                            $totalChiqimNarxi += $item['chiqim_narx'] * $item['soni'];  // Chiqim narxini jami hisoblash uchun ko'paytiramiz
                                                            $totalChegirma += $item['chegirma'];
                                                            $totalSumma += ($item['summa'] - $item['chegirma']);
                                                        @endphp
                                                        <tr>
                                                            <td style="white-space: wrap; width: 5%;">{{ $item['i'] }}</td>
                                                            <td style="white-space: wrap; width: 55%;">{{ $item['tname'] }}</td>
                                                            <td style="white-space: wrap; width: 10%;">{{ $item['soni'] }}</td>
                                                            <td style="white-space: wrap; width: 15%;">{{ number_format($item['chiqim_narx'], 2, ',', ' ') }}</td>
                                                            <td style="white-space: wrap; width: 10%;">{{ number_format($item['chegirma'], 2, ',', ' ') }}</td>
                                                            <td style="white-space: wrap; width: 10%;">{{ number_format($item['summa'] - $item['chegirma'], 2, ',', ' ') }}</td>
                                                            <td style="white-space: wrap; width: 5%;">
                                                                <button onclick="deleteItem('{{ $item['id'] }}')" class="btn btn-danger btn-icon-xxs" title="O'chirish">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="table-warning">
                                                        <th colspan="2">Jami</th>
                                                        <th>{{ $totalSoni }}</th>
                                                        <th>{{ number_format($totalChiqimNarxi, 2, ',', ' ') }}</th>
                                                        <th>{{ number_format($totalChegirma, 2, ',', ' ') }}</th>
                                                        <th>{{ number_format($totalSumma, 2, ',', ' ') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
          <form id="salesForm" method="POST" action="{{ route('sales.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tovarni savatga qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    <input type="hidden" class="form-control" name="tovar_id" id="tovar_id">
                    <input type="hidden" class="form-control" name="tovar_soni" id="tovar_soni">
                </div>
                <div class="modal-body">
                    <div class="col-xl-12 col-sm-12 mb-3">
                        <label for="tovar">Tovar</label>
                        <input type="text" class="form-control" name="tovar" id="tovar" readonly>
                    </div>
                    <div class="col-xl-12 col-sm-12 mb-3">
                        <label id="valyuta_label" for="chegirma">Chegirma </label>
                        <input type="text" class="form-control" name="chegirma" id="chegirma">
                        <div class="text-danger" id="chegirmaError"></div>
                    </div>
                    <div class="col-xl-12 col-sm-12 mb-3">
                        <label for="soni">Soni</label>
                        <input type="text" class="form-control" name="soni" id="soni">
                        <div class="text-danger" id="soniError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                    <button type="submit" class="btn btn-primary light m-r-5" title="Yangi turni saqlash">
                        <i class="fa-check fa-solid fa-check me-1"></i>Saqlash
                    </button>
                </div>
            </div>
          </form>

        </div>
    </div>
</x-layouts.main>
<script src="{{ asset('js/functions.js') }}"></script>
<script>

    function addItem(id, tovar, soni){
        $('#tovar_id').val(id);
        $('#tovar_soni').val(soni);
        $('#tovar').val(tovar);
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
        const inputSelectors = ['#chegirma'];
        $('body').on('input', inputSelectors.join(', '), function(e) {
            digits_float(this);
        });
        inputSelectors.forEach(function(selector) {
            digits_float(selector);
        });
    });
    
    $(document).ready(function () {
    $('#salesForm').on('submit', function (e) {
        e.preventDefault(); // Sahifani qayta yuklashni oldini olish
        $('.text-danger').html(''); // Xatolik xabarlarini tozalash

        var formData = $(this).serialize(); // Formadagi ma'lumotlarni to'plash

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'), // Formaning action URLini oladi
            data: formData,
            success: function (response) {
                if (response.message.startsWith("Xatolik! R"))
                    $('#chegirmaError').html(response.message);
                else if (response.message.startsWith("Xatolik! O"))
                    $('#soniError').html(response.message);
                else{
                    alert(response.message);
                    createSalesTable(response, '#table_field');
                    $('#basicModal').modal('hide'); // Modalni yopish
                }
                    
            },
            error: function (xhr) {
                // Validatsiya xatoliklarini ko'rsatish
                var errors = xhr.responseJSON.errors;
                
                if (errors.chegirma) {
                    $('#chegirmaError').html(errors.chegirma[0]); // Chegirma uchun xatolik
                }

                if (errors.soni) {
                    $('#soniError').html(errors.soni[0]); // Soni uchun xatolik
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

    
    function deleteItem(id){
        $.ajax({
            url: '{{ route('sales.update', ':id') }}'.replace(':id', id),
            type: 'POST',  // PUT metodi orqali yangilash
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token qo'shish
                _method: 'PUT',  // Laravel PUT so'rovini tanishi uchun _method dan foydalaniladi
            },
            success: function(response) {
                alert(response.message);
                createSalesTable(response, '#table_field');
            },
            error: function(xhr, status, error) {
                alert("Xatolik: " + error);
            }
        });
    }
    
    function nextUnik(){
        $.ajax({
            url: '{{ route("unik.next") }}', // AJAX so'rovi yuboriladigan URL
            type: 'POST', // So'rov turi (POST yoki GET)
            data: {
                _token: '{{ csrf_token() }}', // CSRF tokenni yuborish
            },
            success: function(response) {
                console.log('AJAX muvaffaqiyatli yuborildi!');
                location.reload();
            },
            error: function(xhr) {
                console.log('AJAXda xatolik yuz berdi:', xhr.responseText);
            }
        });
    }


</script>
