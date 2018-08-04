$(document).ready(function () {

    $(function() {
        $('#usd').on('input', function() {
            match = (/(\d{0,100})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
            this.value = match[1] + match[2];
        });
    });

    $('#update').click(function () {
        $('#spinner').fadeIn();
        $.ajax({
            type: "POST",
            url: '/api/currency/ajaxUpdate',
            dataType: 'json',
            success: function (data) {

                // remove conversion table
                $("#conversion").remove();

                // add table and table headers
                var content = "<table class='minimalistBlack' id='conversion' border='1' cellpadding='10'>"
                content += "<thead><tr><th>Currency Name</th><th>Code</th><th>Rate</th></tr></thead>"

                // loop through data
                $.each(data, function(i, item) {
                    content += '<tr>';
                    content += '<td>' + data[i].name + '</td>';
                    content += '<td>' + data[i].code + '</td>';
                    content += '<td>' + data[i].conversion_rate + '</td>';
                    content += '</tr>';
                })

                content += "</table>"

                $('#conversion-table').append(content);
            }
        })
    });

    $('#convert').click(function () {

        var usd  = $("#usd").val();
        var cur  = $("#currency").val();
        var date = $("#date").val();

        if ( usd == null || usd == "", cur == null || cur == "", date == null || date == "") {
            alert("Please input all required fields.");
            return false;
        }

        $.ajax({
            type: "POST",
            url: '/api/currency/ajaxConvert',
            data: { usd : usd, cur : cur, date : date } ,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function (data) {
                //$('#converted').val(data);
            }
        })

    });

});