<!DOCTYPE html>
<html>
<head>
    <title>{{ $deposit->account_number != null ? 'Withdraw' : 'Deposit' }} Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #2c3e50;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            padding: 5px 0;
        }
        code {
            background-color: #eee;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello, I'm {{ $user->name }}</h2>

        <p>I have {{ $deposit->account_number != null ? 'WITHDRAW' : 'DEPOSIT' }} request has been submitted successfully with the following details:</p>

        <ul>
            <li>Amount: <code>{{ number_format($deposit->amount, 2) }}</code></li>
            <li>Transaction ID: <code>{{ $deposit->transaction_id }}</code></li>
            <li>Account Number (for withdraw): <code>{{ $deposit->account_number }}</code></li>
            <li>Payment Method: {{ $deposit->payment_method }}</li>
            <li>Reference Number: {{ $deposit->reference_number }}</li>
            <li>Status: {{ $deposit->status }}</li>
        </ul>

        <p>Thank you for using our service!</p>
    </div>
</body>
</html>
