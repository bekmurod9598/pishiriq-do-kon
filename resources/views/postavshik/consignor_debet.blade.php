<x-layouts.main>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">POSTAVSHIK QARZDORLIK OYNASI
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
                                    Postavshikdan qarzdorliklar ro'yxati
                                </h5>
                            </li>
                        </ol>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
                                    <thead>
                                        <tr>
                                            <th style="background-color: #87CEEB; color: white;">#</th>
                                            <th style="background-color: #87CEEB; color: white;">Postavshik nomi</th>
                                            <th style="background-color: #87CEEB; color: white;">Faktura</th>
                                            <th style="background-color: #87CEEB; color: white;">Qarz summasi</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta</th>
                                            <th style="background-color: #87CEEB; color: white;">Faktura sanasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalQarz = 0; @endphp
                                        @forelse ($fakturas as $faktura)
                                        <tr>
                                            <td>{{ $faktura['id'] }}</td>
                                            <td>{{ $faktura['consignor'] }}</td>
                                            <td>{{ $faktura['faktura'] }}</td>
                                            <td>{{ number_format($faktura['qarz'], 2, ',', ' ') }}</td>
                                            <td>{{ $faktura['valyuta'] }}</td>
                                            <td>{{ $faktura['sana'] }}</td>
                                        </tr>
                                        @php $totalQarz += $faktura['qarz']; @endphp
                                        @empty
                                        <tr>
                                            <td colspan="6">Hozircha hech qanday postavshik mavjud emas.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr>
                                            <td colspan="3" style="font-weight: bold;">Umumiy</td>
                                            <td style="font-weight: bold;">{{ number_format($totalQarz, 2, ',', ' ') }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
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
