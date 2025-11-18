<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #eef2f7;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.08);
        }
        h2 {
            margin-top: 0;
            color: #2c3e50;
        }
        .status-box {
            font-size: 18px;
            font-weight: bold;
            padding: 12px 18px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0 20px 0;
        }
        .pending { background: #fff4e5; color: #d35400; }
        .on-the-way { background: #e8f1ff; color: #1e6ce0; }
        .completed { background: #e7f9ed; color: #1e8a3d; }
        .cancelled { background: #fdecea; color: #c0392b; }


        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="email-wrapper">
    <div class="email-container">

        <h2>Hello {{ $name }},</h2>

        <p>We wanted to let you know that the status of your order has just been updated.</p>

        @php
            $statusMessage = '';
            $statusClass = '';

            switch($status) {
                case 0:
                    $statusMessage = 'Pending';
                    $statusClass = 'pending';
                    break;
                case 1:
                    $statusMessage = 'On the Way';
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
                    break;
            }
        @endphp

        <div class="status-box {{ $statusClass }}">
            {{ $statusMessage }}
        </div>

        <p>
            If you have any questions regarding your order, feel free to reach out to our support team.
            We’re always here to help!
        </p>

        <div class="footer">
            Thank you for choosing us. We truly appreciate your trust!<br>
            &copy; {{ date('Y') }} Online Shopping Store
        </div>

    </div>
</div>

</body>
</html>
