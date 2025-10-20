<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">

        <div class="flex justify-between items-center mb-6">
            {{-- كان: text-[#235784] --}}
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.users_management') }}</h2>
            {{-- كان: bg-[#40A8C4] hover:bg-[#235784] --}}
            @can('add users')
                

            <a href="{{ route('users.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center">
                @if (app()->isLocale('ar'))
                    <i class="fas fa-plus ms-2"></i> {{ __('text.add_new_user') }}
                @else
                    <i class="fas fa-plus me-2"></i> {{ __('text.add_new_user') }}
                @endif
            </a>
                        @endcan
        </div>

        {{-- رسائل الفلاش (Success/Error) بدون تغيير --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- جدول عرض المستخدمين --}}
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            @can('view users')

            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        {{-- كان: bg-[#40A8C4] text-[#235784] --}}
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                            {{ __('text.user_name') }}
                        </th>
                        {{-- كان: bg-[#40A8C4] text-[#235784] --}}
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                            {{ __('text.user_email') }}
                        </th>
                        {{-- كان: bg-[#40A8C4] text-[#235784] --}}
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                            {{ __('text.user_role') }}
                        </th>
                        {{-- كان: bg-[#40A8C4] --}}
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td
                                class="px-5 py-4 border-b border-gray-200 bg-white text-base font-semibold text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-700">
                                {{ $user->email }}
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                @foreach ($user->roles as $role)
                                    {{-- كان: bg-blue-100 text-[#235784] border border-[#40A8C4] --}}
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-medium rounded-full 
                                                    bg-blue-100 text-blue-800 border border-blue-300">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-right">
                                {{-- كان: text-[#40A8C4] hover:text-[#235784] --}}
                                @can('edit users')
                                    
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="text-blue-500 hover:text-blue-700 font-medium transition duration-150 me-3">
                                    <i class="fas fa-edit"></i> {{ __('text.edit_btn') }}
                                </a>
                                @endcan
                                @can('manage permissions')
                                    
                                <a href="{{ route('users.permissions', $user->id) }}"
                                    class="text-purple-600 hover:text-purple-800 font-medium transition duration-150 me-3">
                                    <i class="fas fa-user-shield"></i> {{ __('text.manage_permissions') }}
                                </a>
                                <a href="{{route('users.show',$user->id) }}" 
                                    class="text-green-500 hover:text-green-700 font-medium transition duration-150 me-3">
                                    <i class="fas fa-eye"></i> {{ __('text.view') }}
                                </a>
                                @endcan
                                @can('delete users')
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    {{-- لون الحذف أحمر زي ما هو (Red) --}}
                                    <button type="submit"
                                    class="text-red-500 hover:text-red-700 font-medium transition duration-150">
                                    <i class="fas fa-trash-alt"></i> {{ __('text.delete_btn') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endcan
                    @endforeach
                </tbody>
            </table>
            @endcan
            {{-- Pagination --}}
            <div class="p-4 bg-white border-t">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
