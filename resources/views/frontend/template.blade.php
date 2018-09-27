<!DOCTYPE html>
<html>
   <head>
      <title>@yield('title')</title>
      <link href="{{asset('public/theme/css/bootstrap.css')}}" rel="stylesheet" type="text/css" media="all" />
      <!-- Custom Theme files -->
      <!--theme-style-->
      <link href="{{asset('public/theme/css/style.css')}}" rel="stylesheet" type="text/css" media="all" />
      <link href="{{asset('public/theme/css/custom.css')}}" rel="stylesheet" type="text/css" media="all" />
      <!--//theme-style-->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="keywords" content="Shopin Responsive web template, Bootstrap Web Templates, Flat Web Templates, AndroId Compatible web template, 
         Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
      <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
      <!--theme-style-->
      <link href="{{asset('public/theme/css/style4.css')}}" rel="stylesheet" type="text/css" media="all" />
      <!--//theme-style-->
      <script src="{{asset('public/theme/js/jquery.min.js')}}"></script>
      <!--- start-rate---->
      <script src="{{asset('public/theme/js/jstarbox.js')}}"></script>
      <link rel="stylesheet" href="{{asset('public/theme/css/jstarbox.css')}}" type="text/css" media="screen" charset="utf-8" />
      <script type="text/javascript">
         jQuery(function() {
         jQuery('.starbox').each(function() {
         	var starbox = jQuery(this);
         		starbox.starbox({
         		average: starbox.attr('data-start-value'),
         		changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
         		ghosting: starbox.hasClass('ghosting'),
         		autoUpdateAverage: starbox.hasClass('autoupdate'),
         		buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
         		stars: starbox.attr('data-star-count') || 5
         		}).bind('starbox-value-changed', function(event, value) {
         		if(starbox.hasClass('random')) {
         		var val = Math.random();
         		starbox.next().text(' '+val);
         		return val;
         		} 
         	})
         });
         });
      </script>
      <!---//End-rate---->
   </head>
   <body>
      <!--header-->
      <div class="header">
         <div class="container">
            <div class="head">
               <div class=" logo">
                  <a href="{{asset(route('frontend.index'))}}"><img src="{{asset('public/theme/images/logo_azul.png')}}" alt=""></a>	
               </div>
            </div>
         </div>
         <div class="header-top">
            <div class="container">
               <div class="col-sm-5 col-md-offset-2  header-login">
               </div>
               <div class="col-sm-5 header-social">
                  <ul >
                     <li><a href="#"><i></i></a></li>
                     <li><a href="#"><i class="ic1"></i></a></li>
                  </ul>
               </div>
               <div class="clearfix"> </div>
            </div>
         </div>
         <div class="container">
            <div class="head-top">
               <div class="col-sm-8 col-md-offset-2 h_menu4">
                  <nav class="navbar nav_bottom" role="navigation">
                     <!-- Brand and toggle get grouped for better mobile display -->
                     <div class="navbar-header nav_2">
                        <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>
                     </div>
                     <!-- Collect the nav links, forms, and other content for toggling -->
                     <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
                        <ul class="nav navbar-nav nav_1">
                           <li><a class="color nav_custom" href="{{asset(route('frontend.index'))}}">Início</a></li>
                           <li class="dropdown mega-dropdown active">
                              <a class="color1" href="#" class="dropdown-toggle" data-toggle="dropdown">Produtos<span class="caret"></span></a>				
                              <div class="dropdown-menu ">
                                 <div class="menu-top">

                                 	@foreach($self->getCategorias() as $categoria)
                                    <div class="col1">
                                       <div class="h_nav">
                                          <h4>{{$categoria->nome}}</h4>
                                          <ul>
                                             @foreach($self->getSubCategorias($categoria->id) as $subcategoria)
                                                <li><a href="{{route('frontend.subCategoria',[ 
                                                      'subCategoriaNome' => 
                                                      strtolower(str_replace(' ', '_',  $subcategoria->nome))
                                                      ]) }}">{{lcfirst(strtolower($subcategoria->nome))}}</a></li>
                                             @endforeach
                                          </ul>
                                       </div>
                                    </div>
                                    @endforeach

                                    <div class="clearfix"></div>
                                 </div>
                              </div>
                           </li>
                           <li><a class="color3 nav_custom" href="product.html">Parceiros</a></li>
                           <li><a class="color4 nav_custom" href="404.html">Sobre</a></li>
                           <li ><a class="color6 nav_custom" href="contact.html">Contatos</a></li>
                        </ul>
                     </div>
                     <!-- /.navbar-collapse -->
                  </nav>
               </div>
               <div class="col-sm-2 search-right">
                  <ul class="heart">
                     <li><a class="play-icon popup-with-zoom-anim" href="#small-dialog"><i class="glyphicon glyphicon-search"> </i></a></li>
                  </ul>
                  <div class="cart box_1">
                     <a href="checkout.html">
                        <h3>
                           <div class="total">
                              <span class="simpleCart_total"></span>
                           </div>
                           <img src="{{asset('public/theme/images/cart.png')}}" alt=""/>
                        </h3>
                     </a>
                     <p><a href="javascript:;" class="simpleCart_empty">Carrinho Vazio</a></p>
                  </div>
                  <div class="clearfix"> </div>
                  <!----->
                  <!---pop-up-box---->					  
                  <link href="{{asset('public/theme/css/popuo-box.css')}}" rel="stylesheet" type="text/css" media="all"/>
                  <script src="{{asset('public/theme/js/jquery.magnific-popup.js')}}" type="text/javascript"></script>
                  <!---//pop-up-box---->
                  <div id="small-dialog" class="mfp-hide">
                     <div class="search-top">
                        <div class="login-search">
                           <input type="submit" value="">
                           <input type="text" value="Search.." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search..';}">		
                        </div>
                        <p>Shopin</p>
                     </div>
                  </div>
                  <script>
                     $(document).ready(function() {
                     $('.popup-with-zoom-anim').magnificPopup({
                     type: 'inline',
                     fixedContentPos: false,
                     fixedBgPos: true,
                     overflowY: 'auto',
                     closeBtnInside: true,
                     preloader: false,
                     midClick: true,
                     removalDelay: 300,
                     mainClass: 'my-mfp-zoom-in'
                     });
                     																			
                     });
                  </script>		
                  <!----->
               </div>
               <div class="clearfix"></div>
            </div>
         </div>
      </div>
      @yield("content")

      <!--brand-->
      <div class="brand">
         <div class="col-md-3 brand-grid">
            <img src="{{asset('public/theme/images/ic.png')}}" class="img-responsive" alt="">
         </div>
         <div class="col-md-3 brand-grid">
            <img src="{{asset('public/theme/images/ic1.png')}}" class="img-responsive" alt="">
         </div>
         <div class="col-md-3 brand-grid">
            <img src="{{asset('public/theme/images/ic2.png')}}" class="img-responsive" alt="">
         </div>
         <div class="col-md-3 brand-grid">
            <img src="{{asset('public/theme/images/ic3.png')}}" class="img-responsive" alt="">
         </div>
         <div class="clearfix"></div>
      </div>
      <!--//brand-->
      <!--//footer-->
      <div class="footer">
         <div class="footer-middle">
            <div class="container">
               <div class="col-md-3 footer-middle-in">
                  <a href="{{asset(route('frontend.index'))}}"><img src="{{asset('public/theme/images/log.png')}}" alt=""></a>
                  <p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor lorem.</p>
               </div>
               <div class="col-md-3 footer-middle-in">
                  <h6>Tags</h6>
                  <ul class="tag-in">
                     <li><a href="#">Lorem</a></li>
                     <li><a href="#">Sed</a></li>
                     <li><a href="#">Ipsum</a></li>
                     <li><a href="#">Contrary</a></li>
                     <li><a href="#">Chunk</a></li>
                     <li><a href="#">Amet</a></li>
                     <li><a href="#">Omnis</a></li>
                  </ul>
               </div>
               <div class="col-md-3 footer-middle-in">
                  <h6>Newsletter</h6>
                  <span>Sign up for News Letter</span>
                  <form>
                     <input type="text" value="Enter your E-mail" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Enter your E-mail';}">
                     <input type="submit" value="Subscribe">	
                  </form>
               </div>
               <div class="clearfix"> </div>
            </div>
         </div>
         <div class="footer-bottom">
            <div class="container">
               <ul class="footer-bottom-top">
                  <li><a href="#"><img src="{{asset('public/theme/images/f1.png')}}" class="img-responsive" alt=""></a></li>
                  <li><a href="#"><img src="{{asset('public/theme/images/f2.png')}}" class="img-responsive" alt=""></a></li>
                  <li><a href="#"><img src="{{asset('public/theme/images/f3.png')}}" class="img-responsive" alt=""></a></li>
               </ul>
               <p class="footer-class">&copy; 2016 Shopin. All Rights Reserved</p>
               <div class="clearfix"> </div>
            </div>
         </div>
      </div>
      <!--//footer-->
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="{{asset('public/theme/js/imagezoom.js')}}"> </script>
      <script src="{{asset('public/theme/js/jquery.flexslider.js')}}"> </script>
      <link rel="stylesheet" href="{{asset('public/theme/css/flexslider.css')}}" type="text/css" media="screen">
      <script src="{{asset('public/theme/js/simpleCart.min.js')}}"> </script>
      <!-- slide -->
      <script src="{{asset('public/theme/js/bootstrap.min.js')}}"></script>
      <!--light-box-files -->
      <script src="{{asset('public/theme/js/jquery.chocolat.js')}}"></script>
      <link rel="stylesheet" href="{{asset('public/theme/css/chocolat.css')}}" type="text/css" media="screen" charset="utf-8">
      <!--light-box-files -->
      <script type="text/javascript" charset="utf-8">
         $(function() {
         	$('a.picture').Chocolat();
         });
      </script>
   </body>
</html>