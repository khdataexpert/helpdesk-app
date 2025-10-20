<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            {{ __('text.manage_permissions_for') }} <span class="text-blue-600">{{ $user->name }}</span>
        </h2>

        <form action="{{ route('users.permissions.update', $user->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-lg border">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-3 gap-4">
                @foreach ($permissions as $permission)
                    <label class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg hover:bg-gray-100 transition">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                               @if($user->hasPermissionTo($permission->name)) checked @endif
                               class="form-checkbox text-blue-600 focus:ring-blue-500 rounded">
                        <span class="text-gray-800">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow-md">
                    {{ __('text.save_permissions') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
