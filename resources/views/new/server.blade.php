@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="POST" action="{{route('create.server')}}">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">Create Server</div>

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="label">Name</label>
                                <div class="col-md-5">
                                    <input placeholder="Example Website" class="form-control" type="text" id="label" name="label">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="label">IP Address</label>
                                <div class="col-md-5">
                                    <input placeholder="10.10.10.10" class="form-control" type="text" id="ip" name="ip">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="label">Hostname</label>
                                <div class="col-md-5">
                                    <input placeholder="www.example.com" class="form-control" type="text" id="hostname" name="hostname">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="label">Port</label>
                                <div class="col-md-5">
                                    <input placeholder="80" class="form-control" type="text" id="port" name="port">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="label">Use HTTPS</label>
                                <div class="col-md-5">
                                    <label class="switch">
                                        <input type="checkbox" id="https" name="https">
                                        <span class="slider round"></span>
                                    </label>
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
