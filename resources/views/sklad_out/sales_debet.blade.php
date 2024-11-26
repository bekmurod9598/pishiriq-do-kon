<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">NASIYA SAVDOLAR OYNASI
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
                                    Nasiya savdolar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
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
                                            <th style="background-color: #87CEEB; color: white;">Telefon raqami</th>
                                            <th style="background-color: #87CEEB; color: white;">Naqd</th>
                                            <th style="background-color: #87CEEB; color: white;">Plastik</th>
                                            <th style="background-color: #87CEEB; color: white;">Nasiya</th>
                                            <th style="background-color: #87CEEB; color: white;">Jami</th>
                                            <th style="background-color: #87CEEB; color: white;">To'langan</th>
                                            <th style="background-color: #87CEEB; color: white;">Qarzdorlik</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">To'lash vaqti</th>
                                            <th style="background-color: #87CEEB; color: white;">Ko'rish</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body_field">
                                        @forelse ($uniks as $unik)
                                            <tr class="{{ $unik['klass'] }}">
                                                <td>{{ $unik['id'] ?? '-' }}</td>
                                                <td>{{ $unik['client'] ?? '-' }}</td>
                                                <td>{{ $unik['phone'] ?? '-' }}</td>
                                                <td>{{ number_format($unik['naqd'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['plastik'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['nasiya'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['jami'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['paid'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ number_format($unik['debet'] ?? '0', '0', ',', ' ') }}</td>
                                                <td>{{ $unik['valyuta'] ?? '-' }}</td>
                                                <td>{{ $unik['vaqt'] ?? '-' }}</td>
                                                <td>
                                                    <button onclick="viewModalWithData('{{ $unik['id'] }}')" class="btn btn-info btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#viewModal" title="Ko'rish">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11">Hozircha hech qanday savdo mavjud emas.</td> <!-- colspanni 9 ga o'zgartirdim, chunki ustunlar soni oshdi -->
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
                $.each(response.clients, function(index, client) {
                    $('#client').append('<option value="' + client.id + '">' + client.client + '</option>');
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
    
    
    function openDeleteModal(unik_id) {
        $('#unik_id_field').val(unik_id);
    }
        
    $(document).ready(function() {
        $("#search").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#body_field tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })  
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
    
        // Jadvalning boshlang'ich HTML qismi
        var tableHTML = '<table class="table-sm table-hover" style="font-size: 11px; text-align: center; width:100%;">';
        tableHTML += '<thead><tr>';
        tableHTML += '<th colspan="2" class="border-bottom" style="padding: 0px;">Maxsulotlar</th>';
        tableHTML += '</tr></thead><tbody>';
    
        // Ma'lumotlar asosida jadvalni shakllantirish
        if (data.sale_tovar || data.sale_tovar.length > 0) {
            $.each(data.sale_tovar, function (index, item) {
                tableHTML += '<tr class="align-middle text-center">';
                tableHTML += '<td colspan="2" class="text-start" style="padding: 0px;text-indent:8px;">' + item.tname + '</td>';
                tableHTML += '</tr>';
                tableHTML += '<tr class="align-middle text-center">';
                tableHTML += '<td colspan="2" class="text-end border-bottom" style="padding: 0px; padding-right: 8px;">' + item.soni + " x " + formatNumberWithSpaces(item.chiqim_narx) + " = " + formatNumberWithSpaces(item.summa.toFixed(2)) + '</td>';
    
                // Umumiy soni va summa qiymatlarini hisoblash
                totalSoni += parseFloat(item.soni);
                totalChiqimNarx += parseFloat(item.chiqim_narx);
                totalSumma += parseFloat(item.summa);
            });
        } else {
            // Agar ma'lumot bo'lmasa
            tableHTML += '<tr><td colspan="3">Mahsulot mavjud emas!</td></tr>';
        }
    
        // Jadvalning footer qismi
        tableHTML += '</tbody><tfoot>';
        tableHTML += '<tr>';
        tableHTML += '<th><b>Jami</b></th>';
        tableHTML += '<th class="text-end"><b>' + formatNumberWithSpaces(totalSumma.toFixed(2)) + '</b></th>';
        tableHTML += '</tr>';
        tableHTML += '</tfoot></table>';
    
        // Natijani ko'rsatilgan elementga joylashtirish
        $(tableField).html(tableHTML);
    }

</script>
