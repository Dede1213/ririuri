<!DOCTYPE html>
<html lang="en">
    <?php

    
        $table_size = "340px"; //333 100 mm
        $logo_size = "80px";
        

        $page_heigt = 145 * $jumlah_cetak;
        $page_heigt_fix = $page_heigt.'mm';
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>

            #table_utama {
               
                /* margin-left:-24px;
                margin-top:-14px; */
                

                /* border : 1px solid #000; */
                /* padding:10px; */
                width:100%;
            }
            #table {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: <?php echo $table_size;?>;
                border : 2px solid #000;
                margin-left:34px;
                /* height:50%; */
                margin-top:5px;
            }

            #table2 {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 344px;
                padding:2px;
                border : 1px solid #000;
                margin-left:33px;
                /* height:50%; */
            }
            #table2 tr td{;
                border : 1px solid #000;
            }
            
            .text {
                        font-family: 'Arial', cursive;
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

                    @page {
                        size: A4;
                        margin: 0;
                    }
                    @media print {
                        .page {
                            margin: 0;
                            border: initial;
                            border-radius: initial;
                            width: initial;
                            min-height: initial;
                            box-shadow: initial;
                            background: initial;
                            page-break-after: always;
                        }
                    }

                    
        </style>
    </head>
    <body>
       
    <?php
    $nox = 1;
    foreach($header as $row){  
        $font_size = '10px';
        if(strlen($row['nama_toko']) > 13){
            $font_size = '10px';
        }else{
            $font_size = '14px';
        }

        if($nox % 4 == 1){
            echo "<table id='table_utama'>";
        }

        if($nox % 2 != 0){
            echo "<tr>";
        }
    ?>
    <td>
    <?php if($row['opsi'] == 'Semua' || $row['opsi'] == 'Resi'){ ?>
        
        <table id="table">
        <tr style="border-bottom:2px dotted #000;">
            <td>
            <img style="margin-left:5px;" width="<?php echo $logo_size;?>" src="./assets/img/<?php echo $row['ekspedisi'];?>"/>
            </td><td><div class="text" style="font-size:<?php echo $font_size ?>;"><?php echo $row['nama_toko'];?></div></td>
        </tr>
        
        <tr>
            <td colspan=2><div style="border:1px solid #000;font-weight:bold;font-size:12px;padding:1px;margin:1px;">No.Resi : <?php echo $row['no_resi'];?></div></td>
        </tr>
        
        <tr style="border-bottom:2px dotted #000;">
            <td colspan="2">
            <img style="margin-left:50px;width:45%;"  src="data:image/png;base64,<?php echo $row['barcode'];?>" alt='Red dot' />

                <!-- <img style="position:center;width:90%;" src="./assets/img/barcode.jpg"/> -->
            </td>
        </tr>
        <tr >
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Penerima : </div></td>
        </tr>
            <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;"><?php echo $row['nama_penerima'];?>, <?php echo $row['no_hp'];?></div></td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;"><?php echo $row['alamat_penerima'];?></div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Pengirim :</div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;"> <?php echo $row['nama_toko'];?>, <?php echo $row['nomor_toko'];?> </div>
        </tr>
            <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;"><?php echo $row['alamat_toko'];?> </div></td>
        </tr>

        <tr>
            <td colspan=2><div style="font-size:12px;padding:1px;margin:1px;">No.Pesanan : <?php echo $row['no_nota'];?></div></td>
        </tr>
       
        </table>
        <!-- <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br> -->


        <table id="table2" class="textA">
            <tr>
                <td width="4%">No</td><td>Nama Produk</td><td width="10%">Qty</td>
                </tr>
            
            <?php
                $no = 1;
                foreach($row['data_barang'] as $kd)
                {
                    if( ! empty($kd))
                    {
                        echo "<tr><td>".$no."</td><td>".$kd['nama_barang']."</td><td>".$kd['jumlah_beli']."</td></tr>";
                        $no++;
                    }
                }
                
            ?>

          
            
            <tr>
                <td colspan=3>No.Pesanan : <?php echo $row['no_nota'];?></td>
            </tr>
        </table>
    <?php } ?>
        <?php if($row['opsi'] == 'Semua' || $row['opsi'] == 'Ucapan'){ ?>
            <?php if($row['opsi'] == 'Ucapan'){ $margin_top_ucapan = "-45px"; }else{ $margin_top_ucapan = "0px"; }?>
            <?php if($row['opsi'] == 'Semua'){ ?>
                <!-- <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br> -->

            <?php } ?>    
            <table id="table2" style="margin-top:<?php echo $margin_top_ucapan;?>">
                <tr>
                    <td><div style="text-align:justify;font-size:12px;padding:2;">
                        Hallo Kak <?php echo $row['nama_penerima'];?>,
                        <br> <?php echo $row['kartu_ucapan'];?>
                    </div>
                        <br>
                        <div style="background-color:black;color:#fff;text-align:center;font-size:15px;font-weight:bold;padding:2;"><?php echo $row['nama_toko'];?></div>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <?php if($row['opsi'] == 'Ucapan'){ ?>
            <table id="table2">
                <tr>
                    <td colspan=3><div style="text-align:justify;font-size:11px;padding:2;">No.Pesanan : <?php echo $row['no_nota'];?></div></td>
                </tr>
            </table>
            
        <?php }?>  
        </td>
    <?php
    
    if($nox % 2 == 0){
        echo "</tr><tr><td colspan=2><hr style='border:1px dotted #000;'></td></tr>";
    }
    if($nox % 4 == 0){
        echo "</table>";
    }
    // if($nox % 2 == 0 && $nox != $jumlah_cetak){
    //     echo "<div style = 'display:block; clear:both; page-break-after:always;'></div> ";
    // }
    $nox++;
        }
    ?>
    
    
    </body>
</html>
