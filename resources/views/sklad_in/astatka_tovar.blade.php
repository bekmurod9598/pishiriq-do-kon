<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">OMBORDA MAVJUD TOVARLAR OYNASI
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
                                    Astatka tovarlar ro'yxati
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <!--<a href="{{ route('astatka_tovars.create') }}" class="btn btn-success btn-sm mb-2">-->
                            <!--    <i class="fa fa-file-excel"></i> Excel-->
                            <!--</a>-->

                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="tabpros">
                                @if(!empty($items))
                                <div class="table-responsive">
                                    <table id="exampleTable" class="table table-sm table-bordered" style="text-align:center; font-size:14px; width:100%;">
                                        <thead class="table-info">
                                            <tr>
                                                <th>#</th>
                                                <th>Tovar</th>
                                                <th>Soni</th>
                                                <th>Kirim narxi</th>
                                                <th>Sotuv narxi</th>
                                                <th>Farqi</th>
                                                <th>Jami kirim narx</th>
                                                <th>Jami sotuv narx</th>
                                                <th>Jami farqi</th>
                                                <th>Valyuta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalSoni = 0;
                                                $allKirimNarx = $allSotuvNarx = $allFarq = 0;
                                                $i = 0;
                                            @endphp
                                
                                            @foreach($items as $item)
                                                @php
                                                    $farq = $item['sotuv_narx'] - $item['kirim_narx'];
                                                    $jamiKirimNarx = $item['kirim_narx'] * $item['soni'];
                                                    $jamiSotuvNarx = $item['sotuv_narx'] * $item['soni'];
                                                    $jamiFarq = $jamiSotuvNarx - $jamiKirimNarx;
                                
                                                    $totalSoni += $item['soni'];
                                
                                                    $allKirimNarx += $jamiKirimNarx;
                                                    $allSotuvNarx += $jamiSotuvNarx;
                                                    $allFarq += $jamiFarq;
                                                    $i++;
                                                @endphp
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $item['tname'] }}</td>
                                                    <td>{{ $item['soni'] }}</td>
                                                    <td>{{ number_format($item['kirim_narx'], 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($item['sotuv_narx'], 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($farq, 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($jamiKirimNarx, 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($jamiSotuvNarx, 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($jamiFarq, 2, ',', ' ') }}</td>
                                                    <td>{{ $item['valyuta'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-warning">
                                            <tr>
                                                <th colspan="2">Jami</th>
                                                <th>{{ $totalSoni }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>{{ number_format($allKirimNarx, 2, ',', ' ') }}</th>
                                                <th>{{ number_format($allSotuvNarx, 2, ',', ' ') }}</th>
                                                <th>{{ number_format($allFarq, 2, ',', ' ') }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                @endif
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
                    <h5 class="modal-title">Yangi brend qo'shish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('brands.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id ?? 1}}">
                                <input type="text" class="form-control mb-xl-0 mb-3" name="brand" value="{{ old('brand') }}" placeholder="Mdf">
                            </div>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Yopish</button>
                        <button type="submit" class="btn btn-primary light m-r-5" title="Yangi brendni saqlash" type="submit"><i class="fa-check fa-solid fa-check me-1"></i>Saqlash</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.main>
