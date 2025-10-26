<!DOCTYPE html>
<html lang="en">
<head>
<!--
Name: Wo Jia Qian
Student Id: 2314023
-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px; /* Adjust this to fit your logo size */
            height: auto;
        }
        .header-text {
            text-align: right;
        }
        .header-text h1 {
            margin: 0;
            font-size: 24px;
        }
        .header-text p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/your-logo.png') }}" alt="Company Logo">
        <div class="header-text">
            <h1>Movie Booking System</h1>
            <p>Generated Report - {{ \Carbon\Carbon::now()->format('F j, Y, g:i A') }}</p>
        </div>
    </div>

    <h2 class="report-title">Bookings Report</h2>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User</th>
                <th>Movie</th>
                <th>Showtime</th>
                <th>Total Price</th>
                <th>Booking Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->schedule->movie->title ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->schedule->show_time)->format('M j, Y - g:i A') }}</td>
                    <td>RM{{ number_format($booking->bookingSeats->sum('price'), 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('M j, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total Revenue for Report</td>
                <td>RM{{ number_format($bookings->sum(function($b) { return $b->bookingSeats->sum('price'); }), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Movie Booking System. All rights reserved.</p>
    </div>

</body>
</html>