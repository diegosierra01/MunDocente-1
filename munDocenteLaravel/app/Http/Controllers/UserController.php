c<?php

namespace MunDocente\Http\Controllers;

use Illuminate\Http\Request;

use MunDocente\Http\Requests;
use MunDocente\Http\Controllers\Controller;
use MunDocente\AcademicInstitution;
use MunDocente\User;
use MunDocente\Area;
use DB;
use Session;
use Auth;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create_docent(){
        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();
        $areas = Area::all();
        return view('user.create_docent', compact('academic_institutions','areas'));
    }

    public function create_publisher(){
        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();
        return view('user.create_publisher', compact('academic_institutions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();
        $areas = Area::all();
        return view('user.create', compact('academic_institutions','areas'));
    }

    public function forget(){

        return view('auth.reset');

    }

    public function desactived_me(){
        $id = Auth::user()->id;
        DB::table('users')
                    ->where('id', '=', $id)
                    ->update([
                        'activedMe' => false                
                        ]);
        $user = User::where('id', '=', $id)
                    ->get();
        //dd($user);
        Session::flash('flash_message', 'Cuenta desactivada sin problemas.');
        return $this->edit($id);
    }

    public function actived_admin($id){
      DB::table('users')
          ->where('id', '=', $id)
          ->update([
                'activedAdmin' => true                
              ]);
      $user = User::where('id', '=', $id)
                    ->first();
      //enviar mensaje al publicdor de que ya fue activado
      Mail::send('emails.actived_admin', ['text' => 'Tu usuario de MunDocente ha sido activado por parte del adminsitrador, para que hagas uso pleno de tu cuenta'], function($msj) use ($user){
            $msj->subject('Activación de tu cuenta: '.$user->username);
            $msj->to($user->email);
      });
        return view('user.state_count', compact('user'));   
     }

    public function desactived_admin($id){
        DB::table('users')
          ->where('id', '=', $id)
          ->update([
                'activedAdmin' => false                
              ]);
      $user = User::where('id', '=', $id)
                    ->first();
      Mail::send('emails.actived_admin', ['text' => 'Tu usuario de MunDocente ha sido desactivado, para más información comunicate con el administrador'], function($msj) use ($user){
            $msj->subject('Desactivación de tu cuenta: '.$user->username);
            $msj->to($user->email);
      });
        return view('user.state_count', compact('user'));
    }

    public function actived_me(){
        $id = Auth::user()->id;
        DB::table('users')
                    ->where('id', '=', $id)
                    ->update([
                        'activedMe' => true                
                        ]);
        $user = User::where('id', '=', $id)
                    ->get();
        //dd($user);
        Session::flash('flash_message', 'Cuenta activada correctamente.');
        return $this->edit($id);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        //dd($user);
        $areas = Area::all();
        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();
        //dd($user);                                                  
        return view('user.read_user', compact('user','areas','academic_institutions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('academicInstitution')
                    ->where('id','=',$id)
                    ->get();

        $areas = Area::all();

        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();
        foreach ($user as $key) {
            $typeUser = $key->type;
        }
        if($typeUser != 3){
          foreach ($user as $key) {
              $idUser = $key->id;
          }
          $areasUser = DB::table('area_user')
                      ->where('user_id', '=', $idUser)
                      ->select('area_id')
                      ->get();
          $cont = 0;
                foreach ($areasUser as $area) {
                    $areasUser[$cont] = Area::where('id', '=', $area->area_id)
                                        ->select('name')
                                        ->get();
                    $cont += 1;
                }
                
               
            $cont = 0;
            foreach ($areasUser as $collection) {
                    
                    foreach ($collection as $array) {
                        $name[$cont] = $array->name;
                        $cont += 1;
                    }
               }  
          return view('user.edit', compact('user','typeUser', 'areas', 'academic_institutions', 'name'));
        }else {
          return view('errors.edit_admin');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::with('academicInstitution')
                    ->where('id','=',$id)
                    ->get();
        //dd($request);
        //$this->authorize('owner', $user);
        $this->validate($request, [
            'fullname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'academic_institution' => 'required',
            'photo' => 'mimes:jpg,jpeg,png|max:100', //kb
            ]);
        $academic_institution = AcademicInstitution::where('name', '=', $request->input('academic_institution'))
                                                    ->select('id', 'name')
                                                    ->get();
        foreach ($academic_institution as $key) {
            $valueId = $key->id;
        }
        
        
        $this->updateData($request, $id, $valueId);
        

        $user = User::with('academicInstitution','areas')
                    ->where('id','=',$id)
                    ->get();
        foreach ($user as $key) {
                $userOne = $key;
        }

        $typeUser = $userOne->type;
        if($typeUser == 1){
             $userOne->areas()->detach(); 
           $areas = $request->input('area'); 
            $areas = $this->getAreasSelected($areas);
            //dd($areas);//arreglo de areas seleccionadas en formato de numeros
            foreach ($areas as $area) {
                $userOne->areas()->attach($area); 
            }
        }

         $areasUser = DB::table('area_user')
                            ->where('user_id', '=', $userOne->id)
                            ->select('area_id')
                            ->get();
                $cont = 0;
                foreach ($areasUser as $area) {
                    $areasUser[$cont] = Area::where('id', '=', $area->area_id)
                                        ->select('name')
                                        ->get();
                    $cont += 1;
                }
                
               
                $cont = 0;
                foreach ($areasUser as $collection) {
                    
                    foreach ($collection as $array) {
                        $name[$cont] = $array->name;
                        $cont += 1;
                    }
               }          
               // dd($name);
        

         $areas = Area::all();
        $academic_institutions = AcademicInstitution::orderBy('name', 'asc')
                                                    ->get();

        Session::flash('flash_message', 'Usuario actualizado correctamente');
        return view('user.edit', compact('user', 'typeUser', 'areas', 'name','academic_institutions'));
    }

       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getAreasSelected($areas){   
       $cont = 0;
        foreach ($areas as $areaId ) {
        if(is_numeric($areaId)){
            $idArea = Area::where('id', '=', $areaId)
                            ->select('id')
                            ->get();
            foreach ($idArea as $key2) {
                    $valueArea = $key2->id;             
            }
            //dd($valueArea);
            $selectedArea[$cont] = $valueArea;
            $cont += 1;
          }
         else {
            $idArea = Area::where('name', '=', $areaId)
                            ->select('id', 'name')
                            ->get();
            foreach ($idArea as $key2) {
                    $valueArea = $key2->id;             
            }
            $selectedArea[$cont] = $valueArea;
            $cont += 1;      
         //   dd($areas[1]); //Arreglos de areas seleccionadas en numeros :D
             }
        }           
        return $selectedArea;
      }

      private function updateData($request, $id, $valueId){

        if(!(is_null($request->file('photo')))){

          //foto del usuario
          $photo = $request->file('photo');
          //$upload = 'uploads/photo/'.$id;
          $upload = 'uploads/photo/'.$id;
          $file_name = $photo->getClientOriginalName();
          $success = $photo->move($upload, $file_name);

           DB::table('users')
                    ->where('id', '=', $id)
                    ->update([
                        'fullname' => $request->input('fullname'),
                        'email' => $request->input('email'),
                        'academic_institution' => $valueId,
                        'phone' => $request->input('phone'),
                        'contact' => $request->input('contact'),
                        'photo' => $file_name
                        ]);
        } else {
           DB::table('users')
                    ->where('id', '=', $id)
                    ->update([
                        'fullname' => $request->input('fullname'),
                        'email' => $request->input('email'),
                        'academic_institution' => $valueId,
                        'phone' => $request->input('phone'),
                        'contact' => $request->input('contact')
                        ]);
        }  
    }
}
