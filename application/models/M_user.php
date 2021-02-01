<?php
class M_user extends CI_Model 
{
	function validasi_login($username, $password)
	{
		return $this->db
			->select('a.id_user, a.username, a.password, a.nama, a.id_toko, b.label AS level, b.level_akses AS level_caption', false)
			->join('pj_akses b', 'a.id_akses = b.id_akses', 'left')
			->where('a.username', $username)
			->where('a.password', sha1($password))
			->where('a.status', 'Aktif')
			->where('a.dihapus', 'tidak')
			->limit(1)
			->get('pj_user a');
	}

	function is_valid($u, $p)
	{
		return $this->db
			->select('id_user')
			->where('id_user', $u)
			->where('password', $p)
			->where('status','Aktif')
			->where('dihapus','tidak')
			->limit(1)
			->get('pj_user');
	}

	function list_kasir()
	{
		$id_toko = $this->session->userdata('id_toko');

		return $this->db
			->select('id_user, nama')
			->where('id_toko', $id_toko)
			->where('status', 'Aktif')
			->where('dihapus', 'tidak')
			->order_by('nama','asc')
			->get('pj_user');
	}

	function fetch_data_user($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$id_toko = $this->session->userdata('id_toko');

		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_user`, 
				a.`username`, 
				a.`nama`,
				a.`status`,
				b.`level_akses`,
				b.`label`  
			FROM 
				`pj_user` AS a 
				LEFT JOIN `pj_akses` AS b ON a.`id_akses` = b.`id_akses` 
				, (SELECT @row := 0) r WHERE 1=1 
				AND a.`id_toko` = '$id_toko'
				AND a.`dihapus` = 'tidak' 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`username` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`status` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR b.`level_akses` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`username`',
			2 => 'a.`nama`',
			3 => 'b.`level_akses`',
			4 => 'a.`status`'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function hapus_user($id_user)
	{
		$dt['dihapus'] = 'ya';
		return $this->db
				->where('id_user', $id_user)
				->update('pj_user', $dt);
	}

	function cek_username($username)
	{
		return $this->db
			->select('id_user')
			->where('username', $username)
			->where('dihapus', 'tidak')
			->limit(1)
			->get('pj_user');
	}

	function tambah_baru($username, $password, $nama, $id_akses, $status)
	{

		$id_toko = $this->session->userdata('id_toko');

		$dt = array(
			'id_toko' => $id_toko,
			'username' => $username,
			'password' => sha1($password),
			'nama' => $nama,
			'id_akses' => $id_akses,
			'status' => $status,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_user', $dt);
	}

	function registrasi_baru($id_toko,$username, $password, $nama, $id_akses, $status)
	{


		$dt = array(
			'id_toko' => $id_toko,
			'username' => $username,
			'password' => sha1($password),
			'nama' => $nama,
			'id_akses' => $id_akses,
			'status' => $status,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_user', $dt);
	}

	function registrasi_toko($nama)
	{

		$dt = array(
			'nama_toko' => $nama
		);

		return $this->db->insert('pj_toko', $dt);
	}

	function get_baris($id_user)
	{
		
		$sql = "
			SELECT 
				a.`id_user`,
				a.`username`,
				a.`nama`,
				a.`id_akses`,
				a.`status`,
				b.`label` 
			FROM 
				`pj_user` a 
				LEFT JOIN `pj_akses` b ON a.`id_akses` = b.`id_akses` 
			WHERE 
				a.`id_user` = '".$id_user."' 
			LIMIT 1
		";

		return $this->db->query($sql);
	}

	function update_user($id_user, $username, $password, $nama, $id_akses, $status)
	{
		$dt['username'] = $username;

		if( ! empty($password)){
			$dt['password'] = sha1($password);
		}

		$dt['nama']		= $nama;
		$dt['id_akses']	= $id_akses;
		$dt['status']	= $status;
		
		return $this->db
			->where('id_user', $id_user)
			->update('pj_user', $dt);
	}

	function cek_password($pass)
	{
		return $this->db
			->select('id_user')
			->where('password', sha1($pass))
			->where('id_user', $this->session->userdata('ap_id_user'))
			->limit(1)
			->get('pj_user');
	}

	function update_password($pass_new)
	{
		$dt['password'] = sha1($pass_new);
		return $this->db
				->where('id_user', $this->session->userdata('ap_id_user'))
				->update('pj_user', $dt);
	}

	function update_toko($nama,$kartu,$no_telp,$alamat)
	{
		$dt['nama_toko']		= $nama;
		$dt['kartu_ucapan']	= $kartu;
		$dt['no_telp']	= $no_telp;
		$dt['alamat']	= $alamat;

		return $this->db
				->where('id_toko', $this->session->userdata('id_toko'))
				->update('pj_toko', $dt);
	}


	function get_toko()
	{
		$id_toko = $this->session->userdata('id_toko');
		$sql = "SELECT * FROM pj_toko WHERE id_toko = '$id_toko'";

		return $this->db->query($sql);
	}

	function insert_log()
	{

		date_default_timezone_set("Asia/Bangkok");
		
		$dt = array(
			'id_toko' => $this->session->userdata('id_toko'),
			'ap_id_user' => $this->session->userdata('ap_id_user'),
			'ap_level_caption' => $this->session->userdata('ap_level_caption'),
			'ap_nama' => $this->session->userdata('ap_nama'),
			'datetime' => date("Y-m-d h:i:s")

		);

		return $this->db->insert('pj_log', $dt);
	}

	function get_log()
	{
		$sql = "SELECT a.ap_nama,a.ap_level_caption,b.nama_toko,a.datetime
		FROM pj_log a LEFT JOIN pj_toko b ON a.id_toko = b.id_toko 
		WHERE DATE(DATETIME) = CURDATE()";

		return $this->db->query($sql);
	}
}