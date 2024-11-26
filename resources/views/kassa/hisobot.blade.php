<x-layouts.main>

    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">UMUMIY HISOBOT OYNASI
                    </h5>
                </li>
            </ol>
        </div>

        <div class="row p-2">
            <div class="col-12">
                <div class="card h-auto">
                    <div class="page-titles">
                        <ol class="breadcrumb">
                            <li>
                                <h5 class="bc-title text-primary">
                                    Umumiy hisobot
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
                                            <th>Naqd</th>
                                            <th>Plastik</th>
                                            <th>Nasiya</th>
                                            <th>To'lovlar</th>
                                            <th>Investor kirim</th>
                                            <th>Xarajat</th>
                                            <th>Farq</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>{{ number_format($naqd, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($plastik, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($nasiya, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($tolov, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($investor_summa, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($xarajat, 2, ',', ' ') }}</td>
                                            <td>{{ number_format($farq, 2, ',', ' ') }}</td>
                                        </tr>
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
<script>
    $(document).ready(function() {

        $("#search").keyup(function() {
                var value = $(this).val().toLowerCase();
                $("#body_field tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                })
            })  
    });
</script>
