<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Status;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('status')->paginate( 20 );
        return view('dashboard.projects.projectsList', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Status::all();
        return view('dashboard.projects.create', ['statuses' => $statuses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:1|max:255',
            'analytics_project_id' => 'required',
            'analytics_view_id' => 'required',
            'status_id' => 'required',
        ]);
        $user = auth()->user();
        $project = new Project();
        $project->title = $request->input('title');
        $project->analytics_project_id = $request->input('analytics_project_id');
        $project->analytics_view_id = $request->input('analytics_view_id');
        $project->description = $request->input('description');
        $project->status_id = $request->input('status_id');
        $project->created_by = $user->id;
        $project->save();
        $request->session()->flash('message', 'Successfully created project');
        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with('owner')->with('status')->find($id);
        return view('dashboard.projects.projectShow', [ 'project' => $project ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $statuses = Status::all();
        return view('dashboard.projects.edit', ['project' => $project, 'statuses' => $statuses]);
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
            'title' => 'required|min:1|max:255',
            'analytics_project_id' => 'required',
            'analytics_view_id' => 'required',
            'status_id' => 'required',
        ]);
        $user = auth()->user();
        $project = Project::find($id);
        $project->title = $request->input('title');
        $project->analytics_project_id = $request->input('analytics_project_id');
        $project->analytics_view_id = $request->input('analytics_view_id');
        $project->description = $request->input('description');
        $project->status_id = $request->input('status_id');
        $project->created_by = $user->id;
        $project->save();
        $request->session()->flash('message', 'Successfully updated project');
        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if($project){
            $project->delete();
        }
        return redirect()->route('projects.index');
    }
}
