/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.21-MariaDB : Database - db_ririuri
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_ririuri` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_ririuri`;

/*Table structure for table `pj_akses` */

DROP TABLE IF EXISTS `pj_akses`;

CREATE TABLE `pj_akses` (
  `id_akses` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(10) NOT NULL,
  `level_akses` varchar(15) NOT NULL,
  PRIMARY KEY (`id_akses`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `pj_akses` */

insert  into `pj_akses`(`id_akses`,`label`,`level_akses`) values (1,'admin','Administrator'),(2,'kasir','Staff Kasir'),(3,'inventory','Staff Inventory'),(4,'keuangan','Staff Keuangan');

/*Table structure for table `pj_barang` */

DROP TABLE IF EXISTS `pj_barang`;

CREATE TABLE `pj_barang` (
  `id_barang` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(40) NOT NULL,
  `nama_barang` varchar(60) NOT NULL,
  `total_stok` mediumint(1) unsigned NOT NULL,
  `modal` decimal(10,0) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `id_kategori_barang` mediumint(1) unsigned NOT NULL,
  `id_merk_barang` mediumint(1) unsigned DEFAULT NULL,
  `keterangan` text NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `pj_barang` */

insert  into `pj_barang`(`id_barang`,`kode_barang`,`nama_barang`,`total_stok`,`modal`,`harga`,`id_kategori_barang`,`id_merk_barang`,`keterangan`,`dihapus`) values (1,'0001','Thermometer Omron MC 245',53,38000,55000,10,19,'-','tidak'),(2,'0002','Thermometer Omron MC 246',60,45000,65000,10,19,'-','tidak'),(4,'0003','Thermometer Omron MC 343',60,96800,175000,10,19,'-','tidak'),(5,'0004','Masker Kain 3 Ply',200,3000,45000,10,19,'-','tidak'),(6,'0005','Masker Kain 2 Ply',200,2700,3500,10,19,'-','tidak'),(7,'0006','Madu Bali Honey 290 gr',1,127500,150000,11,19,'-','tidak'),(8,'0007','Madu Bali Honey 30 gr',4,12750,15000,11,19,'-','tidak'),(9,'0008','Madu Bali Honey 600 gr',1,212500,250000,11,19,'-','tidak'),(10,'0009','Tes Kehamilan Onemed',40,1600,5000,10,19,'-','tidak');

/*Table structure for table `pj_ci_sessions` */

DROP TABLE IF EXISTS `pj_ci_sessions`;

CREATE TABLE `pj_ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `pj_ci_sessions` */

insert  into `pj_ci_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('2c43d6be954158e5a41eb594b0fc5accfdde0606','::1',1601853289,'__ci_last_regenerate|i:1601853112;ap_id_user|s:1:\"1\";ap_password|s:40:\"d033e22ae348aeb5660fc2140aec35850c4da997\";ap_nama|s:10:\"Bang Admin\";ap_level|s:5:\"admin\";ap_level_caption|s:13:\"Administrator\";'),('f36da28ed3582f75572cb70a3c525159b04f0c39','::1',1601853859,'__ci_last_regenerate|i:1601853859;ap_id_user|s:1:\"1\";ap_password|s:40:\"d033e22ae348aeb5660fc2140aec35850c4da997\";ap_nama|s:10:\"Bang Admin\";ap_level|s:5:\"admin\";ap_level_caption|s:13:\"Administrator\";'),('dc1d3e44b928f4be1b60eae80c67cc4f821a714f','::1',1601853859,'__ci_last_regenerate|i:1601853859;ap_id_user|s:1:\"1\";ap_password|s:40:\"d033e22ae348aeb5660fc2140aec35850c4da997\";ap_nama|s:10:\"Bang Admin\";ap_level|s:5:\"admin\";ap_level_caption|s:13:\"Administrator\";'),('513e4edee0248508f234e366bc2cda8fc3bf65f2','::1',1601856137,'__ci_last_regenerate|i:1601854379;ap_id_user|s:1:\"1\";ap_password|s:40:\"d033e22ae348aeb5660fc2140aec35850c4da997\";ap_nama|s:10:\"Bang Admin\";ap_level|s:5:\"admin\";ap_level_caption|s:13:\"Administrator\";'),('7e6f9b8603758703b46cedef95add18dd36019b2','::1',1601856920,'__ci_last_regenerate|i:1601856259;ap_id_user|s:1:\"1\";ap_password|s:40:\"d033e22ae348aeb5660fc2140aec35850c4da997\";ap_nama|s:10:\"Bang Admin\";ap_level|s:5:\"admin\";ap_level_caption|s:13:\"Administrator\";');

/*Table structure for table `pj_kategori_barang` */

DROP TABLE IF EXISTS `pj_kategori_barang`;

CREATE TABLE `pj_kategori_barang` (
  `id_kategori_barang` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `kategori` varchar(40) NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_kategori_barang`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `pj_kategori_barang` */

insert  into `pj_kategori_barang`(`id_kategori_barang`,`kategori`,`dihapus`) values (12,'Lainya','tidak'),(11,'Suplemen','tidak'),(10,'Alat Kesehatan','tidak');

/*Table structure for table `pj_merk_barang` */

DROP TABLE IF EXISTS `pj_merk_barang`;

CREATE TABLE `pj_merk_barang` (
  `id_merk_barang` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `merk` varchar(40) NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_merk_barang`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `pj_merk_barang` */

insert  into `pj_merk_barang`(`id_merk_barang`,`merk`,`dihapus`) values (19,'Tidak Ada','tidak');

/*Table structure for table `pj_pelanggan` */

DROP TABLE IF EXISTS `pj_pelanggan`;

CREATE TABLE `pj_pelanggan` (
  `id_pelanggan` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  `alamat` text,
  `telp` varchar(40) DEFAULT NULL,
  `info_tambahan` text,
  `kode_unik` varchar(30) NOT NULL,
  `waktu_input` datetime NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `pj_pelanggan` */

insert  into `pj_pelanggan`(`id_pelanggan`,`nama`,`alamat`,`telp`,`info_tambahan`,`kode_unik`,`waktu_input`,`dihapus`) values (24,'Tokopedia','Tokopedia.co.id','021','Tokopedia','16017115721','2020-10-03 14:52:52','tidak'),(23,'Shopee','Shopee.co.id','021','Tidak Ada','16017115451','2020-10-03 14:52:25','tidak');

/*Table structure for table `pj_penjualan_detail` */

DROP TABLE IF EXISTS `pj_penjualan_detail`;

CREATE TABLE `pj_penjualan_detail` (
  `id_penjualan_d` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `id_penjualan_m` int(1) unsigned NOT NULL,
  `id_barang` int(1) NOT NULL,
  `jumlah_beli` smallint(1) unsigned NOT NULL,
  `harga_satuan` decimal(10,0) NOT NULL,
  `modal` decimal(10,0) NOT NULL,
  `laba` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id_penjualan_d`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

/*Data for the table `pj_penjualan_detail` */

insert  into `pj_penjualan_detail`(`id_penjualan_d`,`id_penjualan_m`,`id_barang`,`jumlah_beli`,`harga_satuan`,`modal`,`laba`,`total`) values (2,2,2,1,120000,0,0,120000),(3,2,4,1,35000,0,0,35000),(4,3,3,1,350000,0,0,350000),(5,4,2,1,120000,0,0,120000),(6,4,11,2,30000,0,0,60000),(7,4,4,2,35000,0,0,70000),(11,6,2,1,120000,0,0,120000),(10,6,1,1,400000,0,0,400000),(12,7,4,1,35000,0,0,35000),(13,8,3,1,350000,0,0,350000),(14,9,1,1,400000,0,0,400000),(15,9,2,1,120000,0,0,120000),(16,9,3,1,350000,0,0,350000),(17,9,4,1,35000,0,0,35000),(18,10,1,1,400000,0,0,400000),(19,10,2,1,120000,0,0,120000),(20,10,3,1,350000,0,0,350000),(21,11,1,1,400000,0,0,400000),(22,11,3,1,350000,0,0,350000),(23,12,3,2,350000,0,0,700000),(26,15,1,1,400000,0,0,400000),(27,16,20,2,600,500,200,1200),(28,17,20,2,600,500,200,1200),(29,17,21,1,600,500,100,600),(30,18,1,5,55000,38000,85000,275000),(31,19,1,1,55000,38000,17000,55000),(32,20,1,1,55000,38000,17000,55000);

/*Table structure for table `pj_penjualan_master` */

DROP TABLE IF EXISTS `pj_penjualan_master`;

CREATE TABLE `pj_penjualan_master` (
  `id_penjualan_m` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `nomor_nota` varchar(40) NOT NULL,
  `tanggal` datetime NOT NULL,
  `grand_total` decimal(10,0) NOT NULL,
  `bayar` decimal(10,0) NOT NULL,
  `biaya_admin` decimal(10,0) NOT NULL,
  `keterangan_lain` text,
  `id_pelanggan` mediumint(1) unsigned DEFAULT NULL,
  `id_user` mediumint(1) unsigned NOT NULL,
  PRIMARY KEY (`id_penjualan_m`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `pj_penjualan_master` */

insert  into `pj_penjualan_master`(`id_penjualan_m`,`nomor_nota`,`tanggal`,`grand_total`,`bayar`,`biaya_admin`,`keterangan_lain`,`id_pelanggan`,`id_user`) values (2,'57431A97D5DF8','2016-05-23 16:58:31',155000,160000,0,'',3,1),(3,'57431BDDAFA9D2','2016-05-23 17:03:57',350000,400000,0,'',3,2),(4,'57445D46655AB1','2016-05-24 15:55:18',250000,260000,0,'',NULL,1),(6,'576406086CB611','2016-06-17 16:15:36',520000,550000,0,'',NULL,1),(7,'57655546C37441','2016-06-18 16:05:58',35000,40000,0,'',NULL,1),(8,'57655552ABF781','2016-06-18 16:06:10',350000,400000,0,'',NULL,1),(9,'577A31BABCDC51','2016-07-04 11:51:54',905000,910000,0,'',NULL,1),(10,'577A3327991DC1','2016-07-04 11:57:59',870000,880000,0,'Dibayar Langsung',NULL,1),(11,'577A3793C67CB1','2016-07-04 12:16:51',750000,750000,0,'',NULL,1),(12,'57CA627F897FB1','2016-09-03 07:41:19',700000,800000,0,'',NULL,1),(15,'57CBD697806F61','2016-09-04 10:08:55',400000,500000,0,'',NULL,1),(16,'5F7828BFC55931','2020-10-03 09:31:11',1200,1500,0,'Tidak Ada',NULL,1),(17,'5F782975786141','2020-10-03 09:34:13',1800,2000,0,'Jika Ada',22,1),(18,'5F7A5BAB2BDFD1','2020-10-05 01:32:59',275000,800000,0,'-',NULL,1),(19,'5F7A5BC4E45081','2020-10-05 01:33:24',55000,900000,0,'-',NULL,1),(20,'5F7A5C39ACA681','2020-10-05 01:35:21',55000,700000,909900,'',NULL,1);

/*Table structure for table `pj_user` */

DROP TABLE IF EXISTS `pj_user`;

CREATE TABLE `pj_user` (
  `id_user` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_akses` tinyint(1) unsigned NOT NULL,
  `status` enum('Aktif','Non Aktif') NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `pj_user` */

insert  into `pj_user`(`id_user`,`username`,`password`,`nama`,`id_akses`,`status`,`dihapus`) values (1,'admin','d033e22ae348aeb5660fc2140aec35850c4da997','Bang Admin',1,'Aktif','tidak'),(2,'kasir','8691e4fc53b99da544ce86e22acba62d13352eff','Centini',2,'Aktif','tidak'),(3,'kasir2','08dfc5f04f9704943a423ea5732b98d3567cbd49','Kasir Dua',2,'Aktif','ya'),(4,'jaka','2ec22095503fe843326e7c19dd2ab98716b63e4d','Jaka Sembung',3,'Aktif','ya'),(5,'jaka','2ec22095503fe843326e7c19dd2ab98716b63e4d','Jaka Sembung',3,'Aktif','tidak'),(6,'joko','97c358728f7f947c9a279ba9be88308395c7cc3a','Joko Haji',4,'Aktif','tidak'),(7,'amir','1dd89e5367785ba89076cd264daac0464fdf0d7b','amir',3,'Aktif','ya');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
