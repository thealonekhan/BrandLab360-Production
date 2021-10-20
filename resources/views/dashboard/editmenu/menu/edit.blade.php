@extends('dashboard.base')

@section('content')


<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
        <div class="card">
          <div class="card-header"><h4>Create menu element</h4></div>
            <div class="card-body">
                @if(Session::has('message'))
                    <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                @endif

                <form action="{{ route('menu.menu.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $menulist->id }}" id="menuElementId"/>
                    <table class="table table-striped table-bordered datatable">
                        <tbody>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <td>
                                    <input type="text" name="name" class="form-control" value="{{ $menulist->name }}"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success pb-0" type="submit">Save</button>
                    <a class="btn btn-danger ml-1 pb-0" href="{{ route('menu.menu.index') }}">Return</a>
                </form>
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