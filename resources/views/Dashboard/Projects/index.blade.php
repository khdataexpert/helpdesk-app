<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.projects_management') }}</h2>
            @can('add projects')
                <a href="{{ route('projects.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center">
                    @if (app()->isLocale('ar'))
                        <i class="fas fa-plus ms-2"></i> {{ __('text.create_project') }}
                    @else
                        <i class="fas fa-plus me-2"></i> {{ __('text.create_project') }}
                    @endif
                </a>
            @endcan
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (auth()->user()->can('view projects') || auth()->user()->can('view own projects'))
            {{-- Table --}}
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                                {{ __('text.project_name') }}
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                                {{ __('text.project_client') }}
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                                {{ __('text.project_team') }}
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase tracking-wider">
                                {{ __('text.project_status') }}
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-blue-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td
                                    class="px-5 py-4 border-b border-gray-200 bg-white text-base font-semibold text-gray-900">
                                    {{ $project->name }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-700">
                                    {{ $project->client->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-700">
                                    {{ $project->team->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-gray-700">
                                    {{ ucfirst($project->status) }}
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-right">
                                    <a href="{{ route('projects.show', $project->id) }}"
                                        class="text-green-500 hover:text-green-700 font-medium transition duration-150 me-3">
                                        <i class="fas fa-eye"></i> {{ __('text.view') }}
                                    </a>
                                    @can('edit projects')
                                        <a href="{{ route('projects.edit', $project->id) }}"
                                            class="text-blue-500 hover:text-blue-700 font-medium transition duration-150 me-3">
                                            <i class="fas fa-edit"></i> {{ __('text.edit_btn') }}
                                        </a>
                                    @endcan
                                    @can('delete projects')
                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('{{ __('text.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-medium transition duration-150">
                                                <i class="fas fa-trash-alt"></i> {{ __('text.delete_btn') }}
                                            </button>
                                        </form>
                                    @endcan
                                    {{-- زرار Assign to Me --}}
                                    @if (auth()->user()->hasRole('Agent') && $project->assigned_to == null && !auth()->user()->can('edit projects'))
                                        <form action="{{ route('projects.assignToMe', $project->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-3 rounded-lg transition duration-150 shadow-md">
                                                <i class="fas fa-user-plus me-1"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-3">{{ __('text.no_projects_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
        @endif

    </div>
    </div>
</x-app-layout>
