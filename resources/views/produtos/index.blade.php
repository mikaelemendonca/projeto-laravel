@extends('layouts.app')
@section('title', 'Lista de Produtos')
@section('content')
    <h1>Produtos</h1>
    @if ($message = Session::get('success'))
		<div class="alert alert-success">
			{{$message}}
		</div>
	@endif
    <div class="row">
        <div class="col-md-12">
        <form method="POST" action="{{url('produtos/busca')}}">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="busca" name="busca" value="{{$buscar}}"
                    placeholder="Procurar produto . . .">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary">Buscar</button>
                </div>
            </div>
        </form>
        <form method="POST" action="{{url('produtos/ordem')}}">
            @csrf
            <div class="input-group mb-3">
                <select name="ordem" id="ordem" class="form-control">
                    <option>Escolha a Ordem</option>
                    <option value="1" @if ($ordem == 1) selected @endif>Titutlo (A-Z)</option>
                    <option value="2" @if ($ordem == 2) selected @endif>Titutlo (Z-A)</option>
                    <option value="3" @if ($ordem == 3) selected @endif>Valor (Maior-Menor)</option>
                    <option value="4" @if ($ordem == 4) selected @endif>Valor (Menor-Maior)</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary">Buscar</button>
                </div>
            </div>
        </form>    
        </div>
    </div>
    <div class="row">
        @foreach ($produtos as $produto)
        <div class="col-md-3">
            @if (file_exists("./img/produtos/".md5($produto->id).".jpg"))
            <img src="{{url('img/produtos/'.md5($produto->id).'.jpg')}}" alt="Imagem Produto" class="img-fluid img-thumbnail">
            @endif
            <h4 class="text-center"><a href="{{URL::to('produtos')}}/{{$produto->id}}">{{$produto->titulo}}</a>
                @if ($produto->preco == $maisCaro)
                <span class="badge badge-danger">Maior Preço</span>
                @endif
                @if ($produto->preco == $maisBarato)
                <span class="badge badge-success">Menor Preço</span>
                @endif
            </h4>
            <p class="text-center">R$ {{number_format($produto->preco,2,',','.')}}</p>
            @if (Auth::check())
            <div class="mb-3">
                <form method="POST" action="{{action('ProdutosController@destroy', $produto->id)}}">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <a href="{{URL::to('produtos/'.$produto->id.'/edit')}}" class="btn btn-primary">Editar</a>
                    <button class="btn btn-danger">Excluir</button>
                </form>
            </div>
            @endif
        </div>        
        @endforeach
        <div class="row">
            <div class="col-md-12">
                {{$produtos->links()}}
                @if ($media != null)
                <p><strong>O valor médio dos produtos é </strong>R$ {{number_format($media,2,',','.')}}</p>
                @endif
                @if ($soma != null)
                <p><strong>O valor total dos produtos é </strong>R$ {{number_format($soma,2,',','.')}}</p>
                @endif
                @if ($contagem != null)
                <p><strong>A quantidade total dos produtos é </strong>{{$contagem}}</p>
                @endif
                @if ($maiorDez != null)
                <p><strong>A quantidade com valor que maior que R$ 10,00 é </strong>{{$maiorDez}}</p>
                @endif
            </div>
        </div>
    </div>
@endsection