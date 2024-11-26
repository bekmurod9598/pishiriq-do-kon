<x-layouts.main>
    <style>
        .custom-input-height {
            height: calc(1.5em + 0.75rem + 2px); /* button balandligiga mos */
            padding: 0.25rem 0.5rem; /* tugma bilan teng balandlik uchun */
            font-size: 0.875rem; /* matn hajmini tugmaga moslashtirish uchun */
        }
        h4.kirim-chiqim-header {
            font-family: Cambria, serif;
            font-weight: bold;
            color: #007bff; /* Primary rang */
            text-align: center;
            text-transform: uppercase;
        }
        .table thead th {
            background-color: skyblue;
            color: #005b8f; /* Tekst rangini skyblue foniga mos ravishda o'zgartiring */
        }
        .table tfoot td {
            background-color: skyblue;
            color: #005b8f; /* Tekst rangini skyblue foniga mos ravishda o'zgartiring */
        }
    </style>
    <div class="content-body">
        <div class="page-titles" style="justify-content:center !important">
            <ol class="breadcrumb">
                <li>
                    <h5 class="heading mb-0 text-primary text-center text-uppercase fw-bold">KASSA HISOBOTI OYNASI
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
                                    Kirim-chiqimlar hisoboti: 
                                    <span id="text_title"></span>
                                </h5>
                            </li>
                        </ol>
                        <li class="nav-item" role="presentation">
                            <div class="col d-flex align-items-center gap-2">
                                <div class="col-xl-3 col-sm-12 mb-3">
                                    <input type="date" class="form-control form-control-sm-2 custom-input-height" id="date1" name="date1" required />
                                    <span class="text-danger" id="date1_text"></span>
                                </div>
                                <div class="col-xl-3 col-sm-12 mb-3">
                                    <input type="date" class="form-control form-control-sm-2 custom-input-height" id="date2" name="date2" required />
                                    <span class="text-danger" id="date2_text"></span>
                                </div>
                                <div class="col-xl-3 col-sm-12 mb-3">
                                    <select class="form-control form-control-sm-2 custom-input-height" id="hisobot_turi" name="hisobot_turi">
                                        <option value="sum">Summa bo'yicha</option>
                                        <option value="sale">Savdo bo'yicha</option>
                                    </select>
                                    <span class="text-danger" id="hisobot_turi_text"></span>
                                </div>
                                <div class="col-xl-3 col-sm-12 mb-3">
                                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" id="reportBtn">
                                        <i class="fa fa-eye me-1"></i> Ko'rsatish
                                    </button>
                                </div>
                            </div>
                        </li>
                    </div>
                    <div class="card-body">
                        <div class="people-list dz-scroll">
                            <div class="table-responsive" id="hisobot_table">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.main>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function showReport() {
            $('#date1_text').text('');
            $('#date2_text').text('');
            $('#hisobot_turi_text').text('');
            
            // O'zgaruvchilarni to'g'ri nomlash
            let date1 = $('#date1').val();
            let date2 = $('#date2').val();
            let hisobot_turi = $('#hisobot_turi').val();
            
            if (date1 == '' || date1 == null)
                $('#date1_text').text('Hisobot boshlanish sanasi kiritilmadi');
            if (date2 == '' || date2 == null)
                $('#date2_text').text('Hisobot tugash sanasi kiritilmadi');
            if (hisobot_turi == '' || hisobot_turi == null)
                $('#hisobot_turi_text').text('Hisobot turi kiritilmadi');
            else {
                // date1 va date2 ni to'g'ri o'tkazish
                getHisobot(date1, date2);
            }
        }

        document.getElementById("reportBtn").addEventListener("click", showReport);
    });
    
    $( document ).ready(function() {
        // console.log(`<?php echo date("Y-m-d"); ?>`);
         getHisobot(`<?php echo date("Y-m-d"); ?>`, `<?php echo date("Y-m-d"); ?>`);
    });
    
    function getHisobot(date1, date2) {
        if(date1 == date2 && date1 == `<?php echo date("Y-m-d"); ?>`)
            $('#text_title').text("bugun");
        else
             $('#text_title').text(date1+" - "+date2);
             
        $.ajax({
            url: '/kunlik_hisobot',
            method: 'POST',
            data: {
                date1: date1,
                date2: date2,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // KIRIM jadvali
                let hisobotData = response.hisobot;
                let kirimTable = `
                    <h4 class="kirim-chiqim-header">Kirim qilib olingan savdolar</h4>
                    <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
                        <thead>
                            <tr>
                                <th>#</th><th>Naqd</th><th>Sana</th><th>Plastik</th><th>Nasiya</th>
                                <th>Jami kirim</th><th>Valyuta</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
                let totalNaqd = 0, totalPlastik = 0, totalNasiya = 0;
                hisobotData.forEach((item, index) => {
                    let jamiKirim = parseFloat(item.naqd) + parseFloat(item.plastik) + parseFloat(item.nasiya);
                    
                    totalNaqd += parseFloat(item.naqd);
                    totalPlastik += parseFloat(item.plastik);
                    totalNasiya += parseFloat(item.nasiya);
            
                    kirimTable += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${formatNumberWithSpaces(item.naqd)}</td>
                            <td>${item.sana}</td>
                            <td>${formatNumberWithSpaces(item.plastik)}</td>
                            <td>${formatNumberWithSpaces(item.nasiya)}</td>
                            <td>${formatNumberWithSpaces(jamiKirim.toFixed(2))}</td>
                            <td>${item.valyuta} (${item.valyuta_kurs})</td>
                        </tr>`;
                });
            
                kirimTable += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1">Jami:</td>
                                <td>${formatNumberWithSpaces(totalNaqd.toFixed(2)) || ''}</td>
                                <td></td>
                                <td>${formatNumberWithSpaces(totalPlastik.toFixed(2)) || ''}</td>
                                <td>${formatNumberWithSpaces(totalNasiya.toFixed(2)) || ''}</td>
                                <td>${formatNumberWithSpaces((totalNaqd + totalPlastik + totalNasiya).toFixed(2)) || ''}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>`;
            
                // CHIQIM jadvali
                let chiqimHisobotData = response.chiqimHisobot;
    let chiqimTable = `
        <h4 class="kirim-chiqim-header" style="margin-top: 20px;">Sotilgan maxsulotlar hisobi</h4>
        <table class="table table-bordered table-responsive-sm text-center" style="font-size: 14px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sana</th>
                    <th>Savdo summa</th>
                    <th>Xarajat summa</th>
                    <th>Kassadagi summa</th>
                    <th>Foyda</th>
                    <th>Tovar tannarx</th>
                </tr>
            </thead>
            <tbody>`;

    let totalSavdoSum = 0;
    let totalXarajatSumma = 0;
    let totalFoyda = 0;
    let totalTannarxSumma = 0;
    let totalKassaSumma = 0;

    chiqimHisobotData.forEach((item, index) => {
        let savdoSumma = parseFloat(item.savdo_summa) || 0;
        let xarajatSumma = parseFloat(item.xarajat_summa) || 0;
        let kassaSumma = parseFloat(item.foyda) || 0;
        let tannarxSumma = parseFloat(item.tannarx_summa) || 0;
        let foyda = kassaSumma - tannarxSumma;

        totalSavdoSum += savdoSumma;
        totalXarajatSumma += xarajatSumma;
        totalFoyda += foyda;
        totalTannarxSumma += tannarxSumma;
        totalKassaSumma += kassaSumma;

        chiqimTable += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.sana}</td>
                <td>${formatNumberWithSpaces(savdoSumma.toFixed(2))}</td>
                <td>${formatNumberWithSpaces(xarajatSumma.toFixed(2))}</td>
                <td>${formatNumberWithSpaces(kassaSumma.toFixed(2))}</td>
                <td>${formatNumberWithSpaces(foyda.toFixed(2))}</td>
                <td>${formatNumberWithSpaces(tannarxSumma.toFixed(2))}</td>
            </tr>`;
    });

    chiqimTable += `
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="font-weight: bold;">Jami:</td>
                    <td>${formatNumberWithSpaces(totalSavdoSum.toFixed(2))}</td>
                    <td>${formatNumberWithSpaces(totalXarajatSumma.toFixed(2))}</td>
                    <td>${formatNumberWithSpaces(totalKassaSumma.toFixed(2))}</td>
                    <td>${formatNumberWithSpaces(totalFoyda.toFixed(2))}</td>
                    <td>${formatNumberWithSpaces(totalTannarxSumma.toFixed(2))}</td>
                </tr>
            </tfoot>
        </table>`;

    document.getElementById('hisobot_table').innerHTML = chiqimTable;

                

                // Jadvalni o'zlashtirish
                $('#hisobot_table').html(kirimTable + chiqimTable);
            },

            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.date1) {
                        $('#date1_text').text(errors.date1[0]);
                    }
                    if (errors.date2) {
                        $('#date2_text').text(errors.date2[0]);
                    }
                } else {
                    console.log("Xatolik:", xhr);
                }
            }
        });
    }

    // Har uch xonada probel qo'shish va 0 qiymatni bo'sh ko'rsatish funksiyasi
    function formatNumberWithSpaces(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
</script>

