<x-app-layout>
    <div class="p-8">
        <!-- Services Section -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Services</h1>
                <p class="text-gray-600">Manage service types and pricing</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Service
            </button>
        </div>

        <!-- Services Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Services</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Load</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($serviceTypes as $service)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $service->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $service->name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($service->category)
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $service->category->category_name }}
                                            </span>
                                            @if($service->category->description)
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs text-gray-500 max-w-xs truncate" title="{{ $service->category->description }}">
                                                    {{ $service->category->description }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">No category</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">₱{{ number_format($service->price_per_load, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewService('{{ $service->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openEditModal(@json($service))' class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteService('{{ $service->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">No services found. Click "Add Service" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Modal (Create/Edit) -->
    <div id="serviceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 28rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add New Service</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="serviceForm">
                    @csrf
                    <input type="hidden" id="service_id" name="service_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Service Name <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter service name"
                                >
                            </div>
                            
                            <div>
                                <label for="category_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Category <span style="color: #ef4444;">*</span>
                                </label>
                                <select 
                                    name="category_id" 
                                    id="category_id" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                >
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div id="category_description" style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;"></div>
                            </div>
                            
                            <div>
                                <label for="price_per_load" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Price per Load (₱) <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="price_per_load" 
                                    id="price_per_load" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="0.00"
                                >
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="submitButtonText">Save Service</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Service Modal -->
    <div id="viewServiceModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Service Details</h3>
                    <button onclick="closeViewServiceModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_service_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_service_name">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category</label>
                                <p class="mt-1 text-base text-gray-900" id="view_service_category">-</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Load</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600" id="view_service_price">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category Description</label>
                                <p class="mt-1 text-base text-gray-600" id="view_category_description">-</p>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-green-600 uppercase tracking-wider">Status</label>
                                <p class="mt-1 text-base font-semibold text-green-700" id="view_service_status">Active</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                    <button type="button" onclick="closeViewServiceModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editServiceFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Service
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let serviceModal = document.getElementById('serviceModal');
        let serviceForm = document.getElementById('serviceForm');
        let serviceModalTitle = document.getElementById('modal-title');
        let serviceSubmitText = document.getElementById('submitButtonText');
        let currentViewService = null;
        
        // Category data for description lookup
        const categoriesData = @json($categories);

        function updateCategoryDescription() {
            const categoryId = document.getElementById('category_id').value;
            const descriptionDiv = document.getElementById('category_description');
            
            if (categoryId) {
                const selectedCategory = categoriesData.find(cat => cat.id == categoryId);
                if (selectedCategory && selectedCategory.description) {
                    descriptionDiv.innerHTML = `<span class="text-gray-500">📝 ${selectedCategory.description}</span>`;
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.innerHTML = '';
                    descriptionDiv.style.display = 'none';
                }
            } else {
                descriptionDiv.innerHTML = '';
                descriptionDiv.style.display = 'none';
            }
        }

        document.getElementById('category_id').addEventListener('change', updateCategoryDescription);

        function openAddModal() {
            document.getElementById('service_id').value = '';
            serviceForm.reset();
            document.getElementById('category_description').innerHTML = '';
            serviceModalTitle.textContent = 'Add New Service';
            serviceSubmitText.textContent = 'Save Service';
            serviceModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(service) {
            document.getElementById('service_id').value = service.id;
            document.getElementById('name').value = service.name;
            document.getElementById('category_id').value = service.category_id;
            document.getElementById('price_per_load').value = service.price_per_load;
            updateCategoryDescription();
            serviceModalTitle.textContent = 'Edit Service';
            serviceSubmitText.textContent = 'Update Service';
            serviceModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            serviceModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        async function viewService(id) {
            try {
                const response = await fetch(`/admin/service-types/${id}/json`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const service = await response.json();
                
                document.getElementById('view_service_id').textContent = service.id || '-';
                document.getElementById('view_service_name').textContent = service.name || '-';
                document.getElementById('view_service_category').textContent = service.category_name || '-';
                document.getElementById('view_service_price').textContent = `₱${parseFloat(service.price_per_load || 0).toFixed(2)}`;
                document.getElementById('view_category_description').textContent = service.category_description || 'No description available';
                document.getElementById('view_service_status').textContent = 'Active';
                
                currentViewService = service;
                document.getElementById('viewServiceModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching service:', error);
                alert('Error loading service details: ' + error.message);
            }
        }
        
        function closeViewServiceModal() {
            document.getElementById('viewServiceModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewService = null;
        }
        
        function editServiceFromView() {
            if (currentViewService) {
                closeViewServiceModal();
                openEditModal(currentViewService);
            }
        }

        serviceForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#serviceForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const serviceId = document.getElementById('service_id').value;
            const url = serviceId ? `/admin/service-types/${serviceId}` : '/admin/service-types';
            
            const formData = {
                name: document.getElementById('name').value,
                category_id: document.getElementById('category_id').value,
                price_per_load: document.getElementById('price_per_load').value,
                _token: '{{ csrf_token() }}',
                _method: serviceId ? 'PUT' : 'POST'
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

        async function deleteService(id) {
            if (confirm('Are you sure you want to delete this service?')) {
                try {
                    const response = await fetch(`/admin/service-types/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Error deleting service');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        serviceModal.addEventListener('click', function(event) {
            if (event.target === serviceModal) {
                closeModal();
            }
        });
        
        const viewServiceModal = document.getElementById('viewServiceModal');
        if (viewServiceModal) {
            viewServiceModal.addEventListener('click', function(event) {
                if (event.target === viewServiceModal) {
                    closeViewServiceModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (serviceModal.style.display === 'block') {
                    closeModal();
                }
                if (viewServiceModal && viewServiceModal.style.display === 'block') {
                    closeViewServiceModal();
                }
            }
        });
    </script>
</x-app-layout>