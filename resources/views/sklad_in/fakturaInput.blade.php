<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">KIRIM FAKTURALAR OYNASI
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
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Faktura qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Faktura</th>
                                            <th style="background-color: #87CEEB; color: white;">Postavshik</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta kursi</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
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
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($faktura->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $faktura->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $faktura->id }}" title="Modelni o'chirish" action="{{ route('fakturas.update', $faktura->id ?? 0) }}" />
                                                @else
                                                    -
                                                @endif
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


    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yangi faktura qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form action="{{ route('fakturas.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <label for="consignor">Postavshik</label>
                                <select name="consignor" id="consignor" class="form-control">
                                    @foreach ($consignors as $consignor)
                                        <option value="{{ $consignor->id }}">{{ $consignor->consignor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('consignor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="faktura">Tovar fakturasi</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0" name="faktura" id="faktura" value="{{ old('faktura') }}" placeholder="faktura">
                            </div>
                            @error('faktura')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="valyuta">Valyuta turi</label>
                                <select name="valyuta" id="valyuta" class="form-control">
                                    @foreach ($valyutas as $valyuta)
                                        <option value="{{ $valyuta->id }}">{{ $valyuta->valyuta }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('valyuta')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-xl-0 mt-3">
                                <label for="kurs">Valyuta kursi</label>
                                <input type="text" class="form-control" id="kurs" name="kurs">
                            </div>
                            @error('kurs')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi fakturani saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
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
            const inputSelectors = ['#kurs'];
            $('body').on('input', inputSelectors.join(', '), function(e) {
                digits_float(this);
            });
            inputSelectors.forEach(function(selector) {
                digits_float(selector);
            });
        });
    </script>
