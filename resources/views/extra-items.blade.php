<x-app-layout>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Extra Items</h1>
                <p class="text-gray-600">Manage additional items and add-ons</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Item
            </button>
        </div>

        <!-- Extra Items Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Extra Items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Item ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($extraItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->item_name }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('M d, Y') : '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewItem('{{ $item->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openEditModal(@json($item))' class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteItem('{{ $item->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">No extra items found. Click "Add Item" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Statistics Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Total Items</h3>
                <p class="text-2xl font-bold text-blue-900">{{ $extraItems->count() }}</p>
            </div>
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-sm font-medium text-green-800 mb-2">Average Price</h3>
                <p class="text-2xl font-bold text-green-900">₱{{ number_format($extraItems->avg('price') ?? 0, 2) }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg border border-purple-200 p-6">
                <h3 class="text-sm font-medium text-purple-800 mb-2">Most Expensive</h3>
                <p class="text-2xl font-bold text-purple-900">₱{{ number_format($extraItems->max('price') ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Extra Item Modal (Create/Edit) -->
    <div id="itemModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 28rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add New Extra Item</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="itemForm">
                    @csrf
                    <input type="hidden" id="item_id" name="item_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <label for="item_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Item Name <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="item_name" 
                                    id="item_name" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter item name"
                                >
                            </div>
                            
                            <div>
                                <label for="price" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Price (₱) <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="price" 
                                    id="price" 
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
                            <span id="submitButtonText">Save Item</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Extra Item Modal -->
    <div id="viewItemModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 40rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Item Details</h3>
                    <button onclick="closeViewItemModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Item ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_item_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_item_name">-</p>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Price</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600" id="view_item_price">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information Section -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Additional Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_created_date">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_updated_date">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                    <button type="button" onclick="closeViewItemModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editItemFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById('itemModal');
        let form = document.getElementById('itemForm');
        let modalTitle = document.getElementById('modal-title');
        let submitButtonText = document.getElementById('submitButtonText');
        let currentViewItem = null;

        function openAddModal() {
            document.getElementById('item_id').value = '';
            form.reset();
            modalTitle.textContent = 'Add New Extra Item';
            submitButtonText.textContent = 'Save Item';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(item) {
            document.getElementById('item_id').value = item.id;
            document.getElementById('item_name').value = item.item_name;
            document.getElementById('price').value = item.price;
            modalTitle.textContent = 'Edit Extra Item';
            submitButtonText.textContent = 'Update Item';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        async function viewItem(id) {
            try {
                const response = await fetch(`/admin/extra-items/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const item = await response.json();
                console.log('Item data:', item);
                
                // Populate view modal
                document.getElementById('view_item_id').textContent = item.id || '-';
                document.getElementById('view_item_name').textContent = item.item_name || '-';
                document.getElementById('view_item_price').textContent = `₱${parseFloat(item.price || 0).toFixed(2)}`;
                
                // Created Date
                if (item.created_at) {
                    const date = new Date(item.created_at);
                    document.getElementById('view_created_date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                } else {
                    document.getElementById('view_created_date').textContent = '-';
                }
                
                // Updated Date
                if (item.updated_at && item.updated_at !== item.created_at) {
                    const updatedDate = new Date(item.updated_at);
                    document.getElementById('view_updated_date').textContent = updatedDate.toLocaleDateString() + ' ' + updatedDate.toLocaleTimeString();
                } else if (item.created_at) {
                    document.getElementById('view_updated_date').textContent = 'Not updated yet';
                } else {
                    document.getElementById('view_updated_date').textContent = '-';
                }
                
                // Store current item for edit button
                currentViewItem = item;
                
                // Show modal
                document.getElementById('viewItemModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching item:', error);
                alert('Error loading item details: ' + error.message);
            }
        }
        
        function closeViewItemModal() {
            document.getElementById('viewItemModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewItem = null;
        }
        
        function editItemFromView() {
            if (currentViewItem) {
                closeViewItemModal();
                openEditModal(currentViewItem);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#itemForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const itemId = document.getElementById('item_id').value;
            const url = itemId ? `/admin/extra-items/${itemId}` : '/admin/extra-items';
            
            const formData = {
                item_name: document.getElementById('item_name').value,
                price: document.getElementById('price').value,
                _token: '{{ csrf_token() }}',
                _method: itemId ? 'PUT' : 'POST'
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

        async function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                try {
                    const response = await fetch(`/admin/extra-items/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Error deleting item');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        const viewItemModal = document.getElementById('viewItemModal');
        viewItemModal.addEventListener('click', function(event) {
            if (event.target === viewItemModal) {
                closeViewItemModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewItemModal.style.display === 'block') {
                    closeViewItemModal();
                }
            }
        });
    </script>
</x-app-layout>