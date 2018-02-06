@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Servers
                        <a class="pull-right" href="{{route('new.server')}}">
                            Add Server
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
                                Name
                            </th>
                            <th>
                                IP Address
                            </th>
                            <th>
                                Hostname
                            </th>
                            <th>
                                HTTPS
                            </th>
                            <th>
                                Port
                            </th>
                            <th>
                                <p class="pull-right"></p>
                            </th>
                            </thead>
                            @foreach($servers as $server)
                                <tr>
                                    <td>
                                        {{$server->label}}
                                    </td>
                                    <td>
                                        {{$server->ip}}
                                    </td>
                                    <td>
                                        {{$server->hostname}}
                                    </td>
                                    <td>
                                        @if($server->uses_https)
                                            Yes
                                        @else
                                            No
                                        @endif
                                    </td>
                                    <td>
                                        {{$server->port}}
                                    </td>
                                    <td class="">
                                        <a class="btn btn-danger btn-sm pull-right"
                                           href="{{route('delete.server', [$server->id])}}"><span aria-hidden="true"
                                                                                                  class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="panel-footer center-div">
                        {{ $servers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
