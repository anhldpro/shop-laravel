@extends('master')
@section('content')
<div class="inner-header">
	<div class="container">
		<div class="pull-left">
			<h6 class="inner-title">Đặt hàng</h6>
		</div>
		<div class="pull-right">
			<div class="beta-breadcrumb">
				<a href="{{route('trang-chu')}}">Trang chủ</a> / <span>Đặt hàng</span>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div class="container">
	<div id="content">
		@if(Session::has('cart'))
		<form action="{{route('dathang')}}" method="post" class="beta-form-checkout">
			{{csrf_field()}}
			<div class="row">
				<div class="col-sm-6">
					<h4>Thông tin khách hàng</h4>
					<div class="space20">&nbsp;</div>

					<div class="form-block">
						<label for="name">Họ tên*</label>
						<input type="text" name="name" id="name" placeholder="Họ tên" required>
					</div>
					<div class="form-block">
						<label>Giới tính </label>
						<input id="gender" type="radio" class="input-radio" name="gender" value="nam" checked="checked" style="width: 10%"><span style="margin-right: 10%">Nam</span>
						<input id="gender" type="radio" class="input-radio" name="gender" value="nữ" style="width: 10%"><span>Nữ</span>
									
					</div>

					<div class="form-block">
						<label for="email">Email*</label>
						<input type="email" id="email" name="email" required placeholder="expample@gmail.com">
					</div>

					<div class="form-block">
						<label for="adress">Địa chỉ*</label>
						<input type="text" id="adress" name="address" placeholder="Street Address" required>
					</div>
					

					<div class="form-block">
						<label for="phone">Điện thoại*</label>
						<input type="text" id="phone" name="phone" required>
					</div>
					
					<div class="form-block">
						<label for="notes">Ghi chú</label>
						<textarea id="notes" name="notes"></textarea>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="your-order">
						<div class="your-order-head"><h5>Đơn hàng của bạn</h5></div>
						<div class="your-order-body" style="padding: 0px 10px">
							<div class="your-order-item">
								<div>
								<!--  one item	 -->
								@foreach($product_cart as $item)
									<div class="media">
										<a href="{{route('chitietsanpham', $item['item']['id'])}}"><img width="25%" src="source/image/product/{{$item['item']['image']}}" alt="" class="pull-left"></a>
										<div class="media-body">
											<p class="font-large">{{$item['item']['name']}}</p>
											<span class="color-gray your-order-info">Số lượng: {{$item['qty']}}</span>
											<span class="color-gray your-order-info">Giá gốc: {{number_format($item['item']['unit_price'])}} VND</span>
											<span class="color-gray your-order-info">Giá khuyến mãi: @if($item['item']['promotion_price']!=0){{number_format($item['item']['promotion_price'])}} VND @endif</span>
											<span class="color-gray your-order-info">Thành tiền: {{number_format($item['price'])}} VND</span>
										</div>
									</div>
								<!-- end one item -->
								@endforeach
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="your-order-item">
								<div class="pull-left"><p class="your-order-f18">Tổng tiền:</p></div>
								<div class="pull-right"><h5 class="color-black">{{number_format($totalPrice)}} VND</h5></div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="your-order-head"><h5>Hình thức thanh toán</h5></div>
						
						<div class="your-order-body">
							<ul class="payment_methods methods">
								<li class="payment_method_bacs">
									<input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="COD" checked="checked" data-order_button_text="">
									<label for="payment_method_bacs">Thanh toán khi nhận hàng </label>
									<div class="payment_box payment_method_bacs" style="display: block;">
										Cửa hàng sẽ gửi hàng đến địa chỉ của bạn, bạn xem hàng rồi thanh toán tiền cho nhân viên giao hàng
									</div>						
								</li>

								<li class="payment_method_cheque">
									<input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="ATM" data-order_button_text="">
									<label for="payment_method_cheque">Chuyển khoản </label>
									<div class="payment_box payment_method_cheque" style="display: none;">
										Chuyển tiền đến tài khoản sau:
										<br>- Số tài khoản: 123 456 789
										<br>- Chủ TK: Nguyễn A
										<br>- Ngân hàng ACB, Chi nhánh TPHCM
									</div>						
								</li>
								
							</ul>
						</div>

						<div class="text-center"><button type="submit" class="beta-btn primary">Đặt hàng <i class="fa fa-chevron-right"></i></button></div>
					</div> <!-- .your-order -->
				</div>
			</div>
		</form>
		@endif
		<div class="row">
			@if(Session::has('thongbao'))
				{{Session::get('thongbao')}}
			@endif
		</div>
	</div> <!-- #content -->
</div> <!-- .container -->
@endsection