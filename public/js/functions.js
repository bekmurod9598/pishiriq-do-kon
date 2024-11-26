// public/js/sales.js
function formatNumberWithSpaces(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function createSalesTable(data, tableField, key) {
    // Yuklanmoqda xabarini ko‘rsatish
    $(tableField).html('Yuklanmoqda...');
    
    var totalSoni = 0;
    var totalSumma = 0;
    var totalChegirma = 0;
    var totalChiqimNarx = 0;

    // Jadvalning boshlang'ich HTML qismi
    var tableHTML = '<table class="table table-bordered" id="salesTable" style="text-align:center; font-size:14px;">';
    tableHTML += '<thead><tr class="table-primary">';
    tableHTML += '<th>#</th>';
    tableHTML += '<th>Tovar</th>';
    tableHTML += '<th>Soni</th>';
    tableHTML += '<th>Chiqim narxi</th>';
    tableHTML += '<th>Chegirma</th>';
    tableHTML += '<th>Jami</th>';
    tableHTML += '<th></th>';
    tableHTML += '</tr></thead><tbody>';

    // Ma'lumotlar asosida jadvalni shakllantirish
    if (data.sale_tovar || data.sale_tovar.length > 0) {
        let i = 0;
        $.each(data.sale_tovar, function (index, item) {
            i++;
            let button = (key && key == 'readonly') ? '-' : '<button onclick="deleteItem(\'' + item.id + '\')" class="btn btn-danger btn-icon-xxs" title="O\'chirish"><i class="fa-solid fa-trash"></i></button>';
            tableHTML += '<tr>';
            tableHTML += '<td style="white-space: wrap; width: 5%;">' + i + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 45%;">' + item.tname + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 10%;">' + item.soni + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 10%;">' + formatNumberWithSpaces(item.chiqim_narx) + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 10%;">' + formatNumberWithSpaces(item.chegirma) + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 10%;">' + formatNumberWithSpaces(item.summa) + '</td>';
            tableHTML += '<td style="white-space: wrap; width: 10%;">' + button + '</td>';
            tableHTML += '</tr>';

            // Umumiy soni va summa qiymatlarini hisoblash
            totalSoni += parseFloat(item.soni);
            totalChiqimNarx += parseFloat(item.chiqim_narx);
            totalChegirma += parseFloat(item.chegirma);
            totalSumma += parseFloat(item.summa);
        });
    } else {
        // Agar ma'lumot bo'lmasa
        tableHTML += '<tr><td colspan="7">Mahsulot mavjud emas!</td></tr>';
    }

    // Jadvalning footer qismi
    tableHTML += '</tbody><tfoot>';
    tableHTML += '<tr class="table-warning">';
    tableHTML += '<th colspan="2">Jami</th>';
    tableHTML += '<th>' + formatNumberWithSpaces(totalSoni) + '</th>';
    tableHTML += '<th>' + formatNumberWithSpaces(totalChiqimNarx.toFixed(2)) + '</th>';
    tableHTML += '<th>' + formatNumberWithSpaces(totalChegirma.toFixed(2)) + '</th>';
    tableHTML += '<th>' + formatNumberWithSpaces(totalSumma.toFixed(2)) + '</th>';
    tableHTML += '<th></th></tr>';
    tableHTML += '</tfoot></table>';

    // Natijani ko'rsatilgan elementga joylashtirish
    $(tableField).html(tableHTML);
}


