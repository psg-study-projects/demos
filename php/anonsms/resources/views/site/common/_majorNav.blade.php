<!-- PARTIAL: site.common._minorNav-->
<section class="accordion" id="major-nav">

	<article class="card">
		<div class="card-header" id="heading-dashboard">
			<h2 class="mb-0">
                {{link_to_route('site.directory.index', 'Directory', null, ['class'=>'btn btn-link']) }}
			</h2>
		</div>
	</article>

    {{--
	<article class="card">
		<div class="card-header" id="heading-dashboard">
			<h2 class="mb-0">
                {{link_to_route('site.dashboard.show', 'Dashboard', null, ['class'=>'btn btn-link']) }}
			</h2>
		</div>
	</article>
    --}}

	<article class="card">
		<div class="card-header" id="heading-cms">
			<h2 class="mb-0">
                {{link_to_route('site.cms.index', 'CMS', null, ['class'=>'btn btn-link']) }}
			</h2>
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-clientmatter">
			<h2 class="mb-0">
                {{link_to_route('site.clientmatters.index', 'Client-Matter', null, ['class'=>'btn btn-link']) }}
			</h2>
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-workflows">
			<h2 class="mb-0">
				<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_workflows" aria-expanded="true" aria-controls="collapse_workflows">Workflows</button>
			</h2>
		</div>
		<div id="collapse_workflows" class="collapse" aria-labelledby="heading-workflows" data-parent="#major-nav">
            <div class="card-body">
			    <h2 class="mb-0">
                    {{link_to_route('site.workflowtemplates.index', 'Workflow Templates', null, ['class'=>'btn btn-link']) }}
			    </h2>
			    <h2 class="mb-0">
                    {{link_to_route('site.workflowinstances.index', 'Workflow Instances', null, ['class'=>'btn btn-link']) }}
			    </h2>
            </div>
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-floor_maps">
			<h2 class="mb-0">
				<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_floor_maps" aria-expanded="true" aria-controls="collapse_floor_maps">Floor Maps</button>
			</h2>
		</div>
		<div id="collapse_floor_maps" class="collapse" aria-labelledby="heading-floor_maps" data-parent="#major-nav">
            <div class="card-body">TBD...</div>
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-practice_groups">
			<h2 class="mb-0"><button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse-practice_groups" aria-expanded="false" aria-controls="collapse-practice_groups">Practice Groups</button></h2>
		</div>
		<div id="collapse-practice_groups" class="collapse" aria-labelledby="heading-practice_groups" data-parent="#major-nav">
            <div class="card-body">TBD...</div>
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-admin_depts">
			<h2 class="mb-0"><button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse_admin_depts" aria-expanded="false" aria-controls="collapse_admin_depts">Admin Depts</button></h2>
		</div>
		<div id="collapse_admin_depts" class="collapse" aria-labelledby="heading-admin_depts" data-parent="#major-nav">
			<div class="card-body">{!! $g_nt_html or '' !!}</div> {{-- Navigation Taxonomy object --}}
		</div>
	</article>

	<article class="card">
		<div class="card-header" id="heading-it_admin">
			<h2 class="mb-0">
				<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_admin" aria-expanded="true" aria-controls="collapse_admin">Admin (IT)</button>
			</h2>
		</div>
		<div id="collapse_admin" class="collapse" aria-labelledby="heading-it_admin" data-parent="#major-nav">
            <div class="card-body">
			    <h2 class="mb-0">
                    {{link_to_route('site.importcontents.index', 'Import Contents', null, ['class'=>'btn btn-link']) }}
			    </h2>
			    <h2 class="mb-0">
                    {{link_to_route('site.employees.index', 'Employees', null, ['class'=>'btn btn-link']) }}
			    </h2>
                {{--
			    <h2 class="mb-0">
                    {{link_to_route('site.importfiles.index', 'Import Files', null, ['class'=>'btn btn-link']) }}
			    </h2>
                --}}
            </div>
		</div>
	</article>

</section>

@php
$routes2 = [];
$cmscontents = \App\Models\Cmscontent::get();
foreach ($cmscontents as $cmsc) {
    $routes2[] = [
        'display'=>$cmsc->ctitle,
        'name'=>'site.cms.show',
        'params'=>explode('.',$cmsc->ckey), // ['test-page-1'],
        'extra_classes'=>[],
    ];
}
@endphp

{{-- Nav Pages in CMS as List --}}
{{--
<hr />

<ul class="routes-pages tag-minorNav btn-group-vertical" role="group">
    @foreach ($routes2 as $s => $r)
        @php
        $extra_classes = implode( ' ', !empty($r['extra_classes']) ? $r['extra_classes']:[]  );
        @endphp
        <li class="btn btn-primary">
            {{link_to_route($r['name'], $r['display'], $r['params'], ['class'=>$extra_classes]) }}
            {{link_to('#', '+', ['class'=>'clickme_to-expand']) }}
        </li>
    @endforeach
</ul>
--}}



{{--
        @if ('delete'==$s)
        <li>{{link_to_route($r['name'], $r['display'], $r['params'], ['data-method'=>'delete', 'data-token'=>csrf_token(), 'data-confirm'=>'Are you sure?', 'class'=>'btn btn-primary '.$extra_classes]) }} </li>
        @else
--}}
