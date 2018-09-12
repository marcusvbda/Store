@extends('templates.admin')
@section('title', 'Dashboard')
@section('content')
<ul class="breadcrumb" style="margin-bottom:20px;">
  <li><a href="{{route('dashboard')}}">Início</a><span class="divider"></span></li>
  <li class="active">Dashboard</li>
</ul>

<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Exemplo de notificação<a href="#" class="alert-link"> Alert Link</a>.
</div>
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Exemplo de notificação<a href="#" class="alert-link"> Alert Link</a>.
</div>
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Exemplo de notificação<a href="#" class="alert-link"> Alert Link</a>.
</div>
@stop