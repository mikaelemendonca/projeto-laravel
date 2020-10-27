<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Produtos;

class ProdutosController extends Controller
{
    public function index() {
        //$produtos = Produtos::all();
        $produtos = Produtos::paginate(4);
        $maisCaro = Produtos::max('preco');
        $maisBarato = Produtos::min('preco');
        $media = Produtos::all()->avg('preco');
        $soma = Produtos::all()->sum('preco');
        $contagem = Produtos::all()->count('preco');
        $maiorDez = Produtos::where('preco','>',10)->count();

        return view('produtos.index', array('produtos' => $produtos, 'buscar' => null, 
            'ordem' => null, 'maisCaro' => $maisCaro, 'maisBarato' => $maisBarato,
            'media' => $media, 'soma' => $soma, 'contagem' => $contagem, 'maiorDez' => $maiorDez));
    }

    public function show($id) {
        $produto = Produtos::with('mostrarComentarios')->find($id);
        return view('produtos.show', array('produto' => $produto));
    }

    public function edit($id) {
        if (Auth::check()) {
            $produto = Produtos::find($id);
            return view('produtos.edit', compact('produto', 'id'));
        } else {
            return redirect('login');
        }
    }

    public function create() {
        if (Auth::check()) {
            return view('produtos.create');
        } else {
            return redirect('login');
        }
    }

    public function store(Request $request) {
        
        $this->validate($request, [
            'sku' => 'required|unique:produtos|min:3',
            'titulo' => 'required|min:3',
            'descricao' => 'required|min:10',
            'preco' => 'required|numeric'
        ]);
        
        $produto = new Produtos();
        $produto->sku = $request->input('sku');
        $produto->titulo = $request->input('titulo');
        $produto->descricao = $request->input('descricao');
        $produto->preco = $request->input('preco');
        
        if ($produto->save()) {
            // cria uma secao de success
            return redirect('produtos/create')->with('success', 'Produto Cadastrado Sucesso!');
        }
    }

    public function update(Request $request, $id) {
        
        $produto = Produtos::find($id);

        $this->validate($request, [
            'sku' => 'required|min:3',
            'titulo' => 'required|min:3',
            'descricao' => 'required|min:10',
            'preco' => 'required|numeric'
        ]);

        if ($request->hasFile('imgproduto')) {
            $imagem = $request->file('imgproduto');
            $nomearq = md5($id).".".$imagem->getClientOriginalExtension();
            $request->file('imgproduto')->move(public_path('./img/produtos/'), $nomearq);
        }
        
        $produto->sku = $request->get('sku');
        $produto->titulo = $request->get('titulo');
        $produto->descricao = $request->get('descricao');
        $produto->preco = $request->get('preco');

        if ($produto->save()) {
            // cria uma secao de success
            return redirect('produtos/'.$id.'/edit')->with('success', 'Produto Atualizado Sucesso!');
        }
    }

    public function destroy($id) {
        $produto = Produtos::find($id);
        if (file_exists("./img/produtos/".md5($produto->id).".jpg")) {
            unlink("./img/produtos/".md5($produto->id).".jpg");
        }
        $produto->delete();
        return redirect()->back()->with('success', 'Produto Deletado com Sucesso!!');
    }

    public function busca(Request $request) {
        $ordemInput = $request->input('busca');
        $produtos = Produtos::where('titulo', 'LIKE', '%'.$ordemInput)
            ->orwhere('descricao', 'LIKE', '%'.$buscaInput)
            ->paginate(4);
            //->get();
        return view('produtos.index', array('produtos' => $produtos, 'buscar' => $request->input('busca'), 'ordem' => null));
    }

    public function ordem(Request $request) {
        $ordemInput = $request->input('ordem');
        switch($ordemInput) {
            case 1:
                $campo = 'titulo';
                $tipo = 'asc';
                break;
            case 2:
                $campo = 'titulo';
                $tipo = 'desc';
                break;
            case 3:
                $campo = 'preco';
                $tipo = 'desc';
                break;
            case 4:
                $campo = 'preco';
                $tipo = 'asc';
                break;
        }
        $produtos = Produtos::orderBy($campo, $tipo)->paginate(4);
        
        return view('produtos.index', array('produtos' => $produtos, 'buscar' => null, 'ordem' => $ordemInput,
            'maisCaro' => null, 'maisBarato' => null, 'media' => null, 'soma' => null, 
            'contagem' => null, 'maiorDez' => null));
    }
}
