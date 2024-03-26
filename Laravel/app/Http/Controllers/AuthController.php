<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }
  
    public function registerSave(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => [
            'required',
            'email',
            Rule::unique('users')
        ],
        'password' => 'required|min:4|confirmed'
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => 'Admin'
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
    }

    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
}

  
    public function login()
    {
        return view('auth/login');
    }
  
    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();
  
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }
  
        $request->session()->regenerate();
  
        return redirect()->route('productstype');
    }
  
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/');
    }
 
    public function profile()
    {
        return view('profile');
    }

    public function editprofile(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $oldValues = [
            'name' => $user->name,
            'phone' => $user->phone,
            'address' => $user->address,
        ];
        
        $newValues = $request->only(['name', 'phone', 'address']);
        Log::info("gh",$oldValues);
        Log::info("ch",$newValues);
        // Check if any fields have changed
        $changes = array_diff_assoc($newValues, $oldValues);
  
    
        // If no changes, display message
        if (empty($changes)) {
            return redirect()->back()->with('error', 'No changes were made. Same value is added');
        }
        $user->update($newValues);
        return redirect()->back()->with('success','Product updated successfully');
    }
}