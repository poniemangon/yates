<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Hash;
use Session;

// Models
use App\Models\UserRolesModel;
use App\Models\UsersModel;
use App\Models\UserDocumentsCountriesModel;
use App\Models\CountriesModel;
use App\Models\BillsModel;

class FrontUserController extends Controller {

    // Show login page
    public function login() {
        if (Session::has('loggedUser')) {
            return Redirect('/');
        }

        $hasDarkNav = true;
        $scripts = array('users.js');
        $title = 'Users | Login | FSC';

        return view('frontend.pages.login', compact('title', 'hasDarkNav', 'scripts'));
    }

    // Show registration page
    public function register() {
        if (Session::has('loggedUser')) {
            return Redirect('/');
        }

        $title = 'Users | Register | FSC';
        $userRoles = UserRolesModel::orderBy('user_role_id', 'asc')->get();
        $userRoles->shift(); // Remove the first role
        $countries = CountriesModel::orderBy('country', 'asc')->get(); // Adjust the model and column name as needed
        $scripts = array('users.js');
        $hasDarkNav = true;

        return view('frontend.pages.register', compact('title', 'userRoles', 'countries', 'scripts', 'hasDarkNav'));
    }

    // Show edit user page
    public function edit($userId) {
        $title = 'Users | Edition | FSC';
        $userData = UsersModel::where('user_id', $userId)->first();

        if (!$userData) {
            return redirect('fsc-administration/users-list');
        }

        $userRoles = UserRolesModel::orderBy('user_role_id', 'asc')->get();
        $userRoles->shift(); // Remove the first role
        $scripts = array('users.js');

        return view('frontend.pages.edit', compact('title', 'userData', 'userRoles', 'scripts'));
    }

