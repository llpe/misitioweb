<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\user;
use Hash;
use Auth;

class UsersController extends Controller
{
    public function __construct(){$this->middleware('auth');}
    public function index()
    {
        $usuarios = \DB::table('users')
                     ->select('users.*')
                     ->orderBy('id','DESC')
                     ->get();

        if(Auth::user()->nivel !='admin'){ return redirect('/admin');}             
        
        return view('usuarios')->with('usuarios',$usuarios);
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(),[
            'nombre'=>'required|min:3|max:20',
            'email'=>'required|min:3|email',
            'pass1'=>'required|min:3|required_with:pass2|same:pass2',
            'pass2'=>'required|min:3'
        ]);
        if($validator->fails()){
            return back()
             ->withInput()
             ->with('ErrorInsert','Favor de llenar todos los campos')
             ->withErrors($validator);
        }else{
            $usuario = User::create([
                'name'=>$request->nombre,
                'img'=>'default.jpg',
                'nivel'=>'usurio',
                'email'=>$request->email,
                'password'=>Hash::make($request->pass1),
            ]);
            return back()->with('Listo', 'Se ha insertado correctamente');
        }
        
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user->img != 'default.jpg'){
            if(files_exists(public_path('users/'.$user->img))){
                unlink(public_path('users/'.$user->img));
            }
        }
        $user->delete();
        return back()->with('Listo','El registro se elimino correctamente');
    }

    public function editarUsuario(Request $request)
    {
        $user = User::find($request->id);
        $validator  = Validator::make($request->all(),[
            'nombre'=>'required|min:3|max:20',
            'email'=>'required|min:3|email',
        ]);
        if($validator->fails()){
            return back()
             ->withInput()
             ->with('ErrorInsert','Favor de llenar todos los campos')
             ->withErrors($validator);
        }else{
            $user->name = $request->nombre;
            $user->email = $request->email;
            $validator2  = Validator::make($request->all(),[
                'pass1'=>'required|min:3|required_with:pass2|same:pass2',
                'pass2'=>'required|min:3'
            ]);
            if(!$validator2->fails()){
                $user->password = Hash::make($request->pass1);
            }

            $user->save();
            return back()->with('Listo','El registro se actualizo correctamente');
        }
    }
}
