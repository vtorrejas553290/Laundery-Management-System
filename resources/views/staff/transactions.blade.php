<x-staff-layout>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Transactions</h1>
                <p class="text-gray-600">Manage laundry transactions</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Transaction
            </button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Weight (kg)</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Loads</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $transaction->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->serviceType->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->staff->first_name }} {{ $transaction->staff->last_name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $statusName = $transaction->status->status_name ?? 'Unknown';
                                        $statusClass = match($statusName) {
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'In Progress' => 'bg-blue-100 text-blue-800',
                                            'Completed' => 'bg-green-100 text-green-800',
                                            'Cancelled' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusName }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ number_format($transaction->weight, 2) }} kg</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $transaction->number_of_loads }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewTransaction('{{ $transaction->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="editTransaction('{{ $transaction->id }}')" class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteTransaction('{{ $transaction->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-gray-500">No transactions found. Click "Create Transaction" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Transaction Modal -->
    <div id="transactionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Create New Transaction</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="transactionForm">
                    @csrf
                    <input type="hidden" id="transaction_id" name="transaction_id">
                    
                    <div style="padding: 1.5rem;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Customer -->
                            <div>
                                <label for="customer_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Customer <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="customer_id" id="customer_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;">
                                    <option value="">Select customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Service Type -->
                            <div>
                                <label for="service_type_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Service Type <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="service_type_id" id="service_type_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;" data-price="">
                                    <option value="">Select service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price_per_load }}">{{ $service->name }} - ₱{{ number_format($service->price_per_load, 2) }}/load</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Staff -->
                            <div>
                                <label for="staff_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Staff <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="staff_id" id="staff_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;">
                                    <option value="">Select staff</option>
                                    @foreach($staff as $staffMember)
                                        <option value="{{ $staffMember->id }}">{{ $staffMember->first_name }} {{ $staffMember->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label for="status_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Status <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="status_id" id="status_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;">
                                    <option value="">Select status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Weight -->
                            <div>
                                <label for="weight" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Weight (kg) <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="number" step="0.01" name="weight" id="weight" required min="0.01" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;" placeholder="Enter weight in kg">
                                <p class="text-xs text-gray-500 mt-1">1 load = 6kg. Loads will be calculated automatically.</p>
                            </div>
                            
                            <!-- Number of Loads (Auto-calculated) -->
                            <div>
                                <label for="number_of_loads" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Number of Loads
                                </label>
                                <input type="number" name="number_of_loads" id="number_of_loads" readonly style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: #f3f4f6; cursor: not-allowed;" placeholder="Auto-calculated from weight">
                            </div>
                            
                            <!-- Extra Items Section -->
                            <div class="md:col-span-2">
                                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Extra Items
                                </label>
                                <div class="flex gap-2 items-center mb-2">
                                    <select id="extra_item_select" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="">Select extra item</option>
                                        @foreach($extraItems as $item)
                                            <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->item_name }} - ₱{{ number_format($item->price, 2) }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" id="extra_item_quantity" placeholder="Qty" min="1" value="1" class="w-20 px-3 py-2 border border-gray-300 rounded-lg">
                                    <button type="button" onclick="addExtraItem()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">Add</button>
                                </div>
                                <div id="extra_items_list" class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-500">No extra items added</p>
                                </div>
                                <input type="hidden" id="extra_items_data" name="extra_items_data" value="[]">
                            </div>
                            
                            <!-- Remarks -->
                            <div class="md:col-span-2">
                                <label for="remarks" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Remarks
                                </label>
                                <textarea name="remarks" id="remarks" rows="2" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; font-family: inherit;" placeholder="Enter remarks (optional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Total Amount Display -->
                        <div class="mt-4" style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.875rem; font-weight: 500; color: #1e40af;">Total Amount (Service + Extra Items):</span>
                                <span id="total_amount_display" style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="submitButtonText">Create Transaction</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Transaction Modal -->
    <div id="viewTransactionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Transaction Details</h3>
                    <button onclick="closeViewModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;" id="viewTransactionContent">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Transaction ID</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_transaction_id">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_date">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_customer">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Service Type</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_service">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Staff</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_staff">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-lg font-semibold" id="view_status">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Weight</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_weight">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Number of Loads</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_loads">-</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Extra Items</p>
                            <div id="view_extra_items" class="text-gray-900">-</div>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-2xl font-bold text-blue-600" id="view_total">-</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Remarks</p>
                            <p class="text-gray-700" id="view_remarks">-</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                    <button type="button" onclick="closeViewModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById('transactionModal');
        let form = document.getElementById('transactionForm');
        let modalTitle = document.getElementById('modal-title');
        let submitButtonText = document.getElementById('submitButtonText');
        let serviceSelect = document.getElementById('service_type_id');
        let weightInput = document.getElementById('weight');
        let loadsDisplay = document.getElementById('number_of_loads');
        let totalDisplay = document.getElementById('total_amount_display');
        let extraItemsList = [];
        
        const LOAD_PER_KG = 6;

        function calculateLoads() {
            const weight = parseFloat(weightInput.value) || 0;
            const loads = Math.ceil(weight / LOAD_PER_KG);
            loadsDisplay.value = loads;
            return loads;
        }
        
        function calculateTotal() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
            const loads = calculateLoads();
            const serviceTotal = price * loads;
            
            let extraTotal = 0;
            extraItemsList.forEach(item => {
                extraTotal += item.subtotal;
            });
            
            const total = serviceTotal + extraTotal;
            totalDisplay.textContent = `₱${total.toFixed(2)}`;
            return total;
        }
        
        function addExtraItem() {
            const select = document.getElementById('extra_item_select');
            const selectedOption = select.options[select.selectedIndex];
            const quantity = parseInt(document.getElementById('extra_item_quantity').value) || 1;
            
            if (!selectedOption.value) {
                alert('Please select an extra item');
                return;
            }
            
            const itemId = selectedOption.value;
            const itemName = selectedOption.text.split(' - ')[0];
            const itemPrice = parseFloat(selectedOption.dataset.price);
            const subtotal = itemPrice * quantity;
            
            const existingIndex = extraItemsList.findIndex(item => item.id === itemId);
            if (existingIndex !== -1) {
                extraItemsList[existingIndex].quantity += quantity;
                extraItemsList[existingIndex].subtotal = extraItemsList[existingIndex].price * extraItemsList[existingIndex].quantity;
            } else {
                extraItemsList.push({
                    id: itemId,
                    name: itemName,
                    price: itemPrice,
                    quantity: quantity,
                    subtotal: subtotal
                });
            }
            
            updateExtraItemsList();
            calculateTotal();
            select.value = '';
            document.getElementById('extra_item_quantity').value = '1';
        }
        
        function removeExtraItem(index) {
            extraItemsList.splice(index, 1);
            updateExtraItemsList();
            calculateTotal();
        }
        
        function updateExtraItemsList() {
            const container = document.getElementById('extra_items_list');
            const hiddenInput = document.getElementById('extra_items_data');
            
            if (extraItemsList.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500">No extra items added</p>';
                hiddenInput.value = '[]';
                return;
            }
            
            let html = '';
            extraItemsList.forEach((item, index) => {
                html += `
                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                        <div>
                            <span class="font-medium">${item.name}</span>
                            <span class="text-sm text-gray-600"> x${item.quantity}</span>
                            <span class="text-sm text-gray-600 ml-2">₱${item.subtotal.toFixed(2)}</span>
                        </div>
                        <button type="button" onclick="removeExtraItem(${index})" class="text-red-600 hover:text-red-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
            hiddenInput.value = JSON.stringify(extraItemsList);
        }

        serviceSelect.addEventListener('change', calculateTotal);
        weightInput.addEventListener('input', function() {
            calculateLoads();
            calculateTotal();
        });

        function openAddModal() {
            document.getElementById('transaction_id').value = '';
            form.reset();
            extraItemsList = [];
            updateExtraItemsList();
            totalDisplay.textContent = '₱0.00';
            loadsDisplay.value = '';
            weightInput.value = '';
            modalTitle.textContent = 'Create New Transaction';
            submitButtonText.textContent = 'Create Transaction';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        async function editTransaction(id) {
            try {
                const response = await fetch(`/staff/transactions/${id}`);
                const transaction = await response.json();
                
                document.getElementById('transaction_id').value = transaction.id;
                document.getElementById('customer_id').value = transaction.customer_id;
                document.getElementById('service_type_id').value = transaction.service_type_id;
                document.getElementById('staff_id').value = transaction.staff_id;
                document.getElementById('status_id').value = transaction.status_id;
                document.getElementById('weight').value = transaction.weight || 0;
                document.getElementById('remarks').value = transaction.remarks || '';
                
                extraItemsList = [];
                if (transaction.extra_items_formatted && transaction.extra_items_formatted.length > 0) {
                    transaction.extra_items_formatted.forEach(item => {
                        extraItemsList.push({
                            id: item.id,
                            name: item.item_name,
                            price: item.price,
                            quantity: item.quantity,
                            subtotal: item.subtotal
                        });
                    });
                }
                updateExtraItemsList();
                calculateLoads();
                calculateTotal();
                
                modalTitle.textContent = 'Edit Transaction';
                submitButtonText.textContent = 'Update Transaction';
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching transaction for edit:', error);
                alert('Error loading transaction for editing: ' + error.message);
            }
        }
        
        async function viewTransaction(id) {
            try {
                const response = await fetch(`/staff/transactions/${id}`);
                const transaction = await response.json();
                
                document.getElementById('view_transaction_id').textContent = transaction.id;
                document.getElementById('view_date').textContent = transaction.transaction_date ? new Date(transaction.transaction_date).toLocaleDateString() : '-';
                document.getElementById('view_customer').textContent = transaction.customer_name || '-';
                document.getElementById('view_service').textContent = transaction.service_name || '-';
                document.getElementById('view_staff').textContent = transaction.staff_name || '-';
                document.getElementById('view_weight').textContent = transaction.weight ? `${parseFloat(transaction.weight).toFixed(2)} kg` : '0 kg';
                document.getElementById('view_loads').textContent = transaction.number_of_loads || 0;
                document.getElementById('view_total').textContent = `₱${parseFloat(transaction.total_amount || 0).toFixed(2)}`;
                document.getElementById('view_remarks').textContent = transaction.remarks || 'No remarks';
                
                const statusName = transaction.status_name || 'Pending';
                let statusColor = '';
                if(statusName == 'Pending') statusColor = 'text-yellow-600';
                else if(statusName == 'In Progress') statusColor = 'text-blue-600';
                else if(statusName == 'Completed') statusColor = 'text-green-600';
                else statusColor = 'text-gray-600';
                
                const statusSpan = document.getElementById('view_status');
                statusSpan.textContent = statusName;
                statusSpan.className = `text-lg font-semibold ${statusColor}`;
                
                const extraItemsDiv = document.getElementById('view_extra_items');
                if (transaction.extra_items_formatted && transaction.extra_items_formatted.length > 0) {
                    let extraHtml = '<div class="space-y-1">';
                    transaction.extra_items_formatted.forEach(item => {
                        extraHtml += `<div class="text-sm">${item.item_name} x${item.quantity} - ₱${item.subtotal.toFixed(2)}</div>`;
                    });
                    extraHtml += '</div>';
                    extraItemsDiv.innerHTML = extraHtml;
                } else {
                    extraItemsDiv.innerHTML = '<span class="text-gray-500">No extra items</span>';
                }
                
                document.getElementById('viewTransactionModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching transaction:', error);
                alert('Error loading transaction details');
            }
        }
        
        function closeViewModal() {
            document.getElementById('viewTransactionModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#transactionForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const transactionId = document.getElementById('transaction_id').value;
            const url = transactionId ? `/staff/transactions/${transactionId}` : '/staff/transactions';
            
            const loads = calculateLoads();
            const total = calculateTotal();
            
            const formData = {
                customer_id: document.getElementById('customer_id').value,
                service_type_id: document.getElementById('service_type_id').value,
                staff_id: document.getElementById('staff_id').value,
                status_id: document.getElementById('status_id').value,
                weight: document.getElementById('weight').value,
                number_of_loads: loads,
                total_amount: total,
                remarks: document.getElementById('remarks').value,
                extra_items: extraItemsList,
                _token: '{{ csrf_token() }}',
                _method: transactionId ? 'PUT' : 'POST'
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

        async function deleteTransaction(id) {
            if (confirm('Are you sure you want to delete this transaction?')) {
                try {
                    const response = await fetch(`/staff/transactions/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Error deleting transaction');
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
        
        const viewModal = document.getElementById('viewTransactionModal');
        if (viewModal) {
            viewModal.addEventListener('click', function(event) {
                if (event.target === viewModal) {
                    closeViewModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewModal && viewModal.style.display === 'block') {
                    closeViewModal();
                }
            }
        });
    </script>
</x-staff-layout>