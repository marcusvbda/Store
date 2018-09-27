@extends('frontend.template')
@section('title', lcfirst(strtolower($subCategoria->nome)))
@section('content')
<div class="banner-top banner-custom">
	<div class="container">
		<h1>{{lcfirst(strtolower($subCategoria->nome))}}</h1>
		<em></em>
		<h2><a href="{{asset(route('frontend.index'))}}">Inicio</a><label>/</label>{{lcfirst(strtolower($subCategoria->nome))}}</h2>
	</div>
</div>
<div class="product">
	<div class="container">
		@foreach($self->getSkus($subCategoria->id) as $sku)
	    <div class="col-md-3 item-grid simpleCart_shelfItem">
	       <div class=" mid-pop">
	          <div class="pro-img">
	             <img src="{{ $self->getImagemPrincipalSku($sku->skuId) }}" class="img-responsive" alt="">
	             <div class="zoom-icon ">
	                <a class="picture" href="{{ $self->getImagemPrincipalSku($sku->skuId) }}" rel="title" class="b-link-stripe b-animate-go  thickbox"><i class="glyphicon glyphicon-search icon aquasearch"></i></a>
	                <a href="{{route('frontend.paginaSku',['skuNome'=> strtolower(str_replace(' ', '_',  $sku->skuNome))  ] )}}"><i class="glyphicon glyphicon-menu-right icon aquasearch"></i></a>
	             </div>
	          </div>
	          <div class="mid-1">
	             <div class="women">
	                <div class="women-top">
	                   <h6><a href="single.html">{{$sku->skuNome}}</a></h6>
	                </div>
	                <div class="img item_add">
	                   <a href="#"><img src="{{asset('public/theme/images/ca.png')}}" alt=""></a>
	                </div>
	                <div class="clearfix"></div>
	             </div>
	             <div class="mid-2">
	                <p><em class="item_price">$70.00</em></p>
	                <div class="block">
	                   <div class="starbox small ghosting"> </div>
	                </div>
	                <div class="clearfix"></div>
	             </div>
	          </div>
	       </div>
	    </div>
	    @endforeach
	    <div class="clearfix"></div>
	</div>
</div>
@stop