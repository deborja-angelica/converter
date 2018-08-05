$(document).ready(function () {

    $('#loading').hide();

    $(function() {
        $('#usd').on('input', function() {
            match = (/(\d{0,100})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
            this.value = match[1] + match[2];
        });
    });

    $('#update').click(function () {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: '/api/currency/ajaxUpdate',
            dataType: 'json',
            success: function (data) {
                $('#loading').hide();

                // remove conversion table
                $("#conversion").remove();

                // add table and table headers
                var content = "<table id='conversion' class='table table-bordered table-hover' cellpadding='10'>"
                content += "<thead class='thead-dark'><tr style='text-align: center;'><th>Currency Name</th><th>Code</th><th>Rate</th></tr></thead>"

                // loop through data
                $.each(data, function(i, item) {
                    content += "<tr>";
                    content += "<td>" + data[i].name + "</td>";
                    content += "<td>" + data[i].code + "</td>";
                    content += "<td style='text-align: right;'>" + addCommas(data[i].conversion_rate) + "</td>";
                    content += "</tr>";
                })

                content += "</table>"

                $('#conversion-table').append(content);
            }
        })
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#convert').click(function () {

        var usd  = $("#usd").val();
        var cur  = $("#currency").val();
        var date = $("#date").val();

        // validation
        if ( usd == null || usd == "", cur == null || cur == "", date == null || date == "") {
            alert("Please input all required fields.");
            return false;
        }

        // additional validation
        // check if number, string or date

        $('#loading').show();

        $.ajax({
            type: "POST",
            url: '/api/currency/ajaxConvert',
            data: { usd:usd, cur:cur, date:date },
            dataType: 'json',
            success: function (data) {
                $('#loading').hide();
                $('#converted').val(addCommas(data));
            }
        })

    });

    function addCommas(num) {
        num += '';
        x = num.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

});