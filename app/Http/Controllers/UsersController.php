<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use App\Models\Setting;
use App\Models\ProjectManagement;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
        // $this->middleware('manager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(auth()->user()->getRoleNames());
        $you = auth()->user();
        if ($you->hasRole('admin')) {
            $users = User::all();
        } else {
            // $users = User::find($you->id)->whereHas("roles", function($q){ $q->where("name", "manager")->orWhere("name", "user"); })->get();
            $users = User::where('created_by', $you->id)->get();
        }
        return view('dashboard.admin.usersList', compact('users', 'you'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $you = auth()->user();
        if ($you->hasRole('manager')) {
            $roles = Role::where('name', '!=', 'admin')->get();
            $projects = ProjectManagement::with('project')->where('user_id', $you->id)->first();
        } else {
            $roles = Role::all();
            $projects = Project::all();
        }
        return view('dashboard.admin.userCreateForm', compact( 'roles', 'projects' ));
    }
    
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:255',
            'email' => 'required',
            'menuroles' => 'required',
            'password' => 'required',
            'project_id' => 'required'
        ]);
        $you = auth()->user();
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->menuroles = $request->input('menuroles');
        $user->created_by = $you->id;
        $user->save();
        
        $user->assignRole($request->input('menuroles'));

        $projectManagement = new ProjectManagement();
        $projectManagement->user_id = $user->id;
        $projectManagement->project_id = $request->input('project_id');
        $projectManagement->created_by = $you->id;
        $projectManagement->enabled = true;
        $projectManagement->save();

        $this->createSettings($user->id);

        $request->session()->flash('message', 'Successfully created user');
        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $user = User::find($id);
        $you = auth()->user();
        $roles = Role::all();
        $projects = ProjectManagement::with('project')->where('user_id', $id)->first();
        return view('dashboard.admin.userShow', compact( 'user', 'projects' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $you = auth()->user();
        if ($you->hasRole('manager')) {
            $roles = Role::where('name', '!=', 'admin')->get();
            $projects = ProjectManagement::with('project')->where('user_id', $you->id)->first();
        } else {
            $projects = Project::all();
            $roles = Role::all();
        }
        return view('dashboard.admin.userEditForm', compact('user', 'roles', 'projects'));
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
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'email'      => 'required|email|max:256'
        ]);
        $user = User::find($id);
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->menuroles = $request->input('menuroles');
        $user->save();
        
        $user->assignRole($request->input('menuroles'));

        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            ProjectManagement::where('user_id', $id)->delete();
            foreach ($user->getRoleNames() as $key => $value) {
                $user->removeRole($value);
            }
            $user->delete();
        }
        return redirect()->route('users.index');
    }

    public function createSettings($user_id)
    {
        $config = [
            'filters' => [
                'active' => 'on',
                'matrix' => 'on',
                'quickDate' => 'on',
                'datepicker' => 'on'
            ],
            'topCards' => [
                'active' => 'on',
                'sessions' => 'on',
                'users' => 'on',
                'visits' => 'on',
                'bounceRate' => 'on',
                'avgSessionTime' => 'on'

            ],
            'overview' => [
                'active' => 'on',
                'graph' => 'on',
                'cards' => [
                    'active' => 'on',
                    'newUsers' => 'on',
                    'sessions' => 'on',
                    'avgSessionDuration' => 'on',
                    'bounceRate' => 'on'
                ],
                'pieGraph' => 'on'
            ],
            'graphs' => [
                'devices' => 'on',
                'traffic' => 'on'
            ],
            'events' => [
                'active' => 'on',
                'eventTabs' => ['Location', 'Product', 'Video']
            ]
        ];
        $setting = new Setting();
        $setting->config = json_encode($config);
        $setting->user_id = $user_id;
        $setting->save();
    }
}
