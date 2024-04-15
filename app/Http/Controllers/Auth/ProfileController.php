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

class ProfileController extends Controller
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
    return view('auth.profile',compact('user'));
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
}
