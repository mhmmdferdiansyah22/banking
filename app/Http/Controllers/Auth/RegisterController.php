<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public function showRegistrationForm(Request $request)
    {
        $users = User::with('role')
        ->whereNotIn('name', ['student', 'bank', 'admin']) 
        ->paginate(6);
        $roles = Role::all(); // Tambahkan ini
        return view('auth.register', compact('users', 'roles')); 
    }
    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer','exists:roles,id'],
        ]);
    }
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_number' => in_array($data['role_id'], [1, 2]) ? null : User::generateRandomNumber(),
            'role_id'=> $data['role_id'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $this->create ($request->all());

        return redirect()->back()->with('success','created succesful');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); 

        return view('auth.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role_id' => ['required','integer', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        // Jika password diisi, update passwordnya
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('register')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }
}