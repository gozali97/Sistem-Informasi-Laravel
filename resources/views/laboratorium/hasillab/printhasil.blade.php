<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Hasil Pemeriksaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            margin-top: -7%;
            padding: 0px 20px;
        }

        .card {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
            /* background-color: #f3efef; */
            box-shadow: 2px 4px 6px -1px rgba(0, 0, 0, 0.1), 2px 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 10px;
            border: 1px solid black;
        }

        h1 {
            text-align: center;
            color: #0069d9;
        }

        h3 {
            text-align: center;
            margin-bottom: 30px;
            margin-top: -3%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            text-align: left;
            font-size: 10px;
        }

        th {
            font-weight: bold;
        }

        .btn {
            flex-basis: 50%;
            padding: 3px 2px;
            border: 1px solid black;
            color: black;
            border-radius: 2%;
            font-size: 10px;
            text-align: center;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0069d9;
        }

        .row::after {
            content: "";
            display: table;
            clear: both;

        }

        .column {
            float: left;
            width: 45%;
            padding: 15px;
        }

        .table2 {
            border-collapse: collapse;
            width: 100%;
        }

        .border {
            border: 1px solid rgb(30, 29, 29);
            border-radius: 2%;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ public_path('assets/img/genial.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
            opacity: 0.1;
            z-index: -1;
        }
    </style>
</head>
<body>
<div class="container">
    <table style="margin-top: 5%;">
        <tr>
            <td>Penanggung Jawab Laboraturium : dr. Harjo Mulyono, SpPK(K)</td>
        </tr>
    </table>


    <table style="margin-top: 2%;margin-bottom: 4%">
        @foreach ($tlab as $lab)
                <?php if ($lab->pasien_gender == 'L') {
                $sex = 'Laki - Laki';
            } else {
                $sex = 'Perempuan';
            } ; ?>
            <tr>
                <td>No. ID / Reg</td>
                <td width="30%">: {{ $lab->pasien_nomor_rm .' / '.$lab->lab_nomor }}</td>
                <td width="20%">Telp</td>
                <td width="30%">: {{ $lab->pasien_telp }}</td>
            </tr>
            <tr>
                <td>Nama Pasien</td>
                <td>: {{ $lab->pasien_nama }}</td>
                <td>Umur / Sex</td>
                <td>
                    : {{ $lab->pasien_umur_thn.' Tahun '.$lab->pasien_umur_bln.' Bulan '.$lab->pasien_umur_hr .' Hari / '.$sex}}</td>
            </tr>
            <tr>
                <td>Pasien Alamat</td>
                <td>: {{ $lab->pasien_alamat}}</td>
                <td>Tgl. Registrasi</td>
                <td>: {{ $lab->lab_tanggal }}</td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td>: dr. Ferni Pangkey, MARS</td>
                <td>Alamat dokter</td>
                <td>: 'Hi-Lab Diagnostic Center Yogyakarta'</td>
            </tr>
        @endforeach
    </table>

    <table style="border: 1px solid black">
        <thead>
        <tr>
            <th style="width: 30%;text-align: center">PEMERIKSAAN</th>
            <th style="width: 30%;text-align: center">HASIL</th>
            <th style="width: 60%;text-align: center">NILAI RUJUKAN</th>
            <th style="width: 100%;text-align: center">SATUAN</th>
        </tr>
        </thead>
        <tbody>
        @php
            $varnama1 = '';
            $varnama2 = '';
        @endphp
        @foreach ($tlabhasil as $hasil)
            @php
                $varnama1 = $hasil->nama;
            @endphp
            @if ($varnama1 != $varnama2)
                <td colspan="4"
                    style="font-weight: bold; margin-bottom: 2%;margin-top: 2%;border: 1px solid black">{{ $varnama1 }}</td>
            @endif
            <tr>
                <td style="width: 30%">{{ $hasil->lab_nama }}</td>
                <td style="width: 30%;text-align: center">{{ $hasil->lab_hasil }}</td>
                <td style="width: 60%;text-align: center">{{ $hasil->ref_value }}</td>
                <td style="width: 100%;text-align: center">{{ $hasil->lab_satuan }}</td>
            </tr>
            @php
                $varnama2 = $varnama1;
            @endphp
        @endforeach
        </tbody>

    </table>

    <div style="float: right!important">
        <table>
            <tr>
                <td>
                    <center>Validasi Hasil,
                </td>
            </tr>
            <tr>
                <td><br><br><br><br></td>
            </tr>
            <tr>
                <td>
                    <h5>dr. Harjo Mulyono, SpPK(K)</h5>
                </td>
            </tr>
        </table>
    </div>

</div>
</form>


<!-- JQuery 1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script>
    $(document).ready(function () {
        refreshData();
    });

    function refreshData() {
        window.print();
        setTimeout(function () {
            window.history.go(-1)
        }, 1000);
    }

</script>
</body>
</html>
