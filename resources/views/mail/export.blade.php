<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>Thông báo lương</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
            color: black
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .note {
            font-size: 14px;
            font-style: italic;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thông báo về lương</h1>
        <p>Chào bạn {{ $user['name'] }},</p>
        <p>Bộ phận lương xin gửi bạn thông tin về lương tháng {{ \Carbon\Carbon::now()->subMonth()->format('m/Y') }}.
        </p>
        <p>Bạn vui lòng kiểm tra thông tin trước 15h00 ngày 09.{{ \Carbon\Carbon::now()->format('m.Y') }}.</p>
        <p>Sau thời gian 15h00 ngày 09.{{ \Carbon\Carbon::now()->format('m.Y') }}, nếu không có phản hồi từ phía bạn, bộ
            phận kế toán sẽ chính thức thanh toán lương cho bạn dựa trên con số được cung cấp trong bảng chi tiết.</p>
        <p class="note">Lưu ý: Đây là email tự động, vui lòng không trả lời trực tiếp email này. Mọi thắc mắc xin liên
            hệ ketoan@nal.vn.</p>
        <div class="footer">
            <p>Trân trọng,</p>
            <p>Bộ phận Lương</p>
        </div>
    </div>
</body>

</html>
