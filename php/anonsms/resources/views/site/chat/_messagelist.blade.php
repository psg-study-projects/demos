<!-- PARTIAL: site.chat._messagelist -->
@foreach ($messages as $m)
    <li>
        <span><strong>{{ $m->sender->renderName() }}</strong></span>: 
        <span>{{ $m->renderField('amcontent') }}</span> 
        <span><small class="text-muted">{{ $m->renderField('created_at') }}</small></span>
    </li>
@endforeach
