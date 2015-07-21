<?php

namespace CodeProject\Http\Controllers;

use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class TesteController extends Controller
{
    public function index($nome = "Leandro")
	{
		return "<html><head><title>Pagina1</title></head><body>Olá {$nome} </body></html>";
	}
}
