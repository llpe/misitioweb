<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;
use App\Producto;

class ProductosController extends Controller
{
    public function index()
    {
        return view('productos'); 
    }
    public function imprimir()
    {
        
    }

    public function all(Request $request)
    {
        $productos = \DB::table('productos')
                     ->select('productos.*')
                     ->orderBy('stock','ASC')
                     ->take(10)
                     ->get();
        return response(json_encode($productos),200)->header('Content-type','text/plain');
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            'nombre'=>'required|min:3|max:20',
            'img'=>'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'stock'=>'required',
            'codigo'=>'required'
        ]);
        if($validator->fails()){
            return back()
             ->withInput()
             ->with('ErrorInsert','Favor de llenar todos los campos')
             ->withErrors($validator);
        }else{
            $imagen = $request-> file('img');
            $nombre = time().'.'.$imagen->getClientOriginalExtension();
            $destino = public_path('img/productos');
            $request->img->move($destino, $nombre);
            $red = Image::make($destino.'/'.$nombre);
            $red->resize(200, null, function($constraint){
                $constraint->aspectRatio();
            });
            $red->save($destino.'/thumbs/'.$nombre);
            $marca_agua = Image::make($destino.'/'.$nombre);
            $logo = Image::make(public_path('img/logo.png'));
            $marca_agua->insert($logo, 'botton-right',10,10);
            $marca_agua->save();
            $producto = Producto::create([
                'nombre'=>$request->nombre,
                'img'=>$nombre,
                'stock'=>$request->stock,
                'codigo'=>$request->codigo,
            ]);

            return back()->with('Listo', 'Se ha insertado correctamente');
        }
        
    }
}
