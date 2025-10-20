<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">

        {{-- العنوان وزر الإضافة --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.roles_management') }}</h2>
            <a href="{{ route('roles.create') }}"
                class="bg-blue-600 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center">
                @if (app()->isLocale('ar'))
                    <i class="fas fa-plus ms-2"></i> {{ __('text.create_new_role') }}
                @else
                    <i class="fas fa-plus me-2"></i> {{ __('text.create_new_role') }}
                @endif
            </a>
        </div>
        @can('view Roles')

            {{-- جدول عرض الأدوار --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead
                        class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">
                                {{ __('text.role_name') }}
                            </th>

                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-gray-900 uppercase tracking-wider hidden md:table-cell">
                                {{ __('text.role_permissions') }}
                            </th>

                            {{-- رأس عمود الأزرار (نص فارغ) --}}
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- اسم الدور --}}
                                <td
                                    class="px-5 py-4 border-b border-gray-200 bg-white text-base font-semibold text-gray-900">
                                    {{ $role->name }}
                                    @if ($role->name === 'Super Admin')
                                        <span class="ms-2 text-xs text-red-500">(System Owner)</span>
                                    @endif
                                </td>

                                {{-- الصلاحيات --}}
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm hidden md:table-cell">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($role->permissions as $permission)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-medium rounded-full 
bg-blue-100 text-blue-700 border border-blue-300">

                                                {{ __("text.permission_{$permission->name}") }}
                                            </span>
                                        @endforeach
                                        @if ($role->permissions->isEmpty())
                                            <span
                                                class="text-xs text-gray-500">{{ __('text.no_permissions_assigned') }}</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- الأزرار (Edit/Delete) --}}
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-right">
                                    @can('edit Roles')
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium transition duration-150 me-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('delete Roles')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('{{ __('text.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium transition duration-150">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endcan
    </div>
</x-app-layout>
