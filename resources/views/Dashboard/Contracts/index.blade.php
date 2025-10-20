<x-app-layout>
    <div class="container mx-auto p-4 md:p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">{{ __('text.contracts_management') }}</h2>

            @if (auth()->user()->hasRole('Super Admin'))
                <a href="{{ route('contracts.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center">
                    <i class="fas fa-plus me-2"></i> {{ __('text.create_contract') }}
                </a>
            @endif
        </div>

        {{-- ✅ Alerts --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}
            </div>
        @endif
@if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(auth()->user()->can('view contracts') || auth()->user()->can('view own contracts'))
        {{-- ✅ Table --}}
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            #</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_number') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_client') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_project') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_start_date') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_end_date') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_status') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_attachment') }}</th>
                        <th
                            class="px-5 py-3 border-b-2 bg-blue-100 text-left text-sm font-bold text-blue-800 uppercase">
                            {{ __('text.contract_creator') }}</th>
                        <th class="px-5 py-3 border-b-2 bg-blue-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contracts as $contract)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 border-b">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4 border-b font-semibold">{{ $contract->contract_number }}</td>
                            <td class="px-5 py-4 border-b">{{ $contract->client->name ?? __('text.none') }}</td>
                            <td class="px-5 py-4 border-b">{{ $contract->project->name ?? __('text.none') }}</td>
                            <td class="px-5 py-4 border-b">{{ optional($contract->start_date)->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 border-b">{{ optional($contract->end_date)->format('Y-m-d') }}</td>
                            <td class="px-5 py-4 border-b">
                                @switch($contract->status)
                                    @case('active')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800 font-semibold">{{ __('text.status_active') }}</span>
                                    @break

                                    @case('pending')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800 font-semibold">{{ __('text.status_pending') }}</span>
                                    @break

                                    @case('expired')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-800 font-semibold">{{ __('text.status_expired') }}</span>
                                    @break
                                @endswitch
                            </td>
                            <td class="px-7 py-4 border-b ">
                                @if ($contract->attachment)
                                    <a href="{{ asset('storage/' . $contract->attachment) }}" target="_blank"
                                        class="text-blue-600 underline"> <i class="fas fa-eye"></i></a>
                                @else
                                    <span class="text-gray-400">{{ __('text.none') }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 border-b">{{ $contract->creator->name ?? __('text.none') }}</td>
                            <td class="px-5 py-4 border-b text-right ">
                                <a href="{{ route('contracts.show', $contract->id) }}"
                                    class="text-green-500 hover:text-green-700 me-3 font-medium">
                                 <i class="fas fa-eye"></i> {{ __('text.view') }}
                                </a>
                                @can('edit contracts')
                                    
                                <a href="{{ route('contracts.edit', $contract->id) }}"
                                    class="text-blue-500 hover:text-blue-700 me-3 font-medium">
                                  <i class="fas fa-edit"></i>    {{ __('text.edit_btn') }}
                                </a>
                                @endcan
                                @can('delete contracts')
                                    
                                <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('{{ __('text.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                        <i class="fas fa-trash-alt"></i> {{ __('text.delete_btn') }}
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            <div class="p-4 bg-white border-t">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
