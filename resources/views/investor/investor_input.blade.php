<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">INVESTOR KIRIM OYNASI
                    </h5>
                </li>
            </ol>
        </div>

        <div class="row p-2">
             {{-- alert chiqarish uchun --}}
             
             <x-alert />
            <div class="col-12">
                <div class="card h-auto">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li>
                                <h5 class="bc-title text-primary">
                                    Mavjud kirimlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#basicModal">
                                <i class="fa fa-plus-square"></i>  Kirim qo'shish
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
                                            <th style="background-color: #87CEEB; color: white;">Investor</th>
                                            <th style="background-color: #87CEEB; color: white;">Naqd</th>
                                            <th style="background-color: #87CEEB; color: white;">Plastik</th>
                                            <th style="background-color: #87CEEB; color: white;">Jami</th>
                                            <th style="background-color: #87CEEB; color: white;">Valyuta turi</th>
                                            <th style="background-color: #87CEEB; color: white;">Izoh</th>
                                            <th style="background-color: #87CEEB; color: white;">Vaqt</th>
                                            <th style="background-color: #87CEEB; color: white;">O'chirish</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($investor_inputs as $investor_input)
                                        <tr>
                                            <td>{{ $investor_input['id'] }}</td>
                                            <td>{{ $investor_input['investor'] }}</td>
                                            <td>{{ number_format($investor_input['naqd'], '2', ',', ' ') }}</td>
                                            <td>{{ number_format($investor_input['plastik'], '2', ',', ' ') }}</td>
                                            <td>{{ number_format($investor_input['naqd']+$investor_input['plastik'], '2', ',', ' ') }}</td>
                                            <td>{{ $investor_input['valyuta'] }}</td>
                                            <td>{{ $investor_input['izoh'] }}</td>
                                            <td>{{ date("d.m.Y", strtotime($investor_input['vaqt'])) }}</td>
                                            <td>
                                                <!-- O'chirish tugmasi -->
                                                @if (date("Y-m-d")==date("Y-m-d", strtotime($investor_input['vaqt'])))
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $investor_input['id'] }}">
                                                        O'chirish
                                                    </button>
                                                     <!-- Modalni komponent sifatida qo'shish -->
                                                    <x-modal modalId="deleteModal{{ $investor_input['id'] }}" title="Kirimni o'chirish" action="{{ route('investor_inputs.update', $investor_input['item']) }}" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Hozircha hech qanday investor kirim mavjud emas.</td>
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
                    <h5 class="modal-title">Yangi kirim qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('investor_inputs.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Investor -->
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="investor">Investor</label>
                                <select name="investor" id="investor" class="form-control">
                                    <option value="">Investorni tanlang</option>
                                    @foreach($investors as $investor)
                                        <option value="{{ $investor->id }}" {{ old('investor') == $investor->id ? 'selected' : '' }}>
                                            {{ $investor->investor }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('investor')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Naqd -->
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="naqd">Naqd:</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="naqd" id="naqd" value="{{ old('naqd') }}">
                                @error('naqd')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Plastik -->
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="plastik">Plastik:</label>
                                <input type="text" class="form-control mb-xl-0 mb-3" name="plastik" id="plastik" value="{{ old('plastik') }}">
                                @error('plastik')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <!-- Total xatosi -->
                                @if ($errors->has('total'))
                                    <div class="text-danger">
                                        {{ $errors->first('total') }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Valyuta -->
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="valyuta">Valyuta</label>
                                <select name="valyuta" id="valyuta" class="form-control">
                                    <option value="">Valyutani tanlang</option>
                                    @foreach($valyutas as $valyuta)
                                        <option value="{{ $valyuta->id }}" {{ old('valyuta') == $valyuta->id ? 'selected' : '' }}>
                                            {{ $valyuta->valyuta }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('valyuta')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Izoh -->
                            <div class="col-xl-12 col-sm-12 mb-3">
                                <label for="izoh">Kirim izohi</label>
                                <textarea class="form-control mb-xl-0 mb-3" name="izoh" id="izoh">{{ old('izoh') }}</textarea>
                                @error('izoh')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                            <button type="submit" class="btn btn-primary light m-r-5" title="Yangi xarajatni saqlash">
                                <i class="fa-check fa-solid fa-check me-1"></i>Saqlash
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
<script>

    $(document).ready(function() {

        $("#search").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#body_field tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })  
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
        const inputSelectors = ['#naqd', '#plastik'];
        $('body').on('input', inputSelectors.join(', '), function(e) {
            digits_float(this);
        });
        inputSelectors.forEach(function(selector) {
            digits_float(selector);
        });
    });
</script>
