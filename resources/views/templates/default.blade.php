<!DOCTYPE html>
<html lang="ptbr" >
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/v-libs/v-crud/css/dttable.bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/v-libs/v-crud/css/vcrud.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/v-libs/v-crud/css/scroller.css')}}">
    <link href="{{asset('public/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/js/v-libs/v-loading/css/vloading.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/footer.css')}}" rel="stylesheet">
    <link href="{{asset('public/css/site.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/jquery.ui.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/dropzone/basic.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/dropzone/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/toast/toast.css')}}">
    <link rel="icon" type="image/png" href="{{asset('public/img/logo.png')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('public/js/summernote/summernote-bs4.css')}}"/>

    
  </head>
    <script src="{{asset('public/js/jquery/jquery.min.js')}}"></script> 
    <script src="{{asset('public/js/jquery/jquery-ui.min.js')}}"></script>

    <script src="{{asset('public/js/vuejs/vue.min.js')}}"></script>
    <script src="{{asset('public/js/vuejs/v-mask.min.js')}}"></script>
    <script src="{{asset('public/js/parsley/parsley.min.js')}}"></script>
    <script src="{{asset('public/js/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('public/js/v-libs/v-loading/js/vloading.js')}}"></script>
    <script src="{{asset('public/js/v-libs/v-form/vform.js')}}"></script>
    <script src="{{asset('public/js/v-libs/v-fkSelect/vfkselect.js')}}"></script>    
    <script src="{{asset('public/js/v-libs/v-crud/js/vcrud.js')}}"></script>    
    <script src="{{asset('public/js/v-libs/v-crud/js/dttable.js')}}"></script>   
    <script src="{{asset('public/js/v-libs/v-crud/js/scroller.js')}}"></script>    
    <script src="{{asset('public/js/v-libs/v-crud/js/dttable.bootstrap.js')}}"></script>    
    <script src="{{asset('public/js/core.js')}}"></script>
    <script src="{{asset('public/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('public/js/dropzone/dropzone.js')}}"></script>
    <link rel="stylesheet" href="{{asset('public/js/multipleSelect/bootstrap-select.min.css')}}">
    <script src="{{asset('public/js/multipleSelect/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('public/js/chartjs/Chart.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/js/summernote/summernote-bs4.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/js/summernote/summernote-ext-beagle.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/toast/toast.js')}}" type="text/javascript"></script>
    <script src="{{asset('public/js/jspdf/jspdf.js')}}"></script>
    <script src="{{asset('public/js/jspdf/jspdf.autotable.js')}}"></script>


    <script>
    toastr.options.timeOut = 3000; 
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    vform.ajax("post",'{{route("parametros.setSession")}}',{nome:"timezone",valor:Intl.DateTimeFormat().resolvedOptions().timeZone}, function(response){},false);
    Vue.use(VueMask.VueMaskPlugin);
</script>
@yield('body')   
    
</html>