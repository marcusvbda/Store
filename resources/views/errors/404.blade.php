@extends('templates.default')
@section('title', 'Página não encontrada')
@section('body')

<body>
    
    <div class="content">
        <div class="text-center">
            <h1 style="font-size:2000%;margin-bottom:0px;">404</h1>     
            <h1>Página não encontrada !!!</h1>       
            <button class="btn btn-primary" onclick="javascript:window.history.go(-1);">Voltar</button>
        </div>
    </div>

</body>

@stop
