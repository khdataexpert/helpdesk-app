<x-app-layout>
    @php
        $isRtl = app()->isLocale('ar');
        $textDirection = $isRtl ? 'text-right' : 'text-left';
    @endphp

    <div class="p-6">
        <h2 class="text-3xl font-semibold mb-6 {{ $textDirection }} text-gray-800">
            {{ __('text.management_title') }}
        </h2>

        {{-- ✅ رسالة النجاح --}}
        @if (session('success'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md" role="alert">
                <p class="font-bold">{{ __('text.success_heading') }}</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
@can('add teams')
    
{{-- ✅ زر إضافة فريق جديد --}}
<div class="flex mb-4 justify-end">
    <a href="{{ route('teams.create') }}"
       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
        {{ __('text.add_new') }}
    </a>
</div>

@endcan
        @can('view teams')


            {{-- ✅ جدول عرض الفرق --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead
                            class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 {{ $textDirection }} text-xs font-medium uppercase tracking-wider">#
                                </th>
                                <th class="px-6 py-3 {{ $textDirection }} text-xs font-medium uppercase tracking-wider">
                                    {{ __('text.table_name') }}
                                </th>
                                <th class="px-6 py-3 {{ $textDirection }} text-xs font-medium uppercase tracking-wider">
                                    {{ __('text.table_specialization') }}
                                </th>
                                <th class="px-6 py-3 {{ $textDirection }} text-xs font-medium uppercase tracking-wider">
                                    {{ __('text.table_members_count') }}
                                </th>
                                <th class="px-6 py-3 {{ $textDirection }} text-xs font-medium uppercase tracking-wider">
                                    {{ __('text.table_actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($teams as $team)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $team->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $team->Specialization ?? __('text.not_available') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $team->users_count }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 {{ $isRtl ? 'space-x-reverse' : '' }}">
                                        {{-- ✅ زر تعديل الأعضاء --}}
                                        <a href="{{ route('teams.edit_members', $team) }}"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-gray-600 hover:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                            {{ __('text.edit_members') }}
                                        </a>
                                        {{-- ✅ زر عرض التفاصيل --}}
                                        <a href="{{ route('teams.show', $team) }}"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                            {{ __('text.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ __('text.no_teams_yet') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
