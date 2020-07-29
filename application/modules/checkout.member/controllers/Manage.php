<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('product.member/cart_model');
		$this->load->model('product.member/product_model');
		$this->load->model('order.status.member/transaksi_model');
		$this->load->model('order.status.member/detail_transaksi_model');
		$this->load->model('log_alamat_model');
		
	}

	public function index()
	{
		

		$data = array();
		$data['content'] = 'manage';
		$data['title'] = 'CheckOut';
		$data['funnel'] = $this->cart_model->get_list();
		
		
		$this->load->view($data['content'],$data);
	}

	function page($pg=1)
	{		
		$limit = 10;
		$where = array();
		$where['id_member'] = $this->session->userdata('id');
		$this->cart_model->set_where($where);
		//
		// order by
		$orderBy = array();
		// if($filter['shortby']){
			// $orderBy[$filter['shortby']] = $filter['orderby'];
		// }
		// $orderBy['tbl.Lokasi'] = 'DESC';
		$this->cart_model->set_order($orderBy);
		//
		$list = $this->cart_model->get_list();
		$this->cart_model->set_limit($limit);
		$this->cart_model->set_offset($limit * ($pg - 1));
		// echo $this->db->last_query();exit;
		//
		$page = array();
		$page['limit'] 		= $limit;
		$page['count_row'] 	= $list->num_rows();
		$page['current'] 	= $pg;
		$page['load_func_name'] = 'pageLoadCheckout';
		$page['list'] 		= $this->gen_paging($page);
		//
		$data = array();
		$data['content'] = 'list';		
		$data['list'] = $this->cart_model->get_list();		
		// $data['key'] = $filter;		
		$data['paging'] = $page;		
		$this->load->view($data['content'],$data);
	}

	function getProvinsi()
	{
		$id = $this->input->post('id');
		$this->load->library('RajaOngkir');
		$provinces = $this->rajaongkir->province($id);
		$provinsi = json_decode($provinces, true);
		$data['provinces'] = json_encode($provinsi['rajaongkir']['results']);
		// echo $totalCart;exit();
		if($data['provinces']){
			$this->update['provinsi'] = $data['provinces'];
			$this->success(' Provinsi Ditemukan');
 
		}else{
			$this->error('Provinsi Tidak Ditemukan');
		}

	}

	function getKota()
	{
		$id = $this->input->post('id');
		$idProv = $this->input->post('idProv');
		$this->load->library('RajaOngkir');
		$city = $this->rajaongkir->city($idProv);
		$kota = json_decode($city, true);
		$data['city'] = json_encode($kota['rajaongkir']['results']);
		// echo $totalCart;exit();
		if($data['city']){
			$this->update['kota'] = $data['city'];
			$this->success(' Kota Ditemukan');
 
		}else{
			$this->error('Kota Tidak Ditemukan');
		}

	}

	function getCost()
	{
		$id = $this->input->post('id');
		$destination = $this->input->post('destination');
		$weight = $this->input->post('weight');
		$this->load->library('RajaOngkir');
		$cost = $this->rajaongkir->cost(22, $destination, $weight, 'jne');
		$biaya = json_decode($cost, true);
		$data['cost'] = json_encode($biaya['rajaongkir']['results']);
		// echo $totalCart;exit();
		if($data['cost']){
			$this->update['biaya'] = $data['cost'];
			$this->success(' Biaya Ditemukan');
 
		}else{
			$this->error('Biaya Tidak Ditemukan');
		}

	}

	function TransaksiCheckout()
	{
		$NamaPembeli		 = $this->input->post('NamaPembeli');
		$EmailPembeli		 = $this->input->post('EmailPembeli');
		$NoTelpnPembeli		 = $this->input->post('NoTelpnPembeli');
		$AlamatPembeli		 = $this->input->post('AlamatPembeli');
		$ProvinsiPembeli	 = $this->input->post('ProvinsiPembeli');
		$KotaPembeli		 = $this->input->post('KotaPembeli');
		$KecamatanPembeli	 = $this->input->post('KecamatanPembeli');
		$layananPengiriman	 = $this->input->post('layananPengiriman');
		$servicePengiriman	 = $this->input->post('servicePengiriman');
		$hargaOngkir		 = $this->input->post('hargaOngkir');
		$totalCart		 = $this->input->post('totalCart');
		$diskonCart		 = $this->input->post('diskonCart');

		if (!$NamaPembeli) {
			$this->error('Nama pembeli harus diisi');
		}
		if (!$EmailPembeli) {
			$this->error('Email pembeli harus diisi');
		}
		if (!$NoTelpnPembeli) {
			$this->error('NoTelpn pembeli harus diisi');
		}
		if (!$ProvinsiPembeli) {
			$this->error('Provinsi harus diisi');
		}
		if (!$AlamatPembeli) {
			$this->error('Alamat harus diisi');
		}
		if (!$KotaPembeli) {
			$this->error('Kota harus diisi');
		}
		if (!$KecamatanPembeli) {
			$this->error('Kecamatan harus diisi');
		}

		$getKodeUnik =  $this->transaksi_model->getKodeUnik();

		$data = array();
		$data["id"] = 0;	
		$data["id_member"] = $this->session->userdata('id');	
		$data["nama_pembeli"] = $NamaPembeli;	
		$data["email_pembeli"] = $EmailPembeli;	
		$data["nomor_telepon"] = $NoTelpnPembeli;	
		$data["alamat_pengiriman"] = $AlamatPembeli;	
		$data["kecamatan_pengiriman"] = $KecamatanPembeli;	
		$data["kota_pengiriman"] = $KotaPembeli;	
		$data["provinsi_pengiriman"] = $ProvinsiPembeli;	
		$data["kode_pos_pengiriman"] = 0;	
		$data["tanggal"] = date('Y-m-d');	
		$data["sub_total"] =	$totalCart;	
		$data["ongkir"] = $hargaOngkir;	
		$data["total"] = ((floatval($totalCart) - floatval($diskonCart)) + floatval($hargaOngkir)+floatval($getKodeUnik));	
		$data["diskon"] = $diskonCart;	
		$data["cashback"] = NULL;	
		$data["status"] = 'BELUM BAYAR';	
		$data["id_trafic"] = NULL;	
		$data["service_pengiriman"] = $servicePengiriman;	
		$data["nomor_resi"] = NULL;	
		$data["keterangan"] = NULL;	
		$data["jenis_transaksi"] = 'PENJUALAN';	
		$data["kode_unik"] = $getKodeUnik;	
		$data["update_time"] = date('Y-m-d H:i:s');	
		$save = $this->transaksi_model->save($data);			
		
		$cartData = $this->cart_model->get_list();
		foreach ($cartData->result_array() as $rowCart) {

			$dataDetail['id'] = '';
			$dataDetail['id_transaksi'] = $this->transaksi_model->getLastID();
			$dataDetail['id_produk'] = $rowCart['id_produk'];
			$dataDetail['jumlah'] = $rowCart['jumlah'];
			$dataDetail['harga'] = $rowCart['harga'];
			$dataDetail['diskon'] = $rowCart['diskon'];
			// $dataDetail['cashback'] = $rowCart['cashback'];
			$dataDetail['total'] = $rowCart['total'];

			$this->detail_transaksi_model->save($dataDetail);
			// echo $this->db->last_query();
		// exit;
		}
		$this->cart_model->delete(array('id_member' => $this->session->userdata('id')));

		// echo $save;exit;
		if($save){
			$this->success(' CheckOut Berhasil Ditambahkan');
 
		}else{
			$this->error('CheckOut Gagal Ditambahkan');
		}
	}

	function saveAlamat()
	{
		
		$NamaAlamat		 = $this->input->post('NamaAlamat');
		$AlamatPembeli		 = $this->input->post('AlamatPembeli');
		$ProvinsiPembeli	 = $this->input->post('ProvinsiPembeli');
		$KotaPembeli		 = $this->input->post('KotaPembeli');
		$KecamatanPembeli	 = $this->input->post('KecamatanPembeli');

		if (!$NamaAlamat) {
			$this->error('Nama Alamat harus diisi');
		}

		if (!$ProvinsiPembeli) {
			$this->error('Provinsi harus diisi');
		}
		if (!$AlamatPembeli) {
			$this->error('Alamat harus diisi');
		}
		if (!$KotaPembeli) {
			$this->error('Kota harus diisi');
		}
		if (!$KecamatanPembeli) {
			$this->error('Kecamatan harus diisi');
		}

		$data = array();
		$data["id"] = 0;		
		$data["id_member"] = $this->session->userdata('id');	
		$data["nama_history"] = $NamaAlamat;	
		$data["alamat"] = $AlamatPembeli;	
		$data["kecamatan"] = $KecamatanPembeli;	
		$data["kota"] = $KotaPembeli;	
		$data["provinsi"] = $ProvinsiPembeli;	

		$save = $this->log_alamat_model->save($data);			
		
		if($save){
			$this->success(' Alamat Berhasil Ditambahkan');
 
		}else{
			$this->error('Alamat Gagal Ditambahkan');
		}
	}

	function pageLogAlamat($pg=1)
	{		
		$limit = 10;
		$where = array();
		$where['id_member'] = $this->session->userdata('id');
		$this->log_alamat_model->set_where($where);
		//
		// order by
		$orderBy = array();
		// if($filter['shortby']){
			// $orderBy[$filter['shortby']] = $filter['orderby'];
		// }
		// $orderBy['tbl.Lokasi'] = 'DESC';
		$this->log_alamat_model->set_order($orderBy);
		//
		$list = $this->log_alamat_model->get_list();
		$this->log_alamat_model->set_limit($limit);
		$this->log_alamat_model->set_offset($limit * ($pg - 1));
		// echo $this->db->last_query();exit;
		//
		$page = array();
		$page['limit'] 		= $limit;
		$page['count_row'] 	= $list->num_rows();
		$page['current'] 	= $pg;
		$page['load_func_name'] = 'pageLogAlamat';
		$page['list'] 		= $this->gen_paging($page);
		//
		$data = array();
		$data['content'] = 'listAlamat';		
		$data['list'] = $this->log_alamat_model->get_list();		
		// $data['key'] = $filter;		
		$data['paging'] = $page;		
		$this->load->view($data['content'],$data);
	}

	function getHistory()
	{
		$id = $this->input->post('id');
		$getAlamat  = $this->log_alamat_model->get($id);
		// echo $totalCart;exit();
		if($getAlamat){
			$this->update['alamat'] = $getAlamat;
			$this->success('Alamat Ditemukan');
		}else{
			$this->error('Alamat Tidak Ditemukan');
		}
	}

}