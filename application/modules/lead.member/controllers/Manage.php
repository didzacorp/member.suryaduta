<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include 'application/libraries/SimpleEmailService.php';

class Manage extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('transaksi_model');
		
	}

	public function index()
	{
		$data = array();
		$data['content'] = 'manage';
		$data['title'] = 'My Lead';
		
		$this->load->view($data['content'],$data);
	}

	function page($pg=1)
	{		
		$limit = 10;
		$where = array();
		$where['tbl.id_member'] = $this->session->userdata('id');
		$where['tbl.jenis_transaksi'] = "PENDAFTARAN MEMBER (BASIC)";
		$this->transaksi_model->set_order(array('id' => 'ASC'));
		$this->transaksi_model->set_where($where);
		//
		// order by
		$orderBy = array();
		// if($filter['shortby']){
			// $orderBy[$filter['shortby']] = $filter['orderby'];
		// }
		// $orderBy['tbl.Lokasi'] = 'DESC';
		$this->transaksi_model->set_order($orderBy);
		//
		$list = $this->transaksi_model->get_list();
		$this->transaksi_model->set_limit($limit);
		$this->transaksi_model->set_offset($limit * ($pg - 1));
		// echo $this->db->last_query();exit;
		//
		$page = array();
		$page['limit'] 		= $limit;
		$page['count_row'] 	= $list->num_rows();
		$page['current'] 	= $pg;
		$page['load_func_name'] = 'pageLoadLead';
		$page['list'] 		= $this->gen_paging($page);
		//
		$data = array();
		$data['content'] = 'list';		
		$data['list'] = $this->transaksi_model->get_list();		
		// $data['key'] = $filter;		
		$data['paging'] = $page;		
		$this->load->view($data['content'],$data);
	}	

	public function inputLead()
	{
		$data = array();
		$data['content'] = 'input';
		$data['title'] = 'My Lead';
		
		$this->load->view($data['content'],$data);
	}

	function saveLead()
	{
		error_reporting(0);
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		
		if (!$nama) {
			$this->error('Nama Wajib diisi');
		}

		if (!$email) {
			$this->error('Email Wajib diisi');
		}

		$getKodeUnik =  $this->transaksi_model->getKodeUnik();

		$data = array();
		$data["id"] = 0;	
		$data['nama_pembeli'] = $nama;
		$data['email_pembeli'] = $email;
		$data['id_trafic'] = 0;
		$data["id_member"] =  $this->session->userdata('id');	
		$data["tanggal"] = date('Y-m-d');	
		$data["sub_total"] = '500000';	
		$data["total"] =  floatval(500000)+floatval($getKodeUnik);	
		$data["diskon"] = 0;	
		$data["cashback"] = NULL;	
		$data["status"] = 'BELUM BAYAR';	
		$data["jenis_transaksi"] = 'PENDAFTARAN MEMBER (BASIC)';
		$data["kode_unik"] = $getKodeUnik;	
		$data["url_funnel"] = 'http://suryaduta.fujindev.online/direct/'.$this->session->userdata('username').'/Direct';	
		$data["update_time"] = date('Y-m-d H:i:s');	
		$save = $this->transaksi_model->save($data);	

		// $this->update['idTrans'] = $this->transaksi_model->getLastID();
		
		if ($save) {

			$subject = 'Pembayaran Pendaftaran AKADEMI BISNIS';			
			$html_body =  "<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>Surya Duta Member</title>
  </head>

  <body class='sidebar-fixed'>

<div class='container' style='
    text-align: center;
    font-family: monospace;
    font-size: 16px;
'>
    <div class='row justify-content-center'>
        <div class='col-md-12'>
            <div class='card header_card'>
                <div class='card-body'>
                    <h4 class='heading text-center'>Dapatkan Diskon Sebesar 50% Untuk Menjadi Anggota Surya Duta dari Investasi Normal : Rp <del >1.000.000</del> Menjadi Hanya : </h4>
                    <h3 class='heading text-center'>Rp ".number_format($data['sub_total'])."</h3>
                    <h4 class='heading text-center'>(Tanpa Ada Biaya Bulanan + Bonus Produk Rp 585.000).</h4></div>
            </div>
        </div>
        <div class='col-md-12'>
            <div class='card card_rounded'>
                <div class='card-body text-center'>
                    <h4 >Silahkan Transfer Dengan Nominal</h4>
                    <h3 >Rp.".number_format($data['sub_total']+$data['kode_unik'])."</h3>
                    <h5 ><strong >Masukkan nominal sesuai sampai dengan 3 digit terakhir, bila berbeda akan menghambat proses konfirmasi.</strong></h5>
                    <p class='card-text'>note : kami menambahkan nominal sebesar <strong >".$data['kode_unik']."</strong> untuk memudahkan proses konfirmasi transaksi.</p>
                    <h5 >Ke Salah Satu Rekening Dibawah ini :</h5>
                    <div class='bank-box'>
                        <div class='p-2 icon-bank'><img src='https://upload.wikimedia.org/wikipedia/id/thumb/e/e0/BCA_logo.svg/472px-BCA_logo.svg.png' style='    width: 150px;'></div>
                        <h4 ><strong >6041678787</strong></h4>
                        <h5 >Cabang Alam Sutera</h5>
                        <h5 >Atas Nama PT. Sinergi Rezeki Ananta</h5></div>
                    <div class='option-divider-bordered'>
                        <div class='row justify-content-center overlap-row'>
                            <div class='pills-heading'><strong >ATAU</strong></div>
                        </div>
                    </div>
                    <div class='bank-box'>
                        <div class='p-2 icon-bank'><img src='https://upload.wikimedia.org/wikipedia/id/thumb/f/fa/Bank_Mandiri_logo.svg/1280px-Bank_Mandiri_logo.svg.png' style='    width: 150px;'></div>
                        <h4 ><strong >1640016787873</strong></h4>
                        <h5 >Cabang Tangerang BFI Tower</h5>
                        <h5 >Atas Nama PT. Sinergi Rezeki Ananta</h5></div>
                    <div class='option-divider-bordered'>
                        <div class='row justify-content-center overlap-row'>
                            <div class='pills-heading'><strong >ATAU</strong></div>
                        </div>
                    </div>
                    <div class='bank-box'>
                        <div class='p-2 icon-bank'><img src='https://upload.wikimedia.org/wikipedia/commons/9/97/Logo_BRI.png' style='    width: 150px;'></div>
                        <h4 ><strong >050901001197307</strong></h4>
                        <h5 >Cabang BSD Menara BRI</h5>
                        <h5 >Atas Nama PT. Sinergi Rezeki Ananta</h5></div>
                    <h5 style='color:blue'>*MOHON DIPERHATIKAN : Jika Anda melakukan transfer dari rekening bank selain 3 bank di atas, kami sarankan Anda transfernya ke akun Bank BCA kami, untuk proses verifikasi yang lebih cepat. Terima kasih.</h5>
                    <br >
                    <p class='card-text'>Transaksi ini bersifat <strong >non refundable / tidak bisa dikembalikan</strong> dan Setelah Anda melakukan transaksi ini maka Anda telah setuju dengan semua ketentuan yang berlaku.</p>
                </div>
            </div>
    </div>
</div>
</div>
</body>
</html>";

			$m = new SimpleEmailServiceMessage();
			// $m->addTo($data['transaksi']['email_pembeli']);
			$m->addTo($email);
			$m->setFrom('system@akademibisnis.id');
			$m->setSubject($subject);
			$m->setMessageFromString($html_body);

			$ses = new SimpleEmailService('AKIA4YKMZQ5RBE3KCB6U', '1fg/k8gy96DCu0c+HLKLDxE7wUYK0rGTDmVs7H+r');
			$ses->sendEmail($m);

			$this->success('Behasil Prossess');
		}else{
			$this->error('Gagal Prossess');
		}
	}
	
}