<?php
class M_penjualan_master extends CI_Model
{
	function insert_master($nomor_nota, $tanggal, $id_kasir, $id_pelanggan, $bayar, $grand_total, $catatan, $biaya_admin, $laba_tambahan)
	{
		$id_toko = $this->session->userdata('id_toko');

		$dt = array(
			'id_toko' => $id_toko,
			'nomor_nota' => $nomor_nota,
			'tanggal' => $tanggal,
			'grand_total' => $grand_total,
			'bayar' => $bayar,
			'biaya_admin' => $biaya_admin,
			'laba_tambahan' => $laba_tambahan,
			'keterangan_lain' => $catatan,
			'id_pelanggan' => (empty($id_pelanggan)) ? NULL : $id_pelanggan,
			'id_user' => $id_kasir
		);

		return $this->db->insert('pj_penjualan_master', $dt);
	}

	function insert_resi($id_penjualan_m,$nama_penerima, $alamat_penerima, $no_resi, $no_hp, $ekspedisi)
	{
		
		$dt = array(
			'id_penjualan_m' => $id_penjualan_m,
			'nama_penerima' => $nama_penerima,
			'alamat_penerima' => $alamat_penerima,
			'no_resi' => $no_resi,
			'no_penerima' => $no_hp,
			'ekspedisi' => $ekspedisi
		);

		return $this->db->insert('pj_penjualan_resi', $dt);
	}

	function get_id($nomor_nota)
	{
		return $this->db
			->select('id_penjualan_m')
			->where('nomor_nota', $nomor_nota)
			->limit(1)
			->get('pj_penjualan_master');
	}