    // Handle user login
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
            return response()->json([
                'success' => false,
                'message' => 'Introduced email is invalid'
            ]);
        } else {
            $checkPassword = Hash::check($password, $verifyLogin->password);

            if (!$checkPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Introduced password is invalid'
                ]);
            } else {
                $userRole = UserRolesModel::where('user_role_id', $verifyLogin->user_role_id)->first();

                $userData = [
                    'user_id' => $verifyLogin->user_id,
                    'user_role_id' => $verifyLogin->user_role_id,
                    'user_role' => $userRole->user_role, 
                    'name' => $verifyLogin->name,
                    'surname' => $verifyLogin->surname,
                    'email' => $verifyLogin->email
                ];

                Session::put('loggedUser', $userData);

                return response()->json([
                    'success' => true
                ]);
            }
        }
    }

    public function registerUser(Request $request) {
        // Custom error messages
        $messages = [
            'role.required' => 'Role of the user is required',
            'name.required' => 'Name of the user is required',
            'name.max' => 'Name of the user must contain less than 20 characters',
            'name.min' => 'Name of the user must contain at least 3 characters',
            'surname.required' => 'Surname of the user is required',
            'surname.max' => 'Surname of the user must contain less than 20 characters',
            'surname.min' => 'Surname of the user must contain at least 3 characters',
            'email.required' => 'Email of the user is required',
            'email.max' => 'Email of the user must contain less than 60 characters',
            'email.email' => 'Email of the user is invalid',
            'email.unique' => 'Email of the user is already in use',
            'password.required' => 'Password of the user is required',
            'password.min' => 'Password of the user must contain at least 5 characters',
            'repeatPassword.required' => 'Repeat password of the user is required',
            'repeatPassword.min' => 'Repeat password of the user must contain at least 5 characters'
        ];
    
        // Input validations
        $request->validate([
            'role' => 'required',
            'name' => 'required|max:20|min:3',
            'surname' => 'required|max:20|min:3',
            'email' => 'required|max:60|email|unique:fsc_users',
            'password' => 'required|min:5',
            'repeatPassword' => 'required|min:5'
        ], $messages);
    
        // Retrieve input data
        $role = $request->input('role');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        $password = $request->input('password');
        $repeatPassword = $request->input('repeatPassword');
        $selectedCountries = $request->input('selected_countries');
    
        // Check if passwords match
        if ($password !== $repeatPassword) {
            return response()->json([
                'success' => false,
                'message' => 'Passwords do not match'
            ]);
        }
    
        // Create the user
        $usersModel = new UsersModel();
        $usersModel->user_role_id = $role;
        $usersModel->name = $name;
        $usersModel->surname = $surname;
        $usersModel->email = $email;
        $usersModel->password = Hash::make($password);
        $usersModel->registration_date = date('Y-m-d');
        $usersModel->save();
    
        // Fetch the price of the plan (role)
        $rolePrice = UserRolesModel::where('user_role_id', $role)->value('price'); // Assumes each role has a price in the database
        $totalPrice = $rolePrice;
    
        // Create the bill for the user (before handling countries)
        $billsModel = new BillsModel();
        $billsModel->price = $totalPrice; // Set the initial total price (based on the plan)
        $billsModel->user_id = $usersModel->user_id;
        $billsModel->payment_link = 'johndoe.com'; // Payment link should be dynamic
        $billsModel->payment_status = 0;
        $billsModel->plan_id = $role;
        $billsModel->save(); // Save the bill with the initial price
        Log::info('Bill created with bill_id: ' . $billsModel->bill_id);
    
        // Handle countries selected for the "some countries" plan (role id 2)
        if ($role == 2 && !empty($selectedCountries)) { // Only for users with "some countries" plan
            $selectedCountriesArray = json_decode($selectedCountries, true);
    
            if (is_array($selectedCountriesArray)) {
                foreach ($selectedCountriesArray as $country) {
                    if (isset($country['id'])) {
                        // Add the price of this country to the total
                        $countryPrice = CountriesModel::where('country_id', $country['id'])->value('price'); // Fetch the price of the country
                        $totalPrice += $countryPrice;
    
                        // Save the user-country association
                        $userDocumentsCountriesModel = new UserDocumentsCountriesModel();
                        $userDocumentsCountriesModel->user_id = $usersModel->user_id;
                        $userDocumentsCountriesModel->country_id = $country['id'];
                        $userDocumentsCountriesModel->bill_id = $billsModel->bill_id; // Assign the bill_id here
                        $userDocumentsCountriesModel->save();
                    }
                }
    
                // After handling countries, update the bill with the correct total price
                $billsModel->price = $totalPrice;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid countries data format'
                ]);
            }
        }
    
        // For other plans (id 1, 3, or 4), we just save the bill without handling countries
        if ($role != 2) {
            // Update the bill with the total price (if necessary, but for role 1, 3, or 4, it's already set)
            $billsModel->price = $totalPrice;
        }
    
        // Re-save the bill after calculating the final total price
        $billsModel->save(); // This will save the updated price
    
        // Return the response with user and bill details
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'userId' => $usersModel->user_id,
            'totalPrice' => $totalPrice, // Return the total price for confirmation
            'billId' => $billsModel->bill_id // Return the bill ID as well
        ]);
    }
    
    
    

    // Edit user details
    public function editUser($userId, Request $request) {
        $messages = [
            'role.required' => 'Role of the user is required',
            'name.required' => 'Name of the user is required',
            'name.max' => 'Name of the user must contain less than 20 characters',
            'name.min' => 'Name of the user must contain at least 3 characters',
            'surname.required' => 'Surname of the user is required',
            'surname.max' => 'Surname of the user must contain less than 20 characters',
            'surname.min' => 'Surname of the user must contain at least 3 characters',
            'email.required' => 'Email of the user is required',
            'email.max' => 'Email of the user must contain less than 60 characters',
            'email.email' => 'Email of the user is invalid',
            'email.unique' => 'Email of the user is already in use',
        ];

        // Validate the user input
        $validations = $request->validate([
            'role' => 'required',
            'name' => 'required|max:20|min:3',
            'surname' => 'required|max:20|min:3',
            'email' => 'required|max:60|email|unique:fsc_users,email,' . $userId . ',user_id'
        ], $messages);

        // Retrieve input data
        $role = $request->input('role');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');

        // Find the user and update their details
        $usersModel = UsersModel::where('user_id', $userId)->first();
        $usersModel->user_role_id = $role;
        $usersModel->name = $name;
        $usersModel->surname = $surname;
        $usersModel->email = $email;
        $usersModel->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
    }

    // Delete a user
    public function deleteUser($userId) {
        $user = UsersModel::find($userId);
        
        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
    }



    public function logout() {
    	auth()->logout();
        Session::flush();
        return Redirect('/');
    }
    public function profile() {

    	$hasDarkNav = true;

        return view('frontend.pages.profile', compact('hasDarkNav'));
    }
}