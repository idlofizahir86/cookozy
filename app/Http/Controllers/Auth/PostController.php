<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Auth\Request\UpdateUser;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
     /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    //
    $uid = Session::get('uid');
    $user = app('firebase.auth')->getUser($uid);
    return view('auth.post',compact('user'));
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $updatedUser = app('firebase.auth')->disableUser($id);
    Session::flush();
    return redirect('/login');
  }

    public function edit($id)
        {
            $baseUrl = '';

            if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
                // Lokal (Development)
                $baseUrl = 'http://localhost:8000';
            } else {
                // Produksi
                $baseUrl = 'https://cookozy.web.app'; // Ganti dengan URL produksi Anda
            }

            $url = "{$baseUrl}/api/recipes/{$id}";

            // Lakukan permintaan ke API untuk mendapatkan data resep berdasarkan ID
            $response = Http::get($url);
            $recipe = $response->json();

            return view('editPost', ['recipe' => $recipe]);
        }

        public function update(Request $request, $id)
        {
            // Logika validasi dan penyimpanan ke database
            return redirect()->route('post.edit', ['id' => $id])->with('success', 'Recipe updated successfully!');
        }
}
