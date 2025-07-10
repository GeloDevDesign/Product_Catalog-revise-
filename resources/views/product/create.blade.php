@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10" id="appContainer">

   
    <div id="loadingScreen" class="flex justify-center items-center min-h-96">
        <span class="loading loading-spinner loading-lg text-blue-500"></span>
    </div>

    
    <div id="unauthorizedScreen" class="hidden text-center min-h-96 flex flex-col justify-center items-center gap-4">
        <h2 class="text-3xl font-bold text-red-600">403 - Unauthenticated</h2>
        <p class="text-gray-600 mb-4">You are not authorized to view this page. Please log in first.</p>
        <button onclick="window.location.href='/login'" class="btn btn-primary">
            Go to Login
        </button>
    </div>

    <div id="mainContent" class="hidden">
        <a href="/" class="inline-block mb-4">
            <button class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back
            </button>
        </a>

        <div class="w-full bg-gray-100 p-6 rounded-lg">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">
                Add New Product <span class="text-red-600">Laravel</span>
            </h3>

            <form id="productForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">Information</h3>

                        <div class="mb-4">
                            <label class="label">Name</label>
                            <input type="text" name="name" placeholder="Product name" class="input w-full" id="productName" />
                            <p id="nameError" class="text-red-600 opacity-70 mt-2 hidden"></p>
                        </div>

                        <div class="mb-4">
                            <label class="label">Sell Price</label>
                            <input type="number" name="sell_price" placeholder="Your selling price" class="input w-full" id="sellPrice" step="0.01" />
                            <p id="priceError" class="text-red-600 opacity-70 mt-2 hidden"></p>
                        </div>

                        <div class="mt-4">
                            <label class="label text-sm">Status</label>
                            <select name="is_active" class="select w-full" id="productStatus">
                                <option value="0">Inactive</option>
                                <option value="1" selected>Active</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium mb-2">Categories</h3>
                        <div class="max-h-64 overflow-y-auto rounded-lg p-2 bg-white" id="categoriesContainer">
                            <div class="text-center py-4">
                                <span class="loading loading-spinner loading-md"></span>
                                Loading categories...
                            </div>
                        </div>
                        <p id="categoriesError" class="text-red-600 opacity-70 mt-2 hidden"></p>
                    </div>
                </div>

                <div class="flex justify-end items-center gap-2 mt-6">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Add new product
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    checkIfAuthenticated();
});

async function checkIfAuthenticated() {
    const loadingScreen = document.getElementById('loadingScreen');
    const unauthorizedScreen = document.getElementById('unauthorizedScreen');
    const mainContent = document.getElementById('mainContent');

    try {
        const response = await axios.get('/api/user', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

      

      
        loadingScreen.classList.add('hidden');
        mainContent.classList.remove('hidden');

        loadCategories();
        document.getElementById('productForm').addEventListener('submit', submitForm);

    } catch (error) {
        
       
        loadingScreen.classList.add('hidden');
        unauthorizedScreen.classList.remove('hidden');
    }
}

async function loadCategories() {
    try {
        const response = await axios.get('/api/categories', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        const container = document.getElementById('categoriesContainer');
        container.innerHTML = '';

        response.data.forEach(category => {
            const categoryDiv = document.createElement('div');
            categoryDiv.className = 'flex-shrink-0';
            categoryDiv.innerHTML = `
                <label class="flex items-center gap-2 px-2 py-1 hover:bg-blue-200 rounded">
                    <input type="checkbox" name="category_ids[]" value="${category.id}" class="checkbox checkbox-primary category-checkbox" />
                    <span class="whitespace-nowrap">${category.name}</span>
                </label>
            `;
            container.appendChild(categoryDiv);
        });
    } catch (error) {
      
        document.getElementById('categoriesContainer').innerHTML = `
            <div class="text-red-500 p-2">Failed to load categories. Please refresh the page.</div>
        `;
    }
}

async function submitForm(e) {
    e.preventDefault();
    clearErrors();

    const formData = {
        name: document.getElementById('productName').value,
        sell_price: document.getElementById('sellPrice').value,
        is_active: document.getElementById('productStatus').value,
        category_ids: Array.from(document.querySelectorAll('.category-checkbox:checked')).map(checkbox => checkbox.value)
    };

    const submitBtn = document.getElementById('submitBtn');

    try {
        await axios.post('/api/products', formData, {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            withCredentials: true
        });

        window.location.href = '/';

    } catch (error) {
       

        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;

            if (errors.name) {
                document.getElementById('nameError').textContent = errors.name[0];
                document.getElementById('nameError').classList.remove('hidden');
                document.getElementById('productName').classList.add('input-error');
            }

            if (errors.sell_price) {
                document.getElementById('priceError').textContent = errors.sell_price[0];
                document.getElementById('priceError').classList.remove('hidden');
                document.getElementById('sellPrice').classList.add('input-error');
            }

            if (errors.category_ids) {
                document.getElementById('categoriesError').textContent = errors.category_ids[0];
                document.getElementById('categoriesError').classList.remove('hidden');
                document.getElementById('categoriesContainer').classList.add('border', 'border-red-200');
            }
        } else {
            console.log(error);
        }
    }
}

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });

    document.getElementById('productName').classList.remove('input-error');
    document.getElementById('sellPrice').classList.remove('input-error');
    document.getElementById('categoriesContainer').classList.remove('border', 'border-red-200');
}
</script>
@endsection
