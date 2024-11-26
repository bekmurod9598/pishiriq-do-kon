<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">MAHSULOTGA KO'RSATILADIGAN XIZMATLAR OYNASI
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
                                    Ko'rsatilgan xizmatlar ro'yxati
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
                                    <thead class="table-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Sana</th>
                                            <th>Xizmat nomi</th>
                                            <th>Soni</th>
                                            <th>Xizmat Narxi</th>
                                            <th>Chegirma</th>
                                            <th>Umumiy Summa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $date => $models)
                                            @foreach($models as $item)
                                                <tr>
                                                    <td>{{ $item['i'] }}</td>
                                                    <td>{{ date("d.m.Y", strtotime($date)) }}</td>
                                                    <td>{{ $item['tname'] }}</td>
                                                    <td>{{ $item['soni'] }}</td>
                                                    <td>{{ number_format($item['chiqim_narx'], 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($item['chegirma'], 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($item['summa'], 2, ',', ' ') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-layouts.main>
