@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <form method="POST" action="{{route('bust')}}">
                        {{ csrf_field() }}
                        <div class="panel-heading">Cache Busting Scheme Overview</div>

                        <div class="panel-body">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <h3>Scheme Test Operations</h3>
                            <div class="well console-text">
                                <h4>App Server</h4>
                                <ul>
                                    <li>
                                        A request will be sent to:
                                        <span class="text-danger">
                                            @if($scheme->server->uses_https)
                                                https://{{$scheme->server->ip}}:{{$scheme->server->port}}{{$scheme->server_uri->path}}
                                            @else
                                                http://{{$scheme->server->ip}}:{{$scheme->server->port}}{{$scheme->server_uri->path}}
                                            @endif
                                    </span>
                                    </li>
                                </ul>

                                @if(!empty($scheme->proxies))
                                    <hr/>
                                    <h4>Proxy Servers</h4>
                                    <ul>
                                        @foreach($scheme->proxies as $proxy)
                                            <li>
                                                A request will be sent to:
                                                <span class="text-danger">
                                                    @if($scheme->server->uses_https)
                                                        https://{{$proxy->ip}}:{{$scheme->server->port}}{{$scheme->proxy_uri->path}}
                                                    @else
                                                        http://{{$proxy->ip}}:{{$scheme->server->port}}{{$scheme->proxy_uri->path}}
                                                    @endif
                                            </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <hr/>
                                <h4>Other Info</h4>
                                <ul>
                                    <li>
                                        Masquerading as
                                        <span class="text-danger">
                                        {{$scheme->server->hostname}}
                                    </span>
                                    </li>
                                    <li>
                                        Example Buster Key:
                                        <span class="text-danger">
                                            @if($scheme->server->uses_https)
                                                httpsGET{{$scheme->server->hostname}}/SomePathToPurge/
                                            @else
                                                httpGET{{$scheme->server->hostname}}/SomePathToPurge/
                                            @endif
                                    </span>
                                    </li>
                                    <li>
                                        Example Log Entry:
                                        <span class="text-danger">
                                            @if($scheme->server->uses_https)
                                                {
                                                    "buster_cache_key": "httpsGET{{$scheme->server->hostname}}/SomePathToPurge/",
                                                    "buster_mode": "test"
                                                }
                                            @else
                                                {
                                                    "buster_cache_key": "httpGET{{$scheme->server->hostname}}/SomePathToPurge/",
                                                    "buster_mode": "test"
                                                }
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>

                            <h3>Manually Execute Purge</h3>
                            <div class="well console-text">

                                <input type="text" style="display: none;" hidden readonly value="{{$scheme->id}}"
                                       id="scheme_id" name="scheme_id">

                                <div class="form-group row">
                                    <label class="col-md-4 control-label" for="method">Key HTTP Method</label>
                                    <div class="col-md-5">
                                        <select class="selectpicker" name="method" id="method">
                                            <option value="GET">GET</option>
                                            <option value="POST">POST</option>
                                            <option value="DELETE">DELETE</option>
                                            <option value="PUT">PUT</option>
                                            <option value="PATCH">PATCH</option>
                                            <option value="HEAD">HEAD</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-4 control-label" for="path">Path To Purge</label>
                                    <div class="col-md-5">
                                        <input name="path" id="path" class="form-control" placeholder="/about/">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-danger pull-right" style="margin: 2px;">
                                Execute Purge
                            </button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
