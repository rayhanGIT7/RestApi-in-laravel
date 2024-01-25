<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
public function showUser($id=null){
  if($id==''){
    $user=User::get();
    return response()->json(['users'=>$user],200);
  }else{
    $user=User::find($id);
    return response()->json(['users'=>$user],200);
  }
}

public function addUser(Request $request){

    if($request->isMethod('post')){
        $data=$request->all();
        // return $data;

        $user= new User();
        $user->name=$data['name'];
        $user->email=$data['email'];
        $user->password=bcrypt($data['password']);

        $user->save();

        $massage='User successfully Insert';

        return response()->json(['massage'=>$massage],200);

    }
}

public function addMultipleUser(Request $request){

    if($request->isMethod('post')){
        $data = $request->all();

        foreach($data['users'] as $adduser){
            $user = new User();
            $user->name = $adduser['name'];
            $user->email = $adduser['email'];
            $user->password = bcrypt($adduser['password']);

            $user->save();
        }

        $message = 'Users successfully inserted';

        return response()->json(['message' => $message], 200);
    }
}

// user update

public function userUpdate(Request $request, $id){
    if($request->isMethod('put')){
        $data = $request->all();

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $user->name = $data['name'];
        $user->email = $data['email'];

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        $message = 'User successfully updated';

        return response()->json(['message' => $message, 'user' => $user], 200);
    }
}

// single user delete and Authorization

public function userDelete(Request $request,$id){
    $header=$request->header('Authorization');
    if($header==''){
        $message = 'Authorization is required';

    return response()->json(['message' => $message], 200);
    }else{
        if($header=='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6InJheWhhbiIsImlhdCI6MTUxNjIzOTAyMn0.d3MZlp1nspBm78p5Q6VnZYZe54sAtTSLIGZHt9Xcnfc'){
            $user = User::find($id)->delete();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $message = 'User successfully deleted';

            return response()->json(['message' => $message], 200);
        }else{
            $message = 'Authorization did not match';

    return response()->json(['message' => $message, 'user' => $user], 200);
        }
    }

}

// Multiple user delete

public function userMultipleDelete(Request $request, $ids){
    $ids = explode(',', $ids);

    $users = User::whereIn('id', $ids)->get();

    // Check if all IDs exist
    $existingIds = $users->pluck('id')->toArray();
    $missingIds = array_diff($ids, $existingIds);

    if (!empty($missingIds)) {
        return response()->json(['message' => 'Users with IDs ' . implode(', ', $missingIds) . ' not found'], 404);
    }

    // Delete users with existing IDs
    User::whereIn('id', $existingIds)->delete();

    $message = 'Users with IDs ' . implode(', ', $existingIds) . ' successfully deleted';

    return response()->json(['message' => $message, 'users' => $users], 200);
}


}
