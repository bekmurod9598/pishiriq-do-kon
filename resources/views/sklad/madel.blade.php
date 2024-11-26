<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">MAHSULOT MODELLARI OYNASI
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
                                    Mavjud modellar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Model qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Model turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Model brendi</th>
                                            <th style="background-color: #87CEEB; color: white;">Model nomi</th>
                                            <th style="background-color: #87CEEB; color: white;">Sotuv narxi</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">Tahrir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($madels as $madel)
                                        <tr>
                                            <td>{{ $madel->id }}</td>
                                            <td>{{ $madel->type->type ?? 'Noma’lum tur' }}</td> <!-- Type qiymatini ko'rsatish -->
                                            <td>{{ $madel->brand->brand ?? 'Noma’lum brend' }}</td> <!-- Brand qiymatini ko'rsatish -->
                                            <td>{{ $madel->id.". ".$madel->madel }}</td>
                                            <td>{{ number_format($madel->sotuv_narx, '2', ',', ' ') }}</td>
                                            <td>{{ optional($madel->valyuta)->valyuta ?? 'Valyuta mavjud emas' }}</td>
                                            <td>{{ $madel->created_at }}</td>
                                            <td>
                                                <button onclick="openEditModal({{ $madel->id.",".$madel->sotuv_narx }})" class="btn btn-primary btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#editModal" title="Modelni tahrirlash">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($madel->created_at)))
                                                    <button class="btn btn-danger btn-icon-xxs" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $madel->id }}"  title="Madelni o'chirish">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $madel->id }}" title="Modelni o'chirish" action="{{ route('madels.update', $madel->id) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Hozircha hech qanday model mavjud emas.</td>
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

   
    
    <!--qo'shish-->
    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi model qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{ route('madels.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <label for="type">Tovar turi</label>
                                <select name="type" id="type" class="form-control">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="brand">Tovar brendi</label>
                                <select name="brand" id="brand" class="form-control">
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('brand')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="sotuv_narx">Sotuv narx</label>
                                <input type="text" name="sotuv_narx" id="sotuv_narx" class="form-control">
                            </div>
                            @error('sotuv_narx')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="madel">Tovar modeli</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0" name="madel" id="madel" value="{{ old('madel') }}" placeholder="Mdf">
                            </div>
                            @error('madel')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi modelni saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
     <!--tahrirlash-->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sotuv narxni tahrirlash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{ route('madels.update_sotuvnarx') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <label for="type">Valyuta</label>
                                <select name="type" id="type" class="form-control">
                                    @foreach ($valyutas as $valyuta)
                                        <option value="{{ $valyuta->id }}">{{ $valyuta->valyuta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('valyuta')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="madel">Sotuv narx</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" id="madel_id" name="madel_id" />
                                <input type="text" class="form-control mb-xl-0" name="sotuv_narx_e" id="sotuv_narx_e" value="{{ old('sotuv_narx_e') }}">
                            </div>
                            @error('sotuv_narx')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi modelni saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</x-layouts.main>
    <script>
        function digits_float(target) {
            let val = $(target).val().replace(/[^0-9\.]/g, '');
            if (val.indexOf(".") !== -1) {
                val = val.substring(0, val.indexOf(".") + 3);
            }
            val = val.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
            $(target).val(val);
        }
    
        $(function($) {
            const inputSelectors = ['#sotuv_narx', '#sotuv_narx_e'];
            $('body').on('input', inputSelectors.join(', '), function(e) {
                digits_float(this);
            });
            inputSelectors.forEach(function(selector) {
                digits_float(selector);
            });
        });
        
        function openEditModal(id, sotuvnarx){
            let narx = formatNumberWithSpaces(sotuvnarx);
            $('#madel_id').val(id);
            $('#sotuv_narx_e').val(narx);
        }
        
        function formatNumberWithSpaces(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
    </script> 
