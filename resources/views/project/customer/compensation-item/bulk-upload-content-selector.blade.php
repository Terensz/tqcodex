<div>
@if($jobDone)
    @include('project.customer.compensation-item.bulk-upload-job-done')
@else 
    @include('project.customer.compensation-item.bulk-upload-form')
@endif
</div>
