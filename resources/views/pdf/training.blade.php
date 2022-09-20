<!DOCTYPE html>
<html>
<head>
    <title>Your Certificate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div style="width: 100%; max-width: 960px; margin: auto">
    <table width="100%">
        <tr style="border-bottom: 1px solid #000000">
            <td><h2>Certificate</h2></td>
            <td style="text-align: right"><h3># 12345</h3></td>
        </tr>
        <tr>
            <td style="padding-bottom: 16px;">
                <strong>{{ $recipient['first_name'] . ' ' . $recipient['last_name'] }}</strong><br>
                {{ $recipient['email'] }}
            </td>
            <td style="text-align: right; padding-bottom: 16px;">
                Completed the course <b>{{ date('Y-m-d', strtotime('now')) }}</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Successful completion of the course: {{ $course_name }}
            </td>
        </tr>
    </table>
</div>
</body>
</html>