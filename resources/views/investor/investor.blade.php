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
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">INVESTORLAR OYNASI
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
                                    Mavjud investorlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Investor qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Investor ism-familyasi</th>
                                            <th style="background-color: #87CEEB; color: white;">Manzili</th>
                                            <th style="background-color: #87CEEB; color: white;">Telefon raqami</th>
                                            <th style="background-color: #87CEEB; color: white;">Kiritilgan sana</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($investors as $investor)
                                        <tr>
                                            <td>{{ $investor->id }}</td>
                                            <td>{{ $investor->investor }}</td>
                                            <td>{{ $investor->adress }}</td>
                                            <td>{{ formatPhoneNumber($investor->phone) }}</td>
                                            <td>{{ $investor->created_at }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($investor->created_at)))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $investor->id }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $investor->id }}" title="Investorni o'chirish" action="{{ route('investors.update', $investor) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Hozircha hech qanday investor mavjud emas.</td>
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
                    <h5 class="modal-title">Yangi investor qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('investors.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="investor">Investor ism-familyasi</label>
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="investor" id="investor" value="{{ old('investor') }}">
                            @error('investor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="adress">Manzili</label>
                                <textarea class="form-control" name="adress" id="adress" placeholder="Qo'qon shahri">{{ old('adress') }}</textarea>
                            @error('adress')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="phone">Telefon raqami</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="inputGroupPrepend">+998</span>
                                    <input type="text" class="form-control" maxlength="9" and minlength="9" name="phone"  id="phone" value="{{ old('phone') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            </div>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Investorni saqlash"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
