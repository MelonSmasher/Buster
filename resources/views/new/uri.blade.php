@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="POST" action="{{route('create.uri')}}">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">Create URI</div>

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="path">Path</label>
                                <div class="col-md-5">
                                    <input placeholder="/purge" class="form-control" type="text" id="path" name="path">
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer text-right">
                            <button class="btn btn-primary pull-right">
                                Save
                            </button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
