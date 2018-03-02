<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;
use App\Cart;
use Session;
use App\Customer;
use App\Bill;
use App\BillDetail;
use App\User;
use Hash;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    //

    public function getIndex(){
        $slides = Slide::all();

        $new_product = Product::where('new',1)->paginate(4);
        $sanpham_khuyenmai = Product::where('promotion_price','<>', 0)->paginate(8);

        return view('page.trangchu', compact(
            'slides', 
            'new_product',
            'sanpham_khuyenmai'
        ));
    }

    public function getLoaiSp($type){        
        $sp_theoloai = Product::where('id_type', $type)->get();
        $sp_khac = Product::where('id_type', '<>', $type)->paginate(3);
        $loai_sp = ProductType::find($type);
        $loai = ProductType::all();

    	return view('page.loai_sanpham', compact('sp_theoloai', 'loai_sp', 'sp_khac', 'loai'));
    }

    public function getChitiet(Request $req){
        $sanpham = Product::find($req->id);
        //$sp_lienquan = $sanpham->product_type->products;
        $sp_lienquan = Product::where('id_type', $sanpham->id_type)->paginate(3);
    	return view('page.chitiet_sanpham', compact('sanpham', 'sp_lienquan'));
    }

    public function getLienHe(){
        return view('page.lienhe');
    }

    public function getGioiThieu(){
        return view('page.gioithieu');
    }

    public function getAddtoCart(Request $req, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart', $cart);

        return redirect()->back();
    }

    public function getDelItemCart($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items) > 0){
            Session::put('cart', $cart);    
        }else{
            Session::remove('cart');
        }
        


        return redirect()->back();
    }

    public function getCheckout(){
        $carts = Session::has('cart') ? Session::get('cart') : null;
        return view('page.dathang', compact('carts'));
    }

    public function postCheckout(Request $req){
        $customer = new Customer;
        $customer->name = $req->name;
        $customer->gender = $req->gender;
        $customer->address = $req->address;
        $customer->email = $req->email;
        $customer->phone_number = $req->phone;
        $customer->note = $req->notes;

        $customer->save();

        $cart = Session::get('cart');

        $bill = new Bill;
        $bill->id_customer = $customer->id;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment_method;
        $bill->note = $req->notes;
        $bill->save();

        foreach ($cart->items as $key => $value) {
            $billDtl = new BillDetail;
            $billDtl->id_bill = $bill->id;
            $billDtl->id_product = $key;
            $billDtl->quantity = $value['qty'];
            $billDtl->unit_price = $value['original_price'];
            $billDtl->save();
        }

        Session::forget('cart');

        return redirect()->back()->with('thongbao', 'Đặt hàng thành công');


    }

    public function getLogin(){
        return view('page.login');
    }

    public function postLogin(Request $req){
        $this->validate($req,
            [
                'email'=>'required|email',
                'password'=>'required'
            ],
            [
                'email.required'=>'Vui lòng nhập email',
                'email.email'=>'Email không đúng định dạng',
                'password.required'=>'Vui lòng nhập mật khẩu'
            ]
        );

        $credentials = array('email'=>$req->email, 'password'=>$req->password);
        if(Auth::attempt($credentials)){
            //return redirect()->back()->with('success', 'Đăng nhập thành công');            
            return redirect()->route('trang-chu');
        }

        /*$pwd = Hash::make($req->password);
        $user = User::where('email', $req->email)->where('password', $pwd)->first();

        if($user){

        }*/

        return redirect()->back()->with('errors', 'Đăng nhập không thành công');

    }


    public function postRegister(Request $req){
        $this->validate($req,
            [
                'email'=>'required|email|unique:users,email',
                'password'=>'required|min:6|max:20',
                'fullname'=>'required',
                'rpassword'=>'required|same:password'
            ],
            [
                'email.required'=>'Vui lòng nhập email',
                'email.email'=>'Không đúng định dạng email',
                'email.unique'=>'Email đã có người sử dụng',
                'password.required'=>'Vui lòng nhập mật khẩu',
                'rpassword.same'=>'Mật khẩu không giống nhau',
                'password.min'=>'Mật khẩu ít nhất 6 ký tự',
                'password.max'=>'Mật khẩu tối đa 20 ký tự'
            ]
        );

        $user = new User;
        $user->full_name=$req->fullname;
        $user->email=$req->email;
        $user->password=Hash::make($req->password);
        $user->phone=$req->phone;
        $user->address=$req->address;

        $user->save();

        return redirect()->back()->with('success','Tạo tài khoản thành công');
    }

    public function getRegister(){
        return view('page.register');
    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('trang-chu');
    }


}
