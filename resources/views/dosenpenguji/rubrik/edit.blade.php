{{-- resources/views/dosenpenguji/rubrik/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rubrik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosenpenguji.rubrik.update', $rubrik->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Mata Kuliah -->
                        <div class="mb-4">
                            <label for="kode_mk" class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                            <select name="kode_mk" id="kode_mk" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matakuliahOptions as $mk)
                                    <option value="{{ $mk->kode_mk }}" {{ old('kode_mk', $rubrik->kode_mk) == $mk->kode_mk ? 'selected' : '' }}>
                                        {{ $mk->nama_mk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Rubrik -->
                        <div class="mb-4">
                            <label for="nama_rubrik" class="block text-sm font-medium text-gray-700">Nama Rubrik</label>
                            <input type="text" name="nama_rubrik" id="nama_rubrik" value="{{ old('nama_rubrik', $rubrik->nama_rubrik) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <!-- Bobot -->
                        <div class="mb-4">
                            <label for="bobot" class="block text-sm font-medium text-gray-700">Bobot (%)</label>
                            <input type="number" name="bobot" id="bobot" value="{{ old('bobot', $rubrik->bobot) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required min="0" max="100">
                        </div>

                        <!-- Urutan -->
                        <div class="mb-4">
                            <label for="urutan" class="block text-sm font-medium text-gray-700">Urutan</label>
                            <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $rubrik->urutan) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required min="0">
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('deskripsi', $rubrik->deskripsi) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('dosenpenguji.rubrik.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
