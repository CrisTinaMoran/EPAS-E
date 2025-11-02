<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Store new user in database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'ext_name'      => 'nullable|string|max:10',
            'email'         => 'required|email|unique:users,email',
            'role'          => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'stat'          => 'required|boolean',
            'password'      => 'required|string|min:6|confirmed',
            'section'       => 'nullable|string|max:255',
            'room_number'   => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('private.users.index')->with('success', 'User added successfully!');
    }

    // List all users WITH SEARCH, FILTER, AND SORT FUNCTIONALITY
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter');
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        // Validate direction parameter
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            $direction = 'desc';
        }
        
        $users = User::with('department')
                ->when($search, function($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('first_name', 'like', '%'.$search.'%')
                        ->orWhere('middle_name', 'like', '%'.$search.'%')
                        ->orWhere('last_name', 'like', '%'.$search.'%')
                        ->orWhere('ext_name', 'like', '%'.$search.'%')
                        ->orWhere('id', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('section', 'like', '%'.$search.'%')
                        ->orWhere('room_number', 'like', '%'.$search.'%')
                        ->orWhereHas('department', function($deptQuery) use ($search) {
                            $deptQuery->where('name', 'like', '%'.$search.'%');
                        });
                    });
                })
                ->when($filter, function($query, $filter) {
                    // Handle different filter types
                    switch ($filter) {
                        case 'role=student':
                            return $query->where('role', 'student');
                        case 'role=instructor':
                            return $query->where('role', 'instructor');
                        case 'role=admin':
                            return $query->where('role', 'admin');
                        case 'status=pending':
                            return $query->where('stat', false);
                        case 'status=active':
                            return $query->where('stat', true);
                        case 'verified=no':
                            return $query->whereNull('email_verified_at');
                        default:
                            return $query;
                    }
                })
                ->orderBy($this->getSortColumn($sort), $direction)
                ->paginate(10);

        $departments = Department::all();
        
        // Get filter counts for the dropdown
        $filterCounts = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'pending' => User::where('stat', false)->count(),
            'active' => User::where('stat', true)->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];
        
        return view('private.users.index', compact('users', 'departments', 'filterCounts'));
    }

    // Get filter counts for UI display
    public function getFilterCounts()
    {
        return [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'instructors' => User::where('role', 'instructor')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'pending' => User::where('stat', false)->count(),
            'active' => User::where('stat', true)->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];
    }

    // Helper method to map sort parameters to actual columns
    private function getSortColumn($sort)
    {
        $mapping = [
            'id' => 'id',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'student_id' => 'student_id',
            'email' => 'email',
            'role' => 'role',
            'department_id' => 'department_id',
            'section' => 'section',
            'room_number' => 'room_number',
            'email_verified_at' => 'email_verified_at',
            'stat' => 'stat',
            'created_at' => 'created_at',
            'name' => 'last_name',
            'student' => 'student_id',
            'department' => 'department_id',
            'status' => 'stat',
        ];

        return $mapping[$sort] ?? 'created_at';
    }


    // Show edit form
    public function edit(User $user)
    {
        $departments = Department::all();
        return view('private.users.edit', compact('user', 'departments'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'student_id'        => 'required|string|max:25',
            'first_name'    => 'required|string|max:255',
            'middle_name'   => 'nullable|string|max:255',
            'last_name'     => 'required|string|max:255',
            'ext_name'      => 'nullable|string|max:10',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role'          => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'stat'          => 'required|boolean',
            'password'      => 'nullable|string|min:6|confirmed',
            'section'       => 'nullable|string|max:255',
            'room_number'   => 'nullable|string|max:255',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Ensure room_number is included in the update
        if (!isset($validated['room_number'])) {
            $validated['room_number'] = $user->room_number;
        }

        // Ensure section is included in the update  
        if (!isset($validated['section'])) {
            $validated['section'] = $user->section;
        }

        $user->update($validated);

        return redirect()->route('private.users.edit', $user->id)->with('success', 'User updated successfully!');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('private.users.index')->with('success', 'User deleted successfully!');
    }

    // Approve user
    public function approve(User $user)
    {
        $user->update(['stat' => 1]);
        return redirect()->route('private.users.index')->with('success', 'User approved successfully!');
    }

    public function create()
    {
        $departments = Department::all();
        return view('private.users.create', compact('departments'));
    }

    public function students()
    {
        $users = User::with('department')
                    ->where('role', 'student')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        $departments = Department::all();

        return view('private.users.index', compact('users', 'departments'));
    }

    // Add these methods to your existing UserController
    public function instructors()
    {
        $users = User::with('department')
                    ->where('role', 'instructor') // Changed from 'instructor' to 'instructor' to match your role naming
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        $departments = Department::all();

        return view('private.users.index', compact('users', 'departments'));
    }

    public function admins()
    {
        $users = User::with('department')
                    ->where('role', 'admin')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        $departments = Department::all();

        return view('private.users.index', compact('users', 'departments'));
    }
}