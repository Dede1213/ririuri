<!DOCTYPE html>
<html lang="en">
    <?php
        $table_size = "325px"; //333 100 mm
        $logo_size = "80px";

        if(strlen($nama_toko) > 13){
            $font_size = '10px';
        }else{
            $font_size = '14px';
        }
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #table {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: <?php echo $table_size;?>;
                border : 2px solid #000;
                margin-left:-34px;
                margin-top:-45px;
            }

            #table2 {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: <?php echo $table_size;?>;
                padding:2px;
                border : 1px solid #000;
                margin-left:-34px;
            }
            #table2 tr td{;
                border : 1px solid #000;
            }
            
            .text {
                        font-family: 'Arial', cursive;
                        font-size:<?php echo $font_size;?>;
                        font-weight:bold;
                        margin-top : -5;
                        text-align:center;
                        /* margin-top:10px; */
                        vertical-align:middle;
                    }

                    .textA {
                        font-size:11px;
                        padding:1px;
                        margin:1px;
                        
                    }

            
        </style>
    </head>
    <body>
    <?php if($opsi == 'Semua' || $opsi == 'Resi'){ ?>
        <table id="table">
        <tr style="border-bottom:2px dotted #000;">
            <td><img style="margin-left:5px;" width="<?php echo $logo_size;?>" src="./assets/img/<?php echo $ekspedisi;?>"/></td><td><div class="text"><?php echo $nama_toko;?></div></td>
        </tr>
        
        <tr>
            <td colspan=2><div style="border:1px solid #000;font-weight:bold;font-size:12px;padding:1px;margin:1px;">No.Resi : <?php echo $no_resi;?></div></td>
        </tr>
        
        <tr style="border-bottom:2px dotted #000;">
            <td colspan="2">
                <img style="margin-left:50px;width:60%;" src="./assets/img/barcode.jpg"/>
            </td>
        </tr>
        <tr >
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Penerima : </div></td>
        </tr>
            <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;"><?php echo $nama_penerima;?>, <?php echo $no_hp;?></div></td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;"><?php echo $alamat_penerima;?> </div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Pengirim :</div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;"> <?php echo $nama_toko;?>, <?php echo $nomor_toko;?> </div>
        </tr>
            <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;"><?php echo $alamat_toko;?> </div></td>
        </tr>

        <tr>
            <td colspan=2><div style="font-size:12px;padding:1px;margin:1px;">No.Pesanan : <?php echo $no_nota;?></div></td>
        </tr>
       
        </table>
        <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br>

        <table id="table2" class="textA">
            <tr>
                <td width="4%">No</td><td>Nama Produk</td><td width="10%">Qty</td>
            </tr>
            <?php
            echo $data_barang;
            ?>
            <tr>
                <td colspan=3>No.Pesanan : <?php echo $no_nota;?></td>
            </tr>
        </table>
        <?php } ?>
        <?php if($opsi == 'Semua' || $opsi == 'Ucapan'){ ?>
            <?php if($opsi == 'Ucapan'){ $margin_top_ucapan = "-45px"; }else{ $margin_top_ucapan = "0px"; }?>
            <?php if($opsi == 'Semua'){ ?>
                <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br>
            <?php } ?>   
        <table id="table2" style="margin-top:<?php echo $margin_top_ucapan;?>">
            <tr>
                <td><div style="text-align:justify;font-size:12px;padding:2;">
                    Hallo Kak <?php echo $nama_penerima;?>,
                    <br> <?php echo $kartu_ucapan;?>
                </div>
                    <br>
                    <div style="background-color:black;color:#fff;text-align:center;font-size:15px;font-weight:bold;padding:2;"><?php echo $nama_toko;?></div>
                </td>
            </tr>
        </table>
        <?php } ?>
        <?php if($opsi == 'Ucapan'){ ?>
            <table id="table2">
                <tr>
                    <td colspan=3><div style="text-align:justify;font-size:11px;padding:2;">No.Pesanan : <?php echo $no_nota;?></div></td>
                </tr>
            </table>
        <?php }?>
    </body>
</html>
