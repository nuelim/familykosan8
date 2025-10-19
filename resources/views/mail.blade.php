<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notifikasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            /*height: 100vh;*/
        }

        .container {
            box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';background-color:#edf2f7;border-bottom:20px solid #edf2f7;border-top:20px solid #edf2f7;padding:0;margin-left: 40px;margin: auto;margin-right: 40px;
        }

        .content {
            box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';width:600px;padding:15px;background: white;margin: auto;
        }


        h2 {
            color: #3498db;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .email {
            font-weight: bold;
            color: #e74c3c;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .icon {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <!-- <center><img src="https://danteproject.site/public/thumbnail/logo-true.jpg" width="150" style="border-radius: 50%;"></center> -->
            <b>PERHATIAN JANGAN MEMBALAS E-MAIL INI</b>
            <hr>
            <br>
            @if($details['status'] == 'Penyewaan Baru')
            Halo {{$details['nama_penghuni']}},
            <p>
                Selamat datang! Terima kasih telah memilih kami sebagai tempat tinggal Anda. Kami sangat senang menyambut Anda.
            </p>
            <p>
                <b>Detail Penyewaan Anda:</b>
                <br>
                Nama Penghuni: {{$details['nama_penghuni']}}<br>
                Nomor Kamar: {{$details['nomor_kamar']}}<br>
                Tanggal Masuk: {{tanggal_indonesia($details['tanggal_penyewaan'])}}<br>
                Harga Sewa: {{number_format($details['harga_kamar'],0,",",".")}} per bulan
            </p>
            <p>
                <b>Apa yang Harus Anda Ketahui:</b>
                <br>
                Aturan dan Kebijakan: Untuk memastikan kenyamanan semua penghuni, mohon untuk mematuhi aturan dan kebijakan yang telah ditetapkan (sesuai lampiran yang telah diberikan oleh pengelola/pengurus kos).
            </p>
            <p>
                Salam Hangat
            </p>
            @elseif($details['status'] == 'Lupa Password')
            <h2>Lupa Password</h2>
            <p>Hi, {{$details['name']}}</p>
            <p>
                Kami telah menerima permintaan untuk mereset kata sandi akun Anda. Untuk melanjutkan proses reset, password Anda telah dibuat otomatis dibawah ini: <br><br>
                <b>Password: {{$details['password']}}</b>
            </p>
            <p>
                Jika Anda tidak merasa melakukan permintaan ini, mohon abaikan email ini. Keamanan akun Anda penting bagi kami. Jangan berikan informasi akun Anda kepada pihak lain.
            </p>
            @elseif($details['status'] == 'Reminder')
            {!! nl2br($details['isi_reminder']) !!}
            @else
            <p>
                Halo {{$details['nama_operator']}},
                <br>
                Kami ingin menginformasikan bahwa telah masuk pembayaran kos dengan rincian sebagai berikut:
                <ul style="font-weight: bold;">
                    <li>Kode Penyewaan: {{$details['kode_penyewaan']}}</li>
                    <li>Nama Penghuni: {{$details['nama_penghuni']}}</li>
                    <li>Nomor Kamar: {{$details['nomor_kamar']}}</li>
                    <li>Tanggal Pembayaran: {{tanggal_indonesia($details['tanggal_bayar'])}}</li>
                </ul>
            </p>
            @endif
        </div>
    </div>
</body>
</html>