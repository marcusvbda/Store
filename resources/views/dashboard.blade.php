@extends('templates.admin')
@section('title', 'Início')
@section('content')
<ul class="breadcrumb" style="margin-bottom:20px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Dashboard</li>
</ul>

<div id="app" style="display: none;">
	<div class="row">
	    <div class="col-md-6">
	        <h1 style="margin-top: 0px;">[[saudacao]] <strong>{{Auth::user()->nome}}</strong>.</h1>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-calendar"></i> hoje é dia [[diaCompleto]] e agora são [[horaCompleta]]
                </div>
                <div class="panel-body">
                  
                </div>
            </div>
    	</div>
	</div>
</div>
<script type="text/javascript">
	var app = new Vue( 
	{
		el: '#app',
		delimiters: ["[[","]]"],
		data:
		{
		    saudacao : null,
		    horaCompleta : null,
		    diaCompleto : null
		},
		methods: 
		{
			init : function()
			{
				$("#app").toggle(150);
			},
			updateClock : function()
			{
				var date = new Date();
				var hour = date.getHours();
				var min = date.getMinutes();
				var sec = date.getSeconds();
				var day = date.getDay();
				var month = date.getMonth();
				var year = date.getFullYear();
				this.diaCompleto =  String(Number(day).pad(2))+"/"+String(Number(month).pad(2))+"/"+String(Number(year));
				this.horaCompleta =  String(Number(hour).pad(2))+":"+String(Number(min).pad(2))+":"+String(Number(sec).pad(2));
				var _saudacao = "Olá";
				if((hour<12)&&(hour>5))
				{
					_saudacao = "Bom dia,";
				}
				else if((hour>=12)&&(hour<18))
				{
					_saudacao = "Boa tarde,";
				}
				else if((hour>=18)&&(hour<0))
				{
					_saudacao = "Boa noite,";
				}
				else if((hour>=0)&&(hour<5))
				{
					_saudacao = "Boa madrugada,";
				}
				if(_saudacao!=this.saudacao)
				{
					this.saudacao = _saudacao;
				}
			}
		}
	});
	app.updateClock();
	setInterval(function(){ app.updateClock() } , 1000);
	app.init();
</script>
@stop