	function fetch_data_penjualan($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_penjualan_m`, 
				a.`nomor_nota` AS nomor_nota, 
				DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') AS tanggal,
				CONCAT(REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) AS grand_total,
				IF(b.`nama` IS NULL, 'Umum', b.`nama`) AS nama_pelanggan,
				REPLACE(FORMAT(a.`biaya_admin`, 0),',','.') AS biaya_admin,
				c.`nama` AS kasir,
				a.`keterangan_lain` AS keterangan   
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_pelanggan` AS b ON a.`id_pelanggan` = b.`id_pelanggan` 
				LEFT JOIN `pj_user` AS c ON a.`id_user` = c.`id_user` 
				, (SELECT @row := 0) r WHERE 1=1 
		";

		// echo $sql;
		// exit;
		
		
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`nomor_nota` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR CONCAT('Rp. ', REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR IF(b.`nama` IS NULL, 'Umum', b.`nama`) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR c.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`keterangan_lain` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " )";
		}
		
		
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`tanggal`',
			2 => 'nomor_nota',
			3 => 'a.`grand_total`',
			4 => 'nama_pelanggan',
			5 => 'keterangan',
			6 => 'kasir'
		);
		$sql .= " AND a.id_toko = '$id_toko'";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function get_baris($id_penjualan)
	{
		
		$sql = "
			SELECT 
				a.`id_penjualan_m`,
				a.`nomor_nota`, 
				a.`grand_total`,
				a.`tanggal`,
				a.`bayar`,
				a.`id_user` AS id_kasir,
				a.`id_pelanggan`,
				a.`keterangan_lain` AS catatan,
				b.`nama` AS nama_pelanggan,
				b.`alamat` AS alamat_pelanggan,
				b.`telp` AS telp_pelanggan,
				b.`info_tambahan` AS info_pelanggan,
				a.`biaya_admin`,
				a.`laba_tambahan`  
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_pelanggan` AS b ON a.`id_pelanggan` = b.`id_pelanggan` 
			WHERE 
				a.`id_penjualan_m` = '".$id_penjualan."' 
			LIMIT 1
		";
		return $this->db->query($sql);
	}

	function hapus_transaksi($id_penjualan, $reverse_stok)
	{
		if($reverse_stok == 'yes'){
			$loop = $this->db
				->select('id_barang, jumlah_beli')
				->where('id_penjualan_m', $id_penjualan)
				->get('pj_penjualan_detail');

			foreach($loop->result() as $b)
			{
				$sql = "
					UPDATE `pj_barang` SET `total_stok` = `total_stok` + ".$b->jumlah_beli." 
					WHERE `id_barang` = '".$b->id_barang."' 
				";

				$this->db->query($sql);

				// ########## update stok bundling ###########
				$getBundling = $this->db
				->select('id_barang_bundling')
				->where('id_barang', $b->id_barang)
				->get('pj_bundling')->result_array();

				if($getBundling){
					foreach($getBundling as $row){
						$sqlBundling = "
							UPDATE `pj_barang` SET `total_stok` = `total_stok` + ".$b->jumlah_beli." 
							WHERE `id_barang` = '".$row['id_barang_bundling']."' 
						";
						$this->db->query($sqlBundling);
					}
				}

				//end
			}
		}

		$this->db->where('id_penjualan_m', $id_penjualan)->delete('pj_penjualan_resi');
		$this->db->where('id_penjualan_m', $id_penjualan)->delete('pj_penjualan_detail');
		return $this->db->where('id_penjualan_m', $id_penjualan)->delete('pj_penjualan_master');
	}

	function laporan_penjualan($from, $to, $id_barang)
	{

		if($id_barang != '0'){
			$WHERE = "AND b.id_barang ='".$id_barang."'";
		}else{
			$WHERE = '';
		}

		$id_toko = $this->session->userdata('id_toko');

		$sql = "SELECT a.*,b.*,c.nama_barang,d.nama FROM `pj_penjualan_master` a 
		LEFT JOIN `pj_penjualan_detail` b ON a.`id_penjualan_m`=b.`id_penjualan_m`
		LEFT JOIN `pj_barang` c ON b.`id_barang`= c.`id_barang`
		LEFT JOIN `pj_pelanggan` d on a.id_pelanggan = d.id_pelanggan
		
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '".$to."' 
				AND a.id_toko = '$id_toko'
				$WHERE
			ORDER BY 
				a.`tanggal` ASC
		";

		// print_r($sql);
		// exit;
		return $this->db->query($sql);
	}

	function cek_nota_validasi($nota)
	{
		return $this->db->select('nomor_nota')->where('nomor_nota', $nota)->limit(1)->get('pj_penjualan_master');
	}

	function update_transaksi($id_penjualan_m, $biaya_admin, $laba_tambahan)
	{
		$dt = array(
			'biaya_admin' => $biaya_admin,
			'laba_tambahan' => $laba_tambahan
		);

		return $this->db
			->where('id_penjualan_m', $id_penjualan_m)
			->update('pj_penjualan_master', $dt);
	}


	function get_resi_masal($jumlah_cetak)
	{
		$id_toko = $this->session->userdata('id_toko');
		$sqlA = "
		CREATE TEMPORARY TABLE IF NOT EXISTS tmp_masal AS (SELECT a.id_penjualan_m,a.nomor_nota,b.nama_penerima,b.alamat_penerima,b.no_penerima,b.no_resi,b.ekspedisi,c.*
		FROM pj_penjualan_master a 
		LEFT JOIN pj_penjualan_resi b ON a.id_penjualan_m = b.id_penjualan_m
		LEFT JOIN pj_toko c ON a.id_toko = c.id_toko
		WHERE a.id_toko = '$id_toko'
		ORDER BY a.id_penjualan_m DESC LIMIT $jumlah_cetak)";
		$this->db->query($sqlA);

		$sqlB = "SELECT * FROM tmp_masal ORDER BY id_penjualan_m ASC";

		return $this->db->query($sqlB);
	}

	function get_trx_day()
	{
		$bulan_berjalan = date('Y-m');
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
		SELECT COUNT(id_penjualan_m) AS total_transaksi, DATE_FORMAT(tanggal,'%d %M %Y') AS tanggal FROM `pj_penjualan_master` 
		WHERE id_toko = '$id_toko' AND DATE_FORMAT(tanggal,'%Y-%m') = '$bulan_berjalan'
		GROUP BY DATE_FORMAT(tanggal,'%d %M %Y')
		";
		return $this->db->query($sql);
	}


	function get_trx_day_before()
	{
		$bulan_berjalan = date('m');
		$tahun_berjalan = date('Y');

		

		if($bulan_berjalan == '01'){
			$tahun_berjalan = $tahun_berjalan-1;
			$bulan_berjalan = '12';
		}else{
			$bulan_berjalan = $bulan_berjalan - 1;

			if(strlen($bulan_berjalan) ==  1){
				$bulan_berjalan = '0'.$bulan_berjalan;
			}
		}
	

		

		$ym = $tahun_berjalan.'-'.$bulan_berjalan;

		
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
		SELECT COUNT(id_penjualan_m) AS total_transaksi, DATE_FORMAT(tanggal,'%d %M %Y') AS tanggal FROM `pj_penjualan_master` 
		WHERE id_toko = '$id_toko' AND DATE_FORMAT(tanggal,'%Y-%m') = '$ym'
		GROUP BY DATE_FORMAT(tanggal,'%d %M %Y')
		";
		return $this->db->query($sql);
	}

	function get_trx_month()
	{
		$tahun_berjalan = date('Y');
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
		SELECT COUNT(id_penjualan_m) AS total_transaksi, DATE_FORMAT(tanggal,'%M %Y') AS tanggal FROM `pj_penjualan_master` 
		WHERE id_toko = '$id_toko' AND DATE_FORMAT(tanggal,'%Y') = '$tahun_berjalan'
		GROUP BY DATE_FORMAT(tanggal,'%m')
		";
		return $this->db->query($sql);
	}

	function get_trx_month_before()
	{
		$tahun_berjalan = date('Y')-1;
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
		SELECT COUNT(id_penjualan_m) AS total_transaksi, DATE_FORMAT(tanggal,'%M %Y') AS tanggal FROM `pj_penjualan_master` 
		WHERE id_toko = '$id_toko' AND DATE_FORMAT(tanggal,'%Y') = '$tahun_berjalan'
		GROUP BY DATE_FORMAT(tanggal,'%m')
		";
		return $this->db->query($sql);
	}

}