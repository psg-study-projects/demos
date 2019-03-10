<!-- PARTIAL: site.activitymessages._show -->
<span><strong>{{ $obj->sender->renderName() }}</strong></span>: 
<span>{{ $obj->renderField('amcontent') }}</span> 
<span><small class="text-muted">{{ $obj->renderField('created_at') }}</small></span>
