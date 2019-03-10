@extends('layouts.site.main')
@section('body-class') @parent()dashboard show @stop()

{{--
@section('sidebar')
@include('site.common._majorNav')
@endsection
--}}

@section('content')
<div class="supercontainer-view">

    <section class="subcontainer-header row">
        <article class="col">
            <h2>Welcome to the Dashboard</h2>
        </article>
    </section>

    <section class="subcontainer-main row">

        <article class="col-8">
            <h5>My Topic: {{ $session_user->topic->renderName() }}</h5>
            <ul class="nav nav-tabs" id="tabs-dashboard" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="available_users-tab" data-toggle="tab" href="#available_users" role="tab" aria-controls="available_users" aria-selected="true">Available Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-conversations" data-toggle="tab" href="#conversations" role="tab" aria-controls="conversations" aria-selected="false">Conversations</a>
                </li>
            </ul>

            <div class="tab-content" id="tabs-dashboard-content">

                <div id="available_users" class="tab-pane fade show active table-responsive" role="tabpanel" area-labelledby="available_users-tab">
                    <table class="table tag-datatable tag-dt_util_container  datatable tag-standard_table_light" data-resource_key="available_users" data-route="{{ route('site.users.index', ['filters'=>[],'options'=>[]]) }}"></table>
                </div>
                <div id="conversations" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-conversations">
                    <table class="table">
                        <tr>
                            {{--
                            <th>GUID</td>
                            --}}
                            <th>User</td>
                            <th>Last Activity</td>
                        </tr>
                    @foreach ($conversations as $c)
                        <tr>
                            {{--
                            <td>{{ $c->guid }} </td>
                            --}}
                            <td>{{ link_to_route( 'site.chat.show', $c->getPartner()->renderName(), $c->guid ) }} </td>
                            <td>{{ $c->renderField('updated_at') }} </td>
                        </tr>
                    @endforeach
                    </table>
                </div>

            </div>

        </article>

    </section>
 
</div>
@endsection
