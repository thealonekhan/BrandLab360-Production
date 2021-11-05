<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Project;
use App\Models\Setting;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\Session;

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
        if (Auth::user()->password_change_at == null) {
            return redirect(route('users.change.password', Auth::user()->id));
        }
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

        if ($request['project_id'] == 'All') {
            $flag = 0; 
            foreach (Project::all() as $project) {
                $projectManagement = new ProjectManagement();
                $projectManagement->user_id = $user->id;
                $projectManagement->project_id = $project->id;
                $projectManagement->created_by = $you->id;
                $projectManagement->enabled = $flag == 0 ? true : 0;
                $projectManagement->save();
                $flag = $flag + 1;
            }
        } else {
            $projectManagement = new ProjectManagement();
            $projectManagement->user_id = $user->id;
            $projectManagement->project_id = $request->input('project_id');
            $projectManagement->created_by = $you->id;
            $projectManagement->enabled = true;
            $projectManagement->save();
        }

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
        $selectedProjectId = '';
        if ($you->hasRole('manager')) {
            $roles = Role::where('name', '!=', 'admin')->get();
            $projects = ProjectManagement::with('project')->where('user_id', $you->id)->first();
        } else {
            $edit_user_role = $user->getRoleNames()[0];
            if ($edit_user_role != 'admin') {
                $selectedProject = ProjectManagement::where('user_id', $user->id)->first();
                $selectedProjectId = $selectedProject->project_id;
            }
            $projects = Project::all();
            $roles = Role::all();
        }
        return view('dashboard.admin.userEditForm', compact('user', 'roles', 'projects', 'selectedProjectId'));
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
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
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
        Session::flash('message', 'Successfully deleted the user');
        return redirect()->route('users.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::find($id);
        return view('dashboard.admin.user-modal', compact('user'))->render();
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
                'avgSessionTime' => 'on',
                'sessionsPerUser' => 'on'

            ],
            'overview' => [
                'active' => 'on',
                'graph' => 'on',
                'cards' => [
                    'active' => 'on',
                    'users' => 'on',
                    'newUsers' => 'on',
                    'sessions' => 'on',
                    'avgSessionDuration' => 'on',
                    'bounceRate' => 'on',
                    'sessionsPerUser' => 'on'
                ],
                'pieGraph' => 'on'
            ],
            'graphs' => [
                'devices' => 'on',
                'traffic' => 'on'
            ],
            'realtime' => [
                'liveUserWidget' => 'on',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $user = User::find($id);
        return view('dashboard.admin.userProfileForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            // 'email'      => 'required|email|max:256'
        ]);
        $user = User::find($id);
        $user->name       = $request->input('name');
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        $request->session()->flash('message', 'Successfully updated user profile');
        return redirect()->route('dashboard.overview.ajax');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_password($id)
    {
        $user = User::find($id);
        return view('dashboard.admin.userPasswordForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6',
            // 'email'      => 'required|email|max:256'
        ]);
        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));
        $user->password_change_at = \Carbon\Carbon::now();
        $user->save();
        $request->session()->flash('message', 'Successfully updated user password');
        return redirect()->route('dashboard.overview.ajax');
    }

    public function project_select_ajax(Request $request)
    {
        $you = auth()->user();
        if ($request->get('role') != 'admin') {
            if ($you->hasRole('admin')) {
                $projects = Project::all();
            } else {
                $projects = ProjectManagement::with('project')->where('user_id', $you->id)->first();
            }
        } else {
            $projects = 'All';
        }
        return view('dashboard.admin.project-select-ajax', compact( 'projects' ))->render();
    }
}
