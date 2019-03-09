@extends('layouts.site.main')
@section('body-class') @parent()dashboard show @stop()

@section('sidebar')
@include('site.common._majorNav')
@endsection

@section('content')
<div class="supercontainer-view">

    <section class="subcontainer-header row">
        <article class="col">
            <h2>Welcome to the dashboard</h2>
        </article>
    </section>

    <section class="subcontainer-main row">

        <article class="col">
            {{ Html::image('/images/og/News/Kili.jpg', 'Highest Peak', ['class' => 'img-fluid']) }}
        </article>

    </section>

    <section class="subcontainer-main row">

        <article class="col">
            <p>Placeholder content for Dashboard</p>

        {{--
        <h4> {{ link_to($feed_data->get_permalink(), $feed_data->get_title) }}</h4>
        <p>{{ $feed_data->get_description() }}</p>
        --}}

            <section class="row">
                <article class="col-sm-6">

                    @foreach($feed_data as $k => $feedObj)
                    <div class="card mb-3">
                        <h5 class="card-header text-center">
                            {{ \App\Libs\Utils::unslugify($k,true) }}
                        </h5>
                        <div class="card-body">
                            <ul class="list-rss_feeds ">
                            @php
                            $iter = 1; 
                            $MAX = 3;
                            @endphp
                            @foreach($feedObj['items'] as $item)
		                        <li class="item">
                                {{ link_to( $item->get_permalink(), $item->get_title() ) }}
                                @if (++$iter > $MAX)
                                    @break
                                @endif
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach

                </article>
            </section>

        </article>

    </section>
 
</div>
@endsection

