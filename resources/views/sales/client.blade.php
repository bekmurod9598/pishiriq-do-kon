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
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">MIJOZLAR OYNASI
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
                                    Mavjud mijozlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Mijoz qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Filial</th>
                                            <th style="background-color: #87CEEB; color: white;">Mijoz ism-familyasi</th>
                                            <th style="background-color: #87CEEB; color: white;">Manzili</th>
                                            <th style="background-color: #87CEEB; color: white;">Telefon raqami</th>
                                            <th style="background-color: #87CEEB; color: white;">Qo'shimcha telefon raqami</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($clients as $client)
                                        <tr>
                                            <td>{{ $client->id }}</td>
                                            <td>{{ $client->branch }}</td>
                                            <td>{{ $client->client }}</td>
                                            <td>{{ $client->adress }}</td>
                                            <td>{{ formatPhoneNumber($client->phone) }}</td>
                                            <td>{{ $client->phone_extra }}</td>
                                            <td>{{ $client->created_at }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($client->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $client->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $client->id }}" title="Mijozni o'chirish" action="{{ route('clients.update', $client->id ?? 0) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Hozircha hech qanday mijoz mavjud emas.</td>
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
                    <h5 class="modal-title">Yangi mijoz qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="client">Mijoz ism-familyasi</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="client" value="{{ old('client') }}" placeholder="Mijoz">
                            </div>
                            @error('client')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="adress">Manzili</label>
                                <textarea class="form-control" name="adress" id="adress" placeholder="Buvayda tumani">{{ old('adress') }}</textarea>
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
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="phone">Telefon raqami (qo'shimcha)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="phone_extra"  id="phone_extra" value="{{ old('phone_extra') }}">
                                </div>
                            </div>
                            @error('phone_extra')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi mijozni saqlash"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</x-layouts.main>
