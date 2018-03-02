<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;
use App\Product;
use App\ProductType;

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
}
