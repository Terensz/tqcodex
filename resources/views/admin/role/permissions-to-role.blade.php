<?php
use Domain\User\Services\PermissionService;
use Domain\User\Services\PermissionTranslationService;
use Domain\Shared\Livewire\Base\BaseEditComponent;
use Domain\Shared\Helpers\PHPHelper;
?>

<table style="width: 100%;">
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
    @foreach($cachedRolePermissionData as $permissionGuard => $guardSuffixes)
        @if($permissionGuard == $guardName)
            <tr>
                <td style="text-align: right;">
                    {{ __('admin.CheckAll') }}
                </td>
                <td style="text-align: center;">
                    <input wire:model="form.check_all.{{ $guardName }}" @click="$dispatch('check-all-permission-in-guard')" type="checkbox">
                </td>
                <td style="text-align: center;">
                </td>
                <td style="text-align: center;">
                </td>
                <td style="text-align: center;">
                </td>
                <td style="text-align: center;">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>{{ __('shared.All') }}</td>
                <td>{{ __('shared.View') }}</td>
                <td>{{ __('shared.Create') }}</td>
                <td>{{ __('shared.Edit') }}</td>
                <td>{{ __('shared.Delete') }}</td>
            </tr>
            <!-- <tr>
                <td style="text-align: center;" colspan="6">
                    {{ $permissionGuard }}
                </td>
            </tr> -->
            @foreach($guardSuffixes as $suffix => $suffixParams)
            <tr>
                <td>{{ PermissionTranslationService::getNameTranslation($suffix) }}</td>
                @php 
                    $allCheckedString = '';
                    if ($guardPermissionData[$suffix][PermissionService::PREFIX_VIEW] && $guardPermissionData[$suffix][PermissionService::PREFIX_CREATE] && $guardPermissionData[$suffix][PermissionService::PREFIX_EDIT] && $guardPermissionData[$suffix][PermissionService::PREFIX_DELETE]) {
                        $allCheckedString = 'checked="checked"';
                    }
                    // $allCheckedString = ' checked';
                @endphp 
                @foreach([PermissionService::PREFIX_ALL, PermissionService::PREFIX_VIEW, PermissionService::PREFIX_CREATE, PermissionService::PREFIX_EDIT, PermissionService::PREFIX_DELETE] as $prefix)
                    <td style="text-align: center;">
                        @if($prefix == PermissionService::PREFIX_ALL) 
                            <input wire:model="form.permission_data.{{ $guardName }}.{{ $suffix }}.{{ $prefix }}" @click="$dispatch('check-all-permission-in-suffix', '{{ $suffix }}')" type="checkbox">
                        @else 
                            <input wire:model="form.permission_data.{{ $guardName }}.{{ $suffix }}.{{ $prefix }}" @click="$dispatch('refresh-guard-permissions')" type="checkbox">
                        @endif 
                    </td>
                @endforeach
            </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>