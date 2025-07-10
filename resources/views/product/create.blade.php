@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <a href="/" class="inline-block">
        <button class="btn btn-secondary mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back
        </button>
    </a>

    <div class="w-full h-full">
        <div class="w-full flex flex-col mt-8">
            <div class="w-full bg-gray-100 p-6 rounded-lg">
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">
                    Add New Product
                </h3>

                <form method="POST" action="{{ route('product.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-2">Information</h3>
                            
                            <div class="mb-4">
                                <label class="label">Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    placeholder="Product name" 
                                    class="input w-full @error('name') input-error @enderror" 
                                    value="{{ old('name') }}"
                                />
                                @error('name')
                                    <p class="text-red-600 opacity-70 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="label">Sell Price</label>
                                <input 
                                    type="number" 
                                    name="sell_price" 
                                    placeholder="Your selling price" 
                                    class="input w-full @error('sell_price') input-error @enderror" 
                                    value="{{ old('sell_price', 0) }}"
                                    step="0.01"
                                />
                                @error('sell_price')
                                    <p class="text-red-600 opacity-70 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mt-4">
                                <label class="label text-sm">Status</label>
                                <select name="is_active" class="select w-full">
                                    <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium mb-2">Categories</h3>
                            <div class="max-h-64 overflow-y-auto rounded-lg p-2 bg-white @error('category_ids') border border-red-200 @enderror">
                                @foreach($categories as $category)
                                    <div class="flex-shrink-0">
                                        <label class="flex items-center gap-2 px-2 py-1 hover:bg-blue-200 rounded">
                                            <input 
                                                type="checkbox" 
                                                name="category_ids[]" 
                                                value="{{ $category->id }}"
                                                class="checkbox checkbox-primary"
                                                @if(is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) checked @endif
                                            />
                                            <span class="whitespace-nowrap">{{ $category->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('category_ids')
                                <p class="text-red-600 opacity-70 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-2 mt-6">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <path d="M22 4 12 14.01l-3-3"/>
                            </svg>
                            Add new product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection