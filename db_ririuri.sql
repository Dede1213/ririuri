/*
SQLyog  v11.01 (32 bit)
MySQL - 5.5.5-10.1.21-MariaDB : Database - db_ririuri
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `pj_akses` */

CREATE TABLE `pj_akses` (
  `id_akses` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(10) NOT NULL,
  `level_akses` varchar(15) NOT NULL,
  PRIMARY KEY (`id_akses`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_barang` */

CREATE TABLE `pj_barang` (
  `id_toko` int(5) DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_ci_sessions` */

CREATE TABLE `pj_ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `pj_kategori_barang` */

CREATE TABLE `pj_kategori_barang` (
  `id_toko` int(5) DEFAULT NULL,
  `id_kategori_barang` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `kategori` varchar(40) NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_kategori_barang`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_merk_barang` */

CREATE TABLE `pj_merk_barang` (
  `id_toko` int(5) DEFAULT NULL,
  `id_merk_barang` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `merk` varchar(40) NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_merk_barang`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_pelanggan` */

CREATE TABLE `pj_pelanggan` (
  `id_toko` int(5) DEFAULT NULL,
  `id_pelanggan` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(40) NOT NULL,
  `alamat` text,
  `telp` varchar(40) DEFAULT NULL,
  `info_tambahan` text,
  `kode_unik` varchar(30) NOT NULL,
  `waktu_input` datetime NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_penjualan_detail` */

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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_penjualan_master` */

CREATE TABLE `pj_penjualan_master` (
  `id_toko` int(5) DEFAULT NULL,
  `id_penjualan_m` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `nomor_nota` varchar(40) NOT NULL,
  `tanggal` datetime NOT NULL,
  `grand_total` decimal(10,0) NOT NULL,
  `bayar` decimal(10,0) NOT NULL,
  `biaya_admin` decimal(10,0) NOT NULL,
  `laba_tambahan` decimal(10,0) DEFAULT NULL,
  `keterangan_lain` text,
  `id_pelanggan` mediumint(1) unsigned DEFAULT NULL,
  `id_user` mediumint(1) unsigned NOT NULL,
  PRIMARY KEY (`id_penjualan_m`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_penjualan_resi` */

CREATE TABLE `pj_penjualan_resi` (
  `id_penjualan_m` int(5) DEFAULT NULL,
  `nama_penerima` varchar(50) DEFAULT NULL,
  `alamat_penerima` varchar(255) DEFAULT NULL,
  `no_penerima` varchar(15) DEFAULT NULL,
  `no_resi` varchar(50) DEFAULT NULL,
  `ekspedisi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `pj_toko` */

CREATE TABLE `pj_toko` (
  `id_toko` int(5) NOT NULL AUTO_INCREMENT,
  `nama_toko` varchar(50) DEFAULT NULL,
  `kartu_ucapan` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_toko`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `pj_user` */

CREATE TABLE `pj_user` (
  `id_toko` int(5) DEFAULT NULL,
  `id_user` mediumint(1) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(60) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_akses` tinyint(1) unsigned NOT NULL,
  `status` enum('Aktif','Non Aktif') NOT NULL,
  `dihapus` enum('tidak','ya') NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
