<x-app-layout>
    <div class="p-8">
        <!-- Categories Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Categories</h1>
                    <p class="text-gray-600">Manage service categories</p>
                </div>
                <button onclick="openCategoryModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Category
                </button>
            </div>

            <!-- Categories Table -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Categories</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category ID</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Count</th>
                                    <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $category->id }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $category->category_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $category->description ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $category->serviceTypes->count() }} services
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button onclick="viewCategory('{{ $category->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button onclick='openEditCategoryModal(@json($category))' class="text-blue-600 hover:text-blue-800 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteCategory('{{ $category->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No categories found. Click "Add Category" to create one.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal (Create/Edit) -->
    <div id="categoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 28rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="categoryModalTitle">Add New Category</h3>
                    <button onclick="closeCategoryModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="categoryForm">
                    @csrf
                    <input type="hidden" id="category_id" name="category_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <label for="category_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Category Name <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="category_name" 
                                    id="category_name" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter category name"
                                >
                            </div>
                            
                            <div>
                                <label for="description" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Description
                                </label>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    rows="3"
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; font-family: inherit;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter category description (optional)"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeCategoryModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="categorySubmitText">Save Category</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Category Modal -->
    <div id="viewCategoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Category Details</h3>
                    <button onclick="closeViewCategoryModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_category_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_category_name">-</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service Count</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600" id="view_service_count">0</p>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-purple-600 uppercase tracking-wider">Created Date</label>
                                <p class="mt-1 text-base text-purple-700" id="view_category_created">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Description</label>
                        <div class="mt-2 text-base text-gray-700 bg-gray-50 p-3 rounded-lg" id="view_category_description">-</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                    <button type="button" onclick="closeViewCategoryModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editCategoryFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Category
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let categoryModal = document.getElementById('categoryModal');
        let categoryForm = document.getElementById('categoryForm');
        let categoryModalTitle = document.getElementById('categoryModalTitle');
        let categorySubmitText = document.getElementById('categorySubmitText');
        let currentViewCategory = null;

        function openCategoryModal() {
            document.getElementById('category_id').value = '';
            categoryForm.reset();
            categoryModalTitle.textContent = 'Add New Category';
            categorySubmitText.textContent = 'Save Category';
            categoryModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditCategoryModal(category) {
            document.getElementById('category_id').value = category.id;
            document.getElementById('category_name').value = category.category_name;
            document.getElementById('description').value = category.description || '';
            categoryModalTitle.textContent = 'Edit Category';
            categorySubmitText.textContent = 'Update Category';
            categoryModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeCategoryModal() {
            categoryModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        async function viewCategory(id) {
            try {
                const response = await fetch(`/admin/service-categories/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const category = await response.json();
                
                document.getElementById('view_category_id').textContent = category.id || '-';
                document.getElementById('view_category_name').textContent = category.category_name || '-';
                
                let description = category.description;
                if (!description || description === 'null' || description === '' || description === 'undefined') {
                    description = 'No description provided';
                }
                document.getElementById('view_category_description').innerHTML = description;
                
                const serviceCount = category.service_types_count || 0;
                document.getElementById('view_service_count').textContent = serviceCount;
                
                if (category.created_at) {
                    const date = new Date(category.created_at);
                    document.getElementById('view_category_created').textContent = date.toLocaleDateString();
                } else {
                    document.getElementById('view_category_created').textContent = '-';
                }
                
                currentViewCategory = category;
                document.getElementById('viewCategoryModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching category:', error);
                alert('Error loading category details: ' + error.message);
            }
        }
            
        function closeViewCategoryModal() {
            document.getElementById('viewCategoryModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewCategory = null;
        }
        
        function editCategoryFromView() {
            if (currentViewCategory) {
                closeViewCategoryModal();
                openEditCategoryModal(currentViewCategory);
            }
        }

        categoryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#categoryForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const categoryId = document.getElementById('category_id').value;
            const url = categoryId ? `/admin/service-categories/${categoryId}` : '/admin/service-categories';
            
            const formData = {
                category_name: document.getElementById('category_name').value,
                description: document.getElementById('description').value,
                _token: '{{ csrf_token() }}',
                _method: categoryId ? 'PUT' : 'POST'
            };
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    const data = await response.json();
                    alert('Error: ' + JSON.stringify(data.errors || data));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        async function deleteCategory(id) {
            if (confirm('Are you sure you want to delete this category? This will also delete all services under it.')) {
                try {
                    const response = await fetch(`/admin/service-categories/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        alert(data.message || 'Error deleting category');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        categoryModal.addEventListener('click', function(event) {
            if (event.target === categoryModal) {
                closeCategoryModal();
            }
        });
        
        const viewCategoryModal = document.getElementById('viewCategoryModal');
        if (viewCategoryModal) {
            viewCategoryModal.addEventListener('click', function(event) {
                if (event.target === viewCategoryModal) {
                    closeViewCategoryModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (categoryModal.style.display === 'block') {
                    closeCategoryModal();
                }
                if (viewCategoryModal && viewCategoryModal.style.display === 'block') {
                    closeViewCategoryModal();
                }
            }
        });
    </script>
</x-app-layout>