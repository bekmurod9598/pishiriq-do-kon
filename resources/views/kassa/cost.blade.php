<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">XARAJATLAR OYNASI
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
                                    Mavjud xarajatlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="openModal()">
                                <i class="fa fa-plus-square"></i>  Xarajat qo'shish
                            </button>
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
                                    <thead>
                                        <tr >
                                            <th style="background-color: #87CEEB; color: white;">#</th>
                                            <th style="background-color: #87CEEB; color: white;">Xarajat turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Postavshik</th>
                                            <th style="background-color: #87CEEB; color: white;">Summa</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">To'lov usuli</th>
                                            <th style="background-color: #87CEEB; color: white;">Xarajat izohi</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($costs as $cost)
                                        <tr>
                                            <td>{{ $cost->id }}</td>
                                            <td>{{ $cost->type }}</td>
                                            <td>{{ $cost->consignor }}</td>
                                            <td>{{ number_format($cost->summa, '2', ',', ' ') }}</td>
                                            <td>{{ $cost->valyuta }}</td>
                                            <td>{{ $cost->pay_type }}</td>
                                            <td>{{ $cost->izoh }}</td>
                                            <td>{{ $cost->created_at }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($cost->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $cost->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $cost->id }}" title="Xarajatni o'chirish" action="{{ route('costs.update', $cost->id ?? 0) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Hozircha hech qanday xarajat mavjud emas.</td>
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


    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi xarajat qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('costs.store') }}" method="POST" id="costsForm">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="type">Xarajat turi</label>
                                <select name="type" id="type" class="form-control">
                                </select>
                                <div class="text-danger" id="type_field" style="display:none;">
                                </div>
                            </div>
                            <div class="col-xl-12 col-sm-12 mb-3" id="postavshik_field" style="display: none;">
                                <label for="postavshik">Postavshik:</label>
                                <select name="postavshik" id="postavshik" class="form-control mb-3">
                                </select>
                                <div class="text-danger" id="postavshik_text_field" style="display:none;">
                                </div>
                            </div>
                            
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="summa">Xarajat summasi</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="summa" id="summa">
                                <div class="text-danger" id="summa_field" style="display:none;">
                                </div>
                            </div>
                            
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="pay_type">To'lov turi</label>
                                <select name="pay_type" id="pay_type" class="form-control mb-3">
                                    <option value="naqd">Naqd</option>
                                    <option value="plastik">Plastik</option>
                                </select>
                                <div class="text-danger" id="pay_type_field" style="display:none;">
                                </div>
                            </div>
                            
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="valyuta">Valyuta</label>
                                <select name="valyuta" id="valyuta" class="form-control mb-3">
                                </select>
                                <div class="text-danger" id="valyuta_field" style="display:none;">
                                </div>
                            </div>
                            
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="izoh">Xarajat izohi</label>
                                <textarea class="form-control mb-xl-0 mb-3" name="izoh" id="izoh"></textarea>
                                <div class="text-danger" id="izoh_field" style="display:none;">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi xarajatni saqlash"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
<script>

    $(document).ready(function() {
        $('#costsForm').on('submit', function (e) {
            e.preventDefault(); // Sahifani qayta yuklashni oldini olish
            $('.text-danger').html(''); // Xatolik xabarlarini tozalash
        
            var formData = $(this).serialize(); // Formadagi ma'lumotlarni to'plash
        
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Formaning action URLini oladi
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // alert(response.message); // Muvaffaqiyatli xabar chiqarish
                        location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validatsiya xatolari mavjud bo'lsa
                        let errors = xhr.responseJSON.errors;
                        
                        // Har bir xatolikni tegishli maydonga chiqarish
                        if (errors.type) {
                            $('#type_field').html(errors.type[0]).show();
                        }
                        if (errors.postavshik) {
                            $('#postavshik_text_field').html(errors.postavshik[0]).show();
                        }
                        if (errors.summa) {
                            $('#summa_field').html(errors.summa[0]).show();
                        }
                        if (errors.pay_type) {
                            $('#pay_type_field').html(errors.pay_type[0]).show();
                        }
                        if (errors.valyuta) {
                            $('#valyuta_field').html(errors.valyuta[0]).show();
                        }
                        if (errors.izoh) {
                            $('#izoh_field').html(errors.izoh[0]).show();
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
            
        $('#type').change(function() {
            let type = $(this).val();
            if(type==1){
                $('#postavshik_field').show();
            }
            else {
                $('#postavshik_field').hide();
            }
        });
    });
     //modal oynani shakllantirish va ochish
    function openModal(){
        $.ajax({
            url: '{{ route("costs.create") }}',
            type: 'GET', // So'rov turi
            success: function(response) {
    
                $('#type').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $('#type').append('<option value="">Xarajat turini tanlang</option>');
                $.each(response.types, function(index, type) {
                    $('#type').append('<option value="' + type.id + '">' + type.type + '</option>');
                });
                
                $('#valyuta').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $.each(response.valyutas, function(index, valyuta) {
                    $('#valyuta').append('<option value="' + valyuta.id + '">' + valyuta.valyuta + '</option>');
                });
                
                $('#consignors').empty(); // Tovar modellari selectni tozalash
                // Har bir madelni 'select' elementiga qo'shish
                $.each(response.consignors, function(index, consignor) {
                    $('#postavshik').append('<option value="' + consignor.id + '">' + consignor.consignor + '</option>');
                });
                
            },
            error: function(xhr, status, error) {
                alert("Xarajat turi va valyuta ma'lumotini olishda xato yuz berdi.");
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
        const inputSelectors = ['#summa'];
        $('body').on('input', inputSelectors.join(', '), function(e) {
            digits_float(this);
        });
        inputSelectors.forEach(function(selector) {
            digits_float(selector);
        });
    });
</script>
