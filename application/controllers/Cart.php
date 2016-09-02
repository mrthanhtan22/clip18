<?php 
	/**
	* 
	*/
	class Cart extends MY_Controller
	{
		
		function __construct()
		{
			parent::__construct();
		}
		function add(){
			$this->load->model('product_model');
			$id = $this->uri->rsegment(3);
			$product = $this->product_model->get_info($id);
			if (!$product) {
				redirect();
			}
			/*tong so san pham*/
			$qty = 1;
			$price = $product ->price;
			if ($product->discount > 0) {
				$price = $product->price - $product->discount;
			}

			$this->load->library('cart');
			$data = array();
			$data['id'] = $id;
			$data['qty'] = $qty;
			$data['name'] = url_title($product->name);
			$data['img_link'] = $product->image_link;
			$data['price'] = $price;
		
			$this->cart->insert($data);

			//chuyen sang trang danh sach san pham trong gio hang
        	redirect(base_url('cart'));

		}
		function index(){
			//thong tin gio hang
			$carts = $this->cart->contents();
			$total_items = $this->cart->total_items();

			$this->data['carts'] = $carts;
			$this->data['total_items'] = $total_items;

			$this->data['temp'] = 'site/cart/index';
			$this->load->view('site/layout', $this->data);
		}
		function update(){
			//thong tin gio hang
			$carts = $this->cart->contents();
			foreach ($carts as $key => $row) {
				/*tong so luong*/
			$total_qty = $this->input->post('qty_'.$row['id']);
			$data= array();
			$data['rowid'] = $key;
			$data['qty'] = $total_qty;
			$this->cart->update($data);
			}
			redirect(base_url('cart'));

		}
		function del(){
			$id =$this->uri->rsegment(3);
			$id = intval($id);
			if ($id >0 ) {
			$carts = $this->cart->contents();
			foreach ($carts as $key => $row) {
				/*tong so luong*/
			$data= array();
			$data['rowid'] = $key;
			$data['qty'] = 0;
			$this->cart->update($data);
				}
			} else {
				$this->cart->destroy();
			}
			redirect(base_url('cart'));
		}
	}
 ?>