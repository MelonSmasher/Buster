@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Cache Busting Schemes
                        <a class="pull-right" href="{{route('new.scheme')}}">
                            Create Scheme
                        </a>
                    </div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-hover table-condensed">
                            <thead>
                            <th>
                                ID
                            </th>
                            <th>
                                Server
                            </th>
                            <th>
                                Server URI
                            </th>
                            <th>
                                Proxies
                            </th>
                            <th>
                                Proxy URI
                            </th>
                            <th>
                                <p class="pull-right"></p>
                            </th>
                            </thead>
                            @foreach($schemes as $scheme)
                                <tr>
                                    <td>
                                        <a href="{{route('scheme', [$scheme->id])}}">
                                            {{$scheme->id}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$scheme->server->label}}
                                    </td>
                                    <td>
                                        {{$scheme->server_uri->path}}
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($scheme->proxies as $proxy)
                                                <li>{{$proxy->label}}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        {{$scheme->proxy_uri->path}}
                                    </td>
                                    <td>
                                        <a class="btn btn-danger btn-sm pull-right" style="margin: 2px;"
                                           href="{{route('delete.scheme', [$scheme->id])}}"><span aria-hidden="true"
                                                                                                  class="fa fa-trash"></span></a>
                                        <a class="btn btn-primary btn-sm pull-right" style="margin: 2px;"
                                           href="{{route('scheme', [$scheme->id])}}"><span aria-hidden="true"
                                                                                           class="fa fa-eye"></span></a>
                                    </td>
                                </tr>

                            @endforeach
                        </table>
                    </div>

                    <div class="panel-footer center-div">
                        {{ $paginated->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
