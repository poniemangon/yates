<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

//libraries
use Hash;
use Session;

//models
use App\Models\UsersModel;

class UserController extends Controller {

    public function login() {

 
    

        if (Session::has('loggedUser')) {
            return redirect('ssy-administration/users-list');
        }
    
        $title = 'Users | Login | SSY';
        return view('backend.users.login', compact('title'));
    }
    
    
    public function list(Request $request) {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }
        
        $title = 'Users | List | SSY';

        $totalUsers = UsersModel::count();

        $users = collect();
        $filterSource = false;

        if ($request->has('source') && $request->input('source') != '') {
            $filterSource = $request->input('source');
        }

        $users = UsersModel::when($filterSource, function($query, $filterSource) {
            return $query->where('first_name', 'LIKE', '%'.$filterSource.'%')
            ->orWhere('last_name', 'LIKE', '%'.$filterSource.'%')
            ->orWhere('email', 'LIKE', '%'.$filterSource.'%');
        })->groupBy('user_id')->orderBy('user_id', 'desc');

        $users = $users->get()->all();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 50;
        $currentItems = array_slice($users, $perPage * ($currentPage - 1), $perPage);
        $paginator = new LengthAwarePaginator($currentItems, count($users), $perPage, $currentPage);

        $paginator->setPath(url('/') . '/ssy-administration/users-list');
        $users = $paginator;


        $filtersParameters = array(
            'source' => $filterSource
        );

        $scripts = array('users.js');

        return view('backend.users.list', compact('title', 'totalUsers', 'users', 'filtersParameters', 'scripts'));
    }

    public function register() {

    	if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Users | Register | SSY';

    	$scripts = array('users.js');

    	return view('backend.users.register', compact('title', 'scripts'));
    }

    public function edit($userId) {

        if (!Session::has('loggedUser')) {
            return Redirect('ssy-administration');
        }

    	$title = 'Users | Edition | SSY';

    	$userData = UsersModel::where('user_id', $userId)->first();

    	if (!$userData) {
    		return redirect('ssy-administration/users-list');
    	}

    	$scripts = array('users.js');

        return view('backend.users.edit', compact('title', 'userData', 'scripts'));
    }

    public function loginUser(Request $request) {

    	$messages = [
            'email.required' => 'Email is required to access the system',
            'password.required' => 'Password is required to access the system'
    	];

        $validations = $request->validate([
            'password' => 'required',
            'email' => 'required'
        ], $messages);

        $email = $request->input('email');
        $password = $request->input('password');

        $verifyLogin = UsersModel::where('email', $email)->first();

        if (!$verifyLogin) {
            return Response()->json([
                'success' => false,
                'message' => 'Introduced email is invalid'
            ]);
        } else {

            $checkPassword = Hash::check($password, $verifyLogin->password);

            if (!$checkPassword) {
                return Response()->json([
                    'success' => false,
                    'message' => 'Introduced password is invalid'
                ]);
            } else {
                
                $userData = array(
                    'user_id' => $verifyLogin->user_id,
                    'first_name' => $verifyLogin->first_name,
                    'last_name' => $verifyLogin->last_name,
                    'email' => $verifyLogin->email,
                );

                Session::put('loggedUser', $userData);

                return Response()->json([
                    'success' => true
                ]);
            }
        }
    }

    public function registerUser(Request $request) {
         
        $messages = [
            'first_name.required' => 'Name of the user is required',
            'first_name.max' => 'Name of the user must contain less than 20 characters',
            'first_name.min' => 'Name of the user must contain at least 3 characters',
            'last_name.required' => 'Surname of the user is required',
            'last_name.max' => 'Surname of the user must contain less than 20 characters',
            'last_name.min' => 'Surname of the user must contain at least 3 characters',
            'email.required' => 'Email of the user is required',
            'email.max' => 'Email of the user must contain less than 60 characters',
            'email.email' => 'Email of the user is invalid',
            'email.unique' => 'Email of the user is already in use',
            'password.required' => 'Password of the user is required',
            'password.min' => 'Password of the user must contain at least 5 characters',
            'repeatPassword.required' => 'Repeat password of the user is required',
            'repeatPassword.min' => 'Repeat password of the user must contain at least 5 characters'
        ];

        $validations = $request->validate([
            'first_name' => 'required|max:20|min:3',
            'last_name' => 'required|max:20|min:3',
            'email' => 'required|max:60|email|unique:ssy_users',
            'password' => 'required|min:5',
            'repeatPassword' => 'required|min:5'
        ], $messages);

        
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $repeatPassword = $request->input('repeatPassword');

        if ($password !== $repeatPassword) {
        	return Response()->json([
                'success' => false,
                'message' => 'Password do not match'
        	]);
        }

        $usersModel = new UsersModel();
        $usersModel->first_name = $firstName;
        $usersModel->last_name = $lastName;
        $usersModel->email = $email;
        $usersModel->password = Hash::make($password);
        $usersModel->registration_date = date('Y-m-d');
        $usersModel->save();
        $userId = $usersModel->user_id;

        return Response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'userId' => $userId
        ]);
    }

    public function editUser($userId, Request $request) {
         
        $messages = [
            'first_name.required' => 'Name of the user is required',
            'first_name.max' => 'Name of the user must contain less than 20 characters',
            'first_name.min' => 'Name of the user must contain at least 3 characters',
            'last_name.required' => 'Surname of the user is required',
            'last_name.max' => 'Surname of the user must contain less than 20 characters',
            'last_name.min' => 'Surname of the user must contain at least 3 characters',
            'email.required' => 'Email of the user is required',
            'email.max' => 'Email of the user must contain less than 60 characters',
            'email.email' => 'Email of the user is invalid',
            'email.unique' => 'Email of the user is already in use',
            'password.min' => 'Password of the user must contain at least 5 characters',
            'repeatPassword.min' => 'Repeat password of the user must contain at least 5 characters'
        ];

        $validations = $request->validate([
            'first_name' => 'required|max:20|min:3',
            'last_name' => 'required|max:20|min:3',
            'email' => 'required|max:60|email|unique:ssy_users,email,'.$userId.',user_id',
            'password' => 'nullable|min:5',
            'repeatPassword' => 'nullable|min:5'
        ], $messages);

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $repeatPassword = $request->input('repeatPassword');

        if ($password && $repeatPassword) {
	        if ($password !== $repeatPassword) {
	        	return Response()->json([
	                'success' => false,
	                'message' => 'Password do not match'
	        	]);
	        }
	    }

	    UsersModel::where('user_id', $userId)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email
	    ]);

	    if ($password && $repeatPassword) {
	    	UsersModel::where('user_id', $userId)->update([
	            'password' => Hash::make($password)
		    ]);
	    }

        return Response()->json([
            'success' => true,
            'message' => 'User edited successfully',
            'userId' => $userId
        ]);
    }

    public function deleteUser($userId, Request $request) {

    	if (!isset($userId)) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is invalid or inexistent'
            ]);
        }

        $userData = UsersModel::where('user_id', $userId)->first();

        if (!$userData) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is invalid or inexistent'
            ]);
        }

        if ($userData->user_id == Session('loggedUser')['user_id']) {
        	return response()->json([
                'success' => false,
                'message' => 'You cannot delete yourself from the system'
            ]);
        }

        UsersModel::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function logout() {
    	auth()->logout();
        Session::flush();
        return Redirect('ssy-administration');
    }
}