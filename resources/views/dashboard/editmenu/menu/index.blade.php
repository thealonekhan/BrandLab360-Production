@extends('dashboard.base')

@section('content')


<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header"><h4>Menus list</h4></div>
            <div class="card-body">
                <div class="row mb-3">
                    <a class="btn btn-lg btn-primary custom-btn-color ml-3" href="{{ route('menu.menu.create') }}">Add new menu</a>
                </div>
                <table class="table table-striped table-bordered datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>    
                        @foreach($menulist as $menu1)
                            <tr>
                                <td>
                                    {{ $menu1->name }}
                                </td>
                                <td>
                                    <a class="btn btn-primary custom-btn-color" href="{{ route('menu.index', ['menu' => $menu1->id] ) }}">Show</a>
                                </td>
                                <td>
                                    <a class="btn btn-primary custom-btn-color" href="{{ route('menu.menu.edit', ['id' => $menu1->id] ) }}">Edit</a>
                                </td>
                                <td>
                                    <a class="btn btn-danger pb-0" href="{{ route('menu.menu.delete', ['id' => $menu1->id] ) }}">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('javascript')

@endsection