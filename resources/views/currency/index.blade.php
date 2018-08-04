<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/css.css') }}">
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/js.js') }}"></script>
<body>
    <div class="col-md-6 well">
        <div class="form-inline">
            <label>USD <span style="color: #ff0000;">*</span> :</label>
            <input id="usd" class="enjoy-css form-control" type="text" name="input" placeholder="0.00" />
            &nbsp;&nbsp;&nbsp;
            <label>Convert To <span style="color: #ff0000;">*</span> :</label>
            <select id="currency" name="currency" class="enjoy-css form-control">
                <option value="">---</option>

                @foreach ($currencies as $currency)
                    <option value={{ $currency->code }}>{{ $currency->name }}</option>
                @endforeach

            </select>
            &nbsp;&nbsp;&nbsp;
            <label>Date <span style="color: #ff0000;">*</span> :</label>
            <input id="date" class="enjoy-css form-control" type="date" name="date" onkeydown="return false"/>
            <br /><br />
            <label>Conversion:</label>
            <input id="converted" class="enjoy-css form-control" type="text" name="converted" placeholder="0.00" />
            <br /><br />
            <button id="convert" class="myButton form-control">Convert</button>
        </div>
    </div>
    <br />
    <hr style="border-top:1px dotted #000;"/>
    <br />
    <button id="update" class="myButton form-control">Update Rates</button>
    <br /><br />
    @if (count($conversions) > 1)
    <div id="conversion-table">
        <table class="minimalistBlack" id="conversion" border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Currency Name</th>
                    <th>Code</th>
                    <th>Rate</th>
                </tr>
            </thead>
        @foreach ($conversions as $conversion)
            <tr>
                <td>{{ $conversion->name }}</td>
                <td>{{ $conversion->code }}</td>
                <td>{{ $conversion->conversion_rate }}</td>
            <tr>
        @endforeach
        </table>
    </div>
    @endif
</body>
</html>