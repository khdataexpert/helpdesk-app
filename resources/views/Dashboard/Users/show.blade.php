<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="bg-white shadow-xl rounded-xl p-6 border border-gray-100">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-800">
                    {{ __('text.user_details') }}
                </h2>

                <a href="{{ route('users.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left"></i> {{ __('text.back_to_list') }}
                </a>
            </div>

            {{-- بيانات المستخدم --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.user_name') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.user_email') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.created_at') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('text.updated_at') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            {{-- الأدوار --}}
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ __('text.user_roles') }}</h3>
                @if($user->roles->isEmpty())
                    <p class="text-gray-600">{{ __('text.no_roles_assigned') }}</p>
                @else
                    @foreach($user->roles as $role)
                        <span class="inline-block bg-blue-100 text-blue-800 border border-blue-300 text-xs font-medium rounded-full px-3 py-1 me-2 mb-2">
                            {{ $role->name }}
                        </span>
                    @endforeach
                @endif
            </div>

            {{-- الصلاحيات --}}
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ __('text.user_permissions') }}</h3>
                @php
                    $permissions = $user->getAllPermissions();
                @endphp

                @if($permissions->isEmpty())
                    <p class="text-gray-600">{{ __('text.no_permissions_assigned') }}</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($permissions as $permission)
                            <span class="inline-block bg-purple-100 text-purple-800 border border-purple-300 text-xs font-medium rounded-full px-3 py-1">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
