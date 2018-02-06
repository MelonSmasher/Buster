@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="POST" action="{{route('create.scheme')}}">
                    {{ csrf_field() }}
                    <div class="panel panel-default">
                        <div class="panel-heading">New Cache Busting Scheme</div>

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="server_id">App Server</label>
                                <div class="col-md-5">
                                    <select class="selectpicker" name="server_id" id="server_id">
                                        @foreach($servers as $server)
                                            <option value="{{$server->id}}">{{$server->label}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="server_uri_id">App Server Purge Path</label>
                                <div class="col-md-5">
                                    <select class="selectpicker" name="server_uri_id" id="server_uri_id">
                                        <option value="0">None</option>
                                        @foreach($uris as $uri)
                                            <option value="{{$uri->id}}">{{$uri->path}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="proxy_ids">Proxies</label>
                                <div class="col-md-5">
                                    <select class="selectpicker" name="proxy_ids[]" multiple="multiple">
                                        <option value="0">None</option>
                                        @foreach($proxies as $proxy)
                                            <option value="{{$proxy->id}}">
                                                {{$proxy->label}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 control-label" for="proxy_uri_id">Proxy Purge Path</label>
                                <div class="col-md-5">
                                    <select class="selectpicker" name="proxy_uri_id" id="proxy_uri_id">
                                        <option value="0">None</option>
                                        @foreach($uris as $uri)
                                            <option value="{{$uri->id}}">{{$uri->path}}</option>
                                        @endforeach
                                    </select>
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
