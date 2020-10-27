<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class ContatoController extends Controller
{
    public function index() {
        $data['titulo'] = "My firist page";
        return view('contato', $data);
    }

    public function enviar(Request $request) {
        $dadosEmail = array(
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'assunto' => $request->input('assunto'),
            'msg' => $request->input('msg'),
        );
        Mail::send('email.contato', $dadosEmail, function($message) {
            $message->from('mikaelemendonca@gmail.com', 'formulario de contato');
            $message->subject('Mensagem do form de contato');
            $message->to('mikaelemendonca@gmail.com');
        });        
        return redirect('contato')->with('success', 'Mensagem enviada, em breve entraremos em contato!');
    }
}
