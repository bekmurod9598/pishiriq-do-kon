<x-layouts.main>
    @php
        function formatPhoneNumber($phoneNumber) {
        // Telefon raqamini faqat raqamlar bilan tozalash
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Raqamlarning kerakli qismlarini ajratib olish
        $countryCode = substr($cleaned, 0, 2); // AA
        $part1 = substr($cleaned, 2, 3);       // xxx
        $part2 = substr($cleaned, 5, 2);       // yy
        $part3 = substr($cleaned, 7, 2);       // zz

        // Formatlangan telefon raqamini qaytarish
        return "($countryCode) $part1-$part2-$part3";
    }
    @endphp
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">POSTAVSHIKLAR OYNASI
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
                                    Mavjud postavshiklar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Postavshik qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Postavshik nomi</th>
                                            <th style="background-color: #87CEEB; color: white;">Manzili</th>
                                            <th style="background-color: #87CEEB; color: white;">Telefon raqami</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($consignors as $consignor)
                                        <tr>
                                            <td>{{ $consignor->id }}</td>
                                            <td>{{ $consignor->consignor }}</td>
                                            <td>{{ $consignor->adress }}</td>
                                            <td>{{ formatPhoneNumber($consignor->phone) }}</td>
                                            <td>{{ $consignor->created_at }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($consignor->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $consignor->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $consignor->id }}" title="Modelni o'chirish" action="{{ route('consignors.update', $consignor->id ?? 0) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Hozircha hech qanday postavshik mavjud emas.</td>
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
                    <h5 class="modal-title">Yangi postavshik qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('consignors.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="consignor">Postavshik nomi</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="consignor" value="{{ old('consignor') }}" placeholder="Khat decor">
                            </div>
                            @error('consignor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="adress">Manzili</label>
                                <textarea class="form-control" name="adress" id="adress" placeholder="Qo'qon shahri">{{ old('adress') }}</textarea>
                            </div>
                            @error('adress')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="phone">Telefon raqami</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="inputGroupPrepend">+998</span>
                                    <input type="text" class="form-control" maxlength="9" and minlength="9" name="phone"  id="phone" value="{{ old('phone') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                            </div>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi postavshikni saqlash"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
