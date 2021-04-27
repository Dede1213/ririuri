<?php
class M_bundling_barang extends CI_Model 
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

	function fetch_data_bundling($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		
		$id_toko = $this->session->userdata('id_toko');
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.id_bundling,
				b.kode_barang as kode_barang,
				b.nama_barang as nama_barang,
				c.kode_barang as kode_barang_bundling, 
				c.nama_barang as nama_barang_bundling
			FROM 
				`pj_bundling` a
			LEFT JOIN pj_barang b on a.id_barang=b.id_barang
			LEFT JOIN pj_barang c on a.id_barang_bundling=c.id_barang
			, (SELECT @row := 0) r 	
			WHERE 1=1 
				AND a.id_toko = '$id_toko'  
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				merk LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'nama_barang'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function tambah_bundling($id_barang,$id_barang_bundling)
	{

		$id_toko = $this->session->userdata('id_toko');
		$dt = array(
			'id_toko' => $id_toko,
			'id_barang' => $id_barang,
			'id_barang_bundling' => $id_barang_bundling
		);

		return $this->db->insert('pj_bundling', $dt);
	}

	function hapus_bundling($id_bundling)
	{
		$id_toko = $this->session->userdata('id_toko');
		return $this->db
				->where('id_toko', $id_toko)
				->where('id_bundling', $id_bundling)
				->delete('pj_bundling');
	}

	function get_baris($id_barang)
	{
		$id_toko = $this->session->userdata('id_toko');

		return $this->db
			->select('id_barang_bundling')
			->where('id_toko', $id_toko)
			->where('id_barang', $id_barang)
			->get('pj_bundling')
			->result_array();
	}

	// function update_merek($id_merk_barang, $merek)
	// {
	// 	$dt = array(
	// 		'merk' => $merek
	// 	);

	// 	return $this->db
	// 		->where('id_merk_barang', $id_merk_barang)
	// 		->update('pj_merk_barang', $dt);
	// }
}