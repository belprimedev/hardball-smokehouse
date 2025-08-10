@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.newsletters.index') }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                ← Back to Newsletter Management
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Newsletter Subscriber Details</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <p class="text-lg text-gray-900">{{ $newsletter->email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $newsletter->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($newsletter->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Source
                    </label>
                    <p class="text-gray-900">{{ ucfirst($newsletter->source) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subscribed Date
                    </label>
                    <p class="text-gray-900">{{ $newsletter->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Last Updated
                    </label>
                    <p class="text-gray-900">{{ $newsletter->updated_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Subscriber ID
                    </label>
                    <p class="text-gray-900">#{{ $newsletter->id }}</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.newsletters.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    Back to List
                </a>
                <a href="{{ route('admin.newsletters.edit', $newsletter) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Edit Subscriber
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 