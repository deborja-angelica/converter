<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/css.css') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/js.js') }}"></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <body>
        <div id="loading"></div>
        <h3 style="padding-top: 20px; margin-left: 35px;">Foreign Exchange Rates for U.S. Dollar (USD)</h3>
        <div class="row" style="padding-top: 20px; margin-left: 20px; padding-bottom: 20px;">
            <div id="conversion-table" class="col-md-auto">
                @if (count($conversions) > 1)
                <table id="conversion" class="table table-bordered table-hover" cellpadding="10">
                    <thead class="thead-dark">
                        <tr style="text-align: center;">
                            <th>Currency Name</th>
                            <th>Code</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                @foreach ($conversions as $conversion)
                    <tr>
                        <td>{{ $conversion->name }}</td>
                        <td>{{ $conversion->code }}</td>
                        <td style="text-align: right;">{{ number_format($conversion->conversion_rate, 8) }}</td>
                    <tr>
                @endforeach
                </table>
                @endif
            </div>

            <div class="col-md-3">
                <label style="font-weight: bold;">USD <span style="color: #ff0000;">*</span> :</label>
                <input id="usd" class="form-control" type="text" name="input" placeholder="0.00"/>
                <br />
                <label style="font-weight: bold;">Convert To <span style="color: #ff0000;">*</span> :</label>
                <select id="currency" name="currency" class="form-control">
                    <option value="USD">US Dollar</option>

                    @foreach ($currencies as $currency)
                        <option value={{ $currency->code }}>{{ $currency->name }}</option>
                    @endforeach

                </select>
                <br />
                <label style="font-weight: bold;">Date <span style="color: #ff0000;">*</span> :</label>
                <input id="date" class="form-control" type="date" name="date" onkeydown="return false"/>
                <br />
                <label style="font-weight: bold;">Conversion:</label>
                <input id="converted" class="form-control" type="text" name="converted" placeholder="0.00" />
                <br />

                <div class="row">
                    <div class="col-sm-6">
                        <button id="convert" class="btn btn-dark form-control">Convert</button>
                    </div>
                    <div class="col-sm-6">
                        <button id="update" class="btn btn-dark form-control">Update Rates</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>