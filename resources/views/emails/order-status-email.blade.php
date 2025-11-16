<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            padding: 20px;
            background-color: #f8f8f8;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }
        .status {
            font-weight: bold;
            color: #2c3e50;
        }
        .pending { color: orange; }
        .on-the-way { color: blue; }
        .completed { color: green; }
        .cancelled { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h2>Hello, {{ $name }}</h2>
            <p>Your order status has been updated:</p>

            @php
                $statusMessage = '';
                $statusClass = '';
                switch($status) {
                    case 0:
                        $statusMessage = 'Pending';
                        $statusClass = 'pending';
                        break;
                    case 1:
                        $statusMessage = 'On the way';
                        $statusClass = 'on-the-way';
                        break;
                    case 2:
                        $statusMessage = 'Completed';
                        $statusClass = 'completed';
                        break;
                    case 3:
                        $statusMessage = 'Cancelled';
                        $statusClass = 'cancelled';
                        break;
                    default:
                        $statusMessage = 'Unknown';
                        $statusClass = '';
                        break;
                }
            @endphp

            <p class="status {{ $statusClass }}">{{ $statusMessage }}</p>

            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>

