<!DOCTYPE html>
<html lang="en">
    <?php
        $table_size = "325px"; //333 100 mm
        $logo_size = "80px";
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
                        font-size:19px;
                        text-shadow: 5px 5px 0px #f0e8db, 8px 8px 0px #6c5257;
                        margin-top : 12px;
                        margin-right:2px;
                        font-weight:bold;
                    }

                    .textA {
                        font-size:11px;
                        padding:1px;
                        margin:1px;
                        
                    }

            
        </style>
    </head>
    <body>

        <table id="table">
        <tr style="border-bottom:2px dotted #000;">
            <td><img style="margin-left:5px;" width="<?php echo $logo_size;?>" src="./assets/img/anteraja.jpg"/></td><td><div class="text" style="text-align:center;">Ririuri Store</div></td>
        </tr>
        
        <tr>
            <td colspan=2><div style="border:1px solid #000;font-weight:bold;font-size:12px;padding:1px;margin:1px;">No.Resi : 100000292929</div></td>
        </tr>
        
        <tr style="border-bottom:2px dotted #000;">
            <td colspan="2">
                <img style="margin-left:75px;" src="./assets/img/barcode.jpg"/>
            </td>
        </tr>
        <tr >
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Penerima : </div></td>
        </tr>
            <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;">Dede Irawan, 089630622362</div></td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;">Kota bekasi jawa barat bandar gebang rumahkontarakan panjaitan no 3 pangkalan 5 ,BANTAR GEBANG, KOTA BEKASI, JAWABARAT </div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;font-weight:bold;margin-left:1px;">Pengirim :</div></td>
        </tr>
        <tr>
            <td colspan=2><div style="font-size:12px;text-align:justify;margin-left:1px;"> Ririuri Store, 029828282 </div>
        </tr>
            <tr>
            <td colspan=2 style="border-bottom:2px solid #000;"><div style="font-size:12px;text-align:justify;padding:1px;">Kota bekasi jawa barat bandar gebang rumahkontarakan panjaitan no 3 pangkalan 5 ,BANTAR GEBANG, KOTA BEKASI, JAWABARAT </div></td>
        </tr>

        <tr>
            <td colspan=2><div style="font-size:12px;padding:1px;margin:1px;">No.Pesanan : 100000292929</div></td>
        </tr>
       
        </table>
        <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br>

        <table id="table2" class="textA">
            <tr>
                <td width="4%">No</td><td>Nama Produk</td><td width="10%">Qty</td>
            </tr>
            <tr>
                <td>1</td><td>Nama Produk</td><td>5</td>
            </tr>
            <tr>
                <td colspan=3>No.Pesanan : 900000818181</td>
            </tr>
        </table>
        <br><div style="margin-left:-42px;">------------------------------------------------------------------</div><br>
        <table id="table2">
            <tr>
                <td><div style="text-align:justify;font-size:12px;padding:2;">
                    Hallo kak Dede Irawan,
                    <br> Mohon bintang 5 dan ulasan terbaik nya ya. Semoga kaka sukses dan sehat selalu.
                </div>
                    <br>
                    <div style="background-color:black;color:#fff;text-align:center;font-size:15px;font-weight:bold;padding:2;">Ririuri Store</div>
                </td>
            </tr>
        </table>
    </body>
</html>
