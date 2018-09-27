@extends('frontend.template')
@section('title', 'Início')
@section('content')
<!--banner-->
<div class="banner banner-custom">
   <div class="container">
      <section class="rw-wrapper">
         <h1 class="rw-sentence">
            <span>{!! $self->getCampoPersonalizado("texto banner index") !!}</span>
            <div class="rw-words rw-words-1">
               <span>Beautiful Designs</span>
               <span>Sed ut perspiciatis</span>
               <span> Totam rem aperiam</span>
               <span>Nemo enim ipsam</span>
               <span>Temporibus autem</span>
               <span>intelligent systems</span>
            </div>
            <div class="rw-words rw-words-2">
               <span>We denounce with right</span>
               <span>But in certain circum</span>
               <span>Sed ut perspiciatis unde</span>
               <span>There are many variation</span>
               <span>The generated Lorem Ipsum</span>
               <span>Excepteur sint occaecat</span>
            </div>
         </h1>
      </section>
   </div>
</div>
<!--content-->
<div class="content">
   <div class="container">
      <div class="content-top">
         <div class="col-md-6 col-md">
            <div class="col-1">
               <a href="single.html" class="b-link-stroke b-animate-go  thickbox">
                  <img src="{{asset('public/theme/images/noiva.jpg')}}" class="img-responsive" alt=""/>
                  <div class="b-wrapper1 long-img">
                     <p class="b-animate b-from-right    b-delay03 ">Para casamentos e festas de gala</p>
                     <label class="b-animate b-from-right    b-delay03 "></label>
                     <h3 class="b-animate b-from-left    b-delay03 aqua">Gala</h3>
                  </div>
               </a>
               <!---<a href="single.html"><img src="images/pi.jpg" class="img-responsive" alt=""></a>-->
            </div>
            <div class="col-2">
               <span class="aqua">Gala</span>
               <h2><a href="single.html" class="aquahover">Lembraças de luxo</a></h2>
               <p>Seus convidados se lembrarão para sempre de sua festa com as melhores lembrancinhas, elegantes e luxuosas</p>
               <a href="single.html" class="buy-now buy-now-custom">Ver mais...</a>
            </div>
         </div>
         <div class="col-md-6 col-md1">
            <div class="col-3">
               <a href="single.html">
                  <img src="{{asset('public/theme/images/pi1.jpg')}}" class="img-responsive" alt="">
                  <div class="col-pic">
                     <p>Brindes Comporativos</p>
                     <label></label>
                     <h5 class="aqua">Business</h5>
                  </div>
               </a>
            </div>
            <div class="col-3">
               <a href="single.html">
                  <img src="{{asset('public/theme/images/pi2.jpg')}}" class="img-responsive" alt="">
                  <div class="col-pic">
                     <p>Lembranças Infantis</p>
                     <label></label>
                     <h5 class="aqua">For Kids</h5>
                  </div>
               </a>
            </div>
            <div class="col-3">
               <a href="single.html">
                  <img src="{{asset('public/theme/images/fantasia.jpg')}}" class="img-responsive" alt="">
                  <div class="col-pic">
                     <p>lembranças tematicas</p>
                     <label></label>
                     <h5 class="aqua">Thematic</h5>
                  </div>
               </a>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
      <!--products-->
      <div class="content-mid">
         <h3>Destaques</h3>
         <label class="line lineaqua"></label>
         <div class="mid-popular">
            @foreach($self->getSkus() as $sku)
            <div class="col-md-3 item-grid simpleCart_shelfItem">
               <div class=" mid-pop">
                  <div class="pro-img">
                     <img src="{{ $self->getImagemPrincipalSku($sku->skuId) }}" class="img-responsive" alt="">
                     <div class="zoom-icon ">
                        <a class="picture" href="{{ $self->getImagemPrincipalSku($sku->skuId) }}" rel="title" class="b-link-stripe b-animate-go  thickbox"><i class="glyphicon glyphicon-search icon aquasearch"></i></a>
                        <a href="{route('frontend.paginaSku',['skuNome'=> strtolower(str_replace(' ', '_',  $sku->skuNome))  ] )}}"><i class="glyphicon glyphicon-menu-right icon aquasearch"></i></a>
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
      <!--//products-->
   </div>
</div>
<!--//content-->
@stop