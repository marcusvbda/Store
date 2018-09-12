@extends('templates.default')
@section('title', 'Erro interno')
@section('body')

<body>
    
    <div class="content">
        <div class="text-center">
            <h1 style="font-size:2000%;margin-bottom:0px;">500</h1>     
            <h1>Erro de execução interna !!!</h1>       
            <span>{{$message}}</span> <hr>  
            <button class="btn btn-primary" onclick="javascript:window.history.go(-1);">Voltar</button>
        </div>
    </div>

</body>

@stop
