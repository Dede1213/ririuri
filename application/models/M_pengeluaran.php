<?php
class M_pengeluaran extends CI_Model 
{
	function get_all()
	{
		$id_toko = $this->session->userdata('id_toko');

		return $this->db
			->select('id_merk_barang, merk')
			->where('id_toko', $id_toko)
			->where('dihapus', 'tidak')
			->order_by('merk', 'asc')
			->get('pj_merk_barang');
	}

	function fetch_data_pengeluaran($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$id_toko = $this->session->userdata('id_toko');
		
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				id, 
				keterangan,
				nominal,
				tgl_keluar,
				id_user 
			FROM 
				`pj_pengeluaran`, (SELECT @row := 0) r WHERE 1=1 
				AND id_toko = '$id_toko'
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				keterangan LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'keterangan'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function tambah($keterangan,$nominal,$tgl_keluar)
	{

		$id_toko = $this->session->userdata('id_toko');
		$dt = array(
			'id_toko' => $id_toko,
			'keterangan' => $keterangan,
			'nominal' => $nominal,
			'tgl_keluar' => $tgl_keluar
		);

		return $this->db->insert('pj_pengeluaran', $dt);
	}

	function hapus_pengeluaran($id)
	{
		$id_toko = $this->session->userdata('id_toko');

		return $this->db
			->where('id', $id)
			->where('id_toko', $id_toko)
			->delete('pj_pengeluaran');
	}

	function get_baris($id)
	{
		$id_toko = $this->session->userdata('id_toko');

		return $this->db
			->select('*')
			->where('id_toko', $id_toko)
			->where('id', $id)
			->limit(1)
			->get('pj_pengeluaran');
	}

	function update($id, $keterangan, $nominal, $tgl_keluar)
	{
		$dt = array(
			'keterangan' => $keterangan,
			'nominal' => $nominal,
			'tgl_keluar' => $tgl_keluar
		);

		return $this->db
			->where('id', $id)
			->update('pj_pengeluaran', $dt);
	}

	function laporan_pengeluaran($from, $to)
	{
		$id_toko = $this->session->userdata('id_toko');

		$sql = "SELECT a.* FROM `pj_pengeluaran` a 
		
			WHERE 
				SUBSTR(a.`tgl_keluar`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tgl_keluar`, 1, 10) <= '".$to."' 
				AND a.id_toko = '$id_toko'
			ORDER BY 
				a.`tgl_keluar` ASC
		";

		// print_r($sql);
		// exit;
		return $this->db->query($sql);
	}
}