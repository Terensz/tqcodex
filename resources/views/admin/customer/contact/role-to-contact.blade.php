<?php
use Domain\User\Services\PermissionService;
use Domain\Shared\Livewire\Base\BaseEditComponent;

// dump($contactRoleData);
?>

<table style="width: 100%;">
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('user.AssigningRolesToUser') }}
    </h2>
    <!-- <thead>
        <tr>
            <th></th>
            <th>All</th>
            <th>View</th>
            <th>Create</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead> -->
    <tbody>
            <tr>
                <td></td>
                <td style="text-align: center;">{{ __('shared.Select') }}</td>
            </tr>
            @foreach($contactRoleData as $roleName => $roleActive)
            @php
            @endphp 
            <tr>
                <td>{{ __($roleName) }}</td>
                <td style="text-align: center;">
                    <input wire:model="form.role_data.{{ $roleName }}" type="checkbox">
                </td>
            </tr>
            @endforeach
    </tbody>
</table>