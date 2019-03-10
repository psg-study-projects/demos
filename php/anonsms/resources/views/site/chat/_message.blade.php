<!-- PARTIAL: site.chat._message -->
<span><strong>{{ $m->sender->renderName() }}</strong></span>: 
<span>{{ $m->renderField('amcontent') }}</span> 
<span><small class="text-muted">{{ $m->renderField('created_at') }}</small></span>
