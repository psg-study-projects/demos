<!-- PARTIAL: admin.base._show -->
<?php
    //dd($obj);
?>
<table class="table table-striped table-condensed">
    @foreach ($obj->toArray() as $k => $v) 
<?php
        switch ($k) {
            case 'deleted_at':
                continue 2; // skip
        }
?>
    <tr>
        <td>{{$obj->renderFieldKey($k)}}</td>
        <td>{{$obj->renderField($k)}}</td>
    </tr>
    @endforeach 
</table>
