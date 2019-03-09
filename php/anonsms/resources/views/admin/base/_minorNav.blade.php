<!-- PARTIAL: admin.base._minorNav-->
<div class="tag-minorNav">
<ul class="btn-group-vertical" role="group">
    @foreach ($routes as $s => $r)
        <?php $extra_classes = implode( ' ', !empty($r['extra_classes']) ? $r['extra_classes']:[]  ); ?>
        @if ('delete'==$s)
        <li>{{link_to_route($r['name'], $r['display'], $r['params'], ['data-method'=>'delete', 'data-token'=>csrf_token(), 'data-confirm'=>'Are you sure?', 'class'=>'btn btn-primary '.$extra_classes]) }} </li>
        @else
        <li>{{link_to_route($r['name'], $r['display'], $r['params'], ['class'=>'btn btn-primary '.$extra_classes]) }} </li>
        @endif
    @endforeach
</ul>
</div>
