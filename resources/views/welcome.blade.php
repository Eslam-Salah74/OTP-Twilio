<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إرسال والتحقق من OTP</title>
</head>
<body>
    <h2>إرسال OTP</h2>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <form method="POST" action="{{ route('send.otp') }}">
        @csrf
        <label for="phone">رقم الهاتف:</label>
        <input type="text" id="phone" name="phone" required>
        <button type="submit">إرسال OTP</button>
    </form>

    <h2>التحقق من OTP</h2>
    <form method="POST" action="{{ route('verify.otp') }}">
        @csrf
        <label for="phone">رقم الهاتف:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="otp">OTP:</label>
        <input type="text" id="otp" name="otp" required>

        <button type="submit">تحقق من OTP</button>
    </form>
</body>
</html>
