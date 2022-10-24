<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" href="{{ asset('css/bootstrap-5.2.2/bootstrap-grid.min.css') }}">-->
    <style type="text/css">
        body {font-family: "Open sans", "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;}
        .left {width: 100%;}
        .bold {font-weight: bold;}
        .w-full {width: 100%;}
        h2 {margin: 30px 0;}
    </style>
</head>
<body>
    <table class="w-full">
        <tr>
            <td class="left">
                <div><span class="bold">Company:</span> {!! $data['company_name'] !!}</div>
                <div><span class="bold">Report generated on:</span> {!! $data['report_date'] !!}</div>
                <div><br></div>
                <div><span class="bold">Start date results:</span> {!! $data['start_date'] !!}</div>
                <div><span class="bold">End date results:</span> {!! $data['end_date'] !!}</div>
            </td>
            <td><img src="{!! $data['company_logo'] !!}"></td>
        </tr>
    </table>

    <h2>Phishing Results</h2>

    <section class="chart">
        <img src="{!! $data['image'] !!}">
    </section>

</body>
</html>
