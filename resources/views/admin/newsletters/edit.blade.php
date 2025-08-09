@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.newsletters.index') }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                ← Back to Newsletter Management
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Newsletter Subscriber</h1>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('admin.newsletters.update', $newsletter) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address *
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $newsletter->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ old('status', $newsletter->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="unsubscribed" {{ old('status', $newsletter->status) == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-2">
                        Source
                    </label>
                    <select id="source" 
                            name="source"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="admin" {{ old('source', $newsletter->source) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="website" {{ old('source', $newsletter->source) == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="footer" {{ old('source', $newsletter->source) == 'footer' ? 'selected' : '' }}>Footer</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subscribed Date
                    </label>
                    <p class="text-gray-600">{{ $newsletter->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.newsletters.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Update Subscriber
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 