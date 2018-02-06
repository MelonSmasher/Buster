@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Past Purge Operations</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(empty($purges->count()))
                            No purges to show
                        @else
                            <table class="table table-hover table-condensed">
                                <thead>
                                <th>
                                    Host
                                </th>
                                <th>
                                    Cache IP
                                </th>
                                <th>
                                    Target
                                </th>
                                <th>
                                    Code
                                </th>
                                <th>
                                    Result
                                </th>
                                <th>
                                    Time
                                </th>
                                </thead>

                                @foreach($purges as $purge)
                                    <tr>
                                        <td>
                                            {{explode(':',$purge->url)[0]}}
                                        </td>
                                        <td>
                                            {{explode(':',$purge->url)[2]}}
                                        </td>
                                        <td>
                                            {{$purge->path}}
                                        </td>
                                        <td>
                                            {{$purge->response_code}}
                                        </td>
                                        <td>
                                            @if($purge->response_code == 200)
                                                Purged
                                            @elseif($purge->response_code == 412)
                                                Not Cached
                                            @elseif($purge->response_code == 404)
                                                Not Found
                                            @else
                                                {{$purge->reason}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$purge->created_at->setTimezone(config('app.display_timezone', 'UTC'))->format('h:i A m/d/y')}}
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                    </div>

                    <div class="panel-footer center-div">
                        {{ $purges->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
