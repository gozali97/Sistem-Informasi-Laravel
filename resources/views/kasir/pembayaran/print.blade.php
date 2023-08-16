<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

</head>
<style>

    table, th, td {

    }


    div. a {
        writing-mode: vertical-rl;
        transform: rotate(270deg);
        transform: scale(-1);
        font-size: 100%;
        width: 7px;
        display: block;
        text-align: center;
    }

    .table_title_left div {
        writing-mode: vertical-rl;
        white-space: nowrap;
        transform: scale(-1);
        padding: 20px;
        text-align: center;
    }

</style>

<body>
<table>
    <thead>
    <tr>
        <td><h5>{{$data->pasien_nama }}</h5></th>
    </tr>
    <tr>
        <td style="text-align: center">
            <?php
            $barcode = new \Milon\Barcode\DNS2D;
            echo $barcode->getBarcodeHTML($data->lab_no_reg . '.' . $data->pasien_nama, 'QRCODE', 8, 8);
            ?>
            {{$data->pasien_nama }}
            - {{ $data->pasien_gender.' / '. $data->pasien_umur_thn.' '.$data->pasien_umur_bln}}
            - {{$data->pasien_alamat}}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center">{{ strval($data->lab_no_reg)}} {{''. $data->pasien_nama }} <br></td>
    </tr>
    </thead>
    <tbody>
    @foreach($listbarcode as $dt)
        <tr>
            <td><h5>{{$data->pasien_nama }}</h5></th>
        </tr>
        <tr>
                <?php
                $lab_no_reg = str_replace(['-', ' '], '', $data->lab_no_reg);
                $sign = $dt->sign;
                $barcode_data1 = $lab_no_reg . $sign;
                $barcode = new \Milon\Barcode\DNS2D;
                echo $barcode->getBarcodeHTML($barcode_data1, 'QRCODE', 10, 10);
                ?>

        </tr>
        <tr>
            <td colspan="2" style="text-align: center">{{ strval($data->lab_no_reg.''.$dt->sign)}} {{ $dt->inst }}
                <br></td>
        </tr>
    @endforeach
    </tbody>
</table>


</body>


<script>
    window.onload = function () {
        window.print();
    };
</script>
