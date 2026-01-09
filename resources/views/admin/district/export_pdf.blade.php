<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>{{ $title ?? 'Export' }}</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }

            th {
                background: #f2f2f2;
            }
        </style>
    </head>

    <body>
        <h3>{{ $title }}</h3>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>District</th>
                    <th>City</th>
                    <th>Pincode</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $r['country'] ?? '' }}</td>
                        <td>{{ $r['state'] ?? '' }}</td>
                        <td>{{ $r['district'] ?? '' }}</td>
                        <td>{{ $r['city'] ?? '' }}</td>
                        <td>{{ $r['pincode'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</html>
