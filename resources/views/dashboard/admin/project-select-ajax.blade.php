@if($projects == 'All')
    <div class="alert alert-secondary col-md-12 text-center" role="alert">All Projects</div>
    <input type="hidden" name="project_id" value="All" />
</div>
@else
<div class="input-group-prepend">
    <span class="input-group-text">Project</span>
</div>
<select class="form-control" name="project_id" id="project">
    @if(!empty($projects->project))
    <option value="{{ $projects->project->id }}">{{ $projects->project->title }}</option>
    @else
    @foreach($projects as $project)
        <option value="{{ $project->id }}">{{ $project->title }}</option>
    @endforeach
    @endif
    </select>
@endif