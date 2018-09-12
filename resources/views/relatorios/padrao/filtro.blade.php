

    <div class="col-md-3" style="width:22%;">
        <div class="panel panel-default">
            <div class="panel-heading text-right" style="background-color:#515368;">
                <button type="button" class="btn btn-default btn-xs" v-on:click="filtrar()"><i class="glyphicon glyphicon-search"></i> Filtrar</button>
            </div>
            <div class="panel-body" style="padding-top: 5px;">

                <div class="row" style="padding-bottom:20px;height:100%;min-height:400px;max-height:400px;overflow:scroll;">
                    @foreach($dados["parametros"] as $parametro)
                        <div class="col-md-12" style="padding-bottom: 5px;">
                            <label>{{$parametro["label"]}}</label>
                            @if(isset($parametro["combo"]))
                                <select class="form form-control select"  name="{{$parametro['nome']}}" v-model="{{trim($parametro['nome'])}}" v-on:keyup.enter="filtrar()" > 
                                    <option></option>
                                    @foreach($parametro["combo"] as $dado)
                                        <option value="{{$dado['id']}}">{{$dado['nome']}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form form-control" @if(isset($parametro["tipo"])) type="{{$parametro["tipo"]}}" @endif name="{{trim($parametro['nome'])}}"  v-model="{{trim($parametro['nome'])}}" v-on:keyup.enter="filtrar()"  >
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
<!-- </div> -->