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

        <!-- Statistics Summary -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Total Transactions</h3>
                <p class="text-2xl font-bold text-blue-900" id="totalCount">0</p>
            </div>
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-sm font-medium text-green-800 mb-2">Total Revenue</h3>
                <p class="text-2xl font-bold text-green-900" id="totalRevenue">₱0.00</p>
            </div>
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">Pending</h3>
                <p class="text-2xl font-bold text-yellow-900" id="pendingCount">0</p>
            </div>
            <div class="bg-purple-50 rounded-lg border border-purple-200 p-6">
                <h3 class="text-sm font-medium text-purple-800 mb-2">Completed</h3>
                <p class="text-2xl font-bold text-purple-900" id="completedCount">0</p>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="mb-4 flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-4">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by customer, transaction ID..." 
                        class="w-80 px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <!-- Status Filter -->
                <select id="statusFilter" class="px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZT0iY3VycmVudENvbG9yIiBjbGFzcz0idy00IGgtNCI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNOC4yNSAxNUwxMiAxOC43NSAxNS43NSAxNW0tNy41LTZMMTIgNS4yNSAxNS43NSA5IiAvPjwvc3ZnPg=='); background-position: right 0.75rem center; background-size: 1rem;">
                    <option value="all">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                
                <!-- Clear Filters Button -->
                <button onclick="clearFilters()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Clear Filters
                </button>
            </div>
            
            <div class="text-sm text-gray-500">
                Showing <span id="visibleCount">0</span> of <span id="totalVisibleCount">0</span> transactions
            </div>
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
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Total</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Extra Total</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsTableBody" class="divide-y divide-gray-200">
                            @forelse($transactions->sortBy(function($transaction) {
                                return (int) substr($transaction->transaction_id, 2);
                            }) as $transaction)
                            @php
                                // Calculate service total and extra total
                                $serviceTotal = ($transaction->price_per_load ?? 0) * ($transaction->number_of_loads ?? 0);
                                // Note: extra_total will be loaded via AJAX
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200 transaction-row" 
                                data-status="{{ $transaction->transaction_status }}"
                                data-customer="{{ strtolower($transaction->customer_name) }}"
                                data-id="{{ strtolower($transaction->transaction_id) }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $transaction->transaction_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 customer-name">{{ $transaction->customer_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->service_type }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $transaction->staff_name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $statusName = $transaction->transaction_status ?? 'Unknown';
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
                                <td class="px-4 py-3 text-sm text-gray-600 transaction-date">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 weight">{{ number_format($transaction->weight, 2) }} kg</td>
                                <td class="px-4 py-3 text-sm text-gray-900 loads">{{ $transaction->number_of_loads }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 service-total">₱{{ number_format($serviceTotal, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 extra-total" id="extra_total_{{ $transaction->transaction_id }}">Loading...</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 total-amount" id="total_amount_{{ $transaction->transaction_id }}">₱{{ number_format($transaction->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewTransaction('{{ $transaction->transaction_id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="editTransaction('{{ $transaction->transaction_id }}')" class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteTransaction('{{ $transaction->transaction_id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noRecordsRow">
                                <td colspan="12" class="px-4 py-8 text-center text-gray-500">No transactions found. Click "Create Transaction" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 mt-4">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <button id="prevMobileBtn" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</button>
                        <button id="nextMobileBtn" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing <span id="pageStart" class="font-medium">0</span>
                                to <span id="pageEnd" class="font-medium">0</span>
                                of <span id="totalRecords" class="font-medium">0</span> results
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" id="paginationNumbers">
                                <!-- Pagination buttons will be generated here -->
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Transaction Modal (Scrollable) -->
    <div id="transactionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto; max-height: 90vh; display: flex; flex-direction: column;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; flex-shrink: 0;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Create New Transaction</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="transactionForm" style="overflow-y: auto; flex: 1;">
                    @csrf
                    <input type="hidden" id="transaction_id" name="transaction_id">
                    
                    <div style="padding: 1.5rem;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Customer - Searchable Dropdown -->
                            <div>
                                <label for="customer_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Customer <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="customer_search" placeholder="Search customer..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    autocomplete="off">
                                <select name="customer_id" id="customer_id" required style="display: none;">
                                    <option value="">Select customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" data-name="{{ strtolower($customer->first_name . ' ' . $customer->last_name) }}">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="customer_dropdown" class="absolute z-50 hidden bg-white border border-gray-300 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg" style="width: calc(100% - 2rem); min-width: 200px;">
                                    @foreach($customers as $customer)
                                        <div class="customer-option px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm" data-value="{{ $customer->id }}">
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Service Type -->
                            <div>
                                <label for="service_type_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Service Type <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="service_type_id" id="service_type_id" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;">
                                    <option value="">Select service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->price_per_load }}">{{ $service->name }} - ₱{{ number_format($service->price_per_load, 2) }}/load</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Staff (Hidden - Auto-set to logged in staff) -->
                            <div style="display: none;">
                                <input type="hidden" name="staff_id" id="staff_id" value="{{ auth()->guard('staff')->user()->id }}">
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
                            
                            <!-- Number of Loads (Auto-calculated - Display Only) -->
                            <div>
                                <label for="number_of_loads" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Number of Loads (Preview)
                                </label>
                                <input type="number" name="number_of_loads" id="number_of_loads" readonly style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: #f3f4f6; cursor: not-allowed;" placeholder="Auto-calculated from weight">
                                <p class="text-xs text-blue-600 mt-1">TRIGGER: Will be calculated by database trigger</p>
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
                                <p class="text-xs text-blue-600 mt-1">TRIGGER: Subtotal will be calculated by database trigger</p>
                            </div>
                            
                            <!-- Remarks -->
                            <div class="md:col-span-2">
                                <label for="remarks" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Remarks
                                </label>
                                <textarea name="remarks" id="remarks" rows="2" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; font-family: inherit;" placeholder="Enter remarks (optional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Service Total Display -->
                        <div class="mt-4" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.5rem; padding: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.875rem; font-weight: 500; color: #166534;">Service Total (Preview):</span>
                                <span id="service_total_display" style="font-size: 1.125rem; font-weight: 600; color: #166534;">₱0.00</span>
                            </div>
                        </div>
                        
                        <!-- Extra Items Total Display -->
                        <div class="mt-2" style="background-color: #fefce8; border: 1px solid #fef08a; border-radius: 0.5rem; padding: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.875rem; font-weight: 500; color: #854d0e;">Extra Items Total (Preview):</span>
                                <span id="extra_total_display" style="font-size: 1.125rem; font-weight: 600; color: #854d0e;">₱0.00</span>
                            </div>
                        </div>
                        
                        <!-- Grand Total Display -->
                        <div class="mt-2" style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.5rem; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 1rem; font-weight: 600; color: #1e40af;">GRAND TOTAL (Preview):</span>
                                <span id="total_amount_display" style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">₱0.00</span>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">TRIGGER: Actual total will be calculated by database trigger</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; flex-shrink: 0;">
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
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
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
                        <div>
                            <p class="text-sm text-gray-500">Service Total</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_service_total">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Extra Items Total</p>
                            <p class="text-lg font-semibold text-gray-900" id="view_extra_total">-</p>
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

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; position: sticky; bottom: 0; background: white;">
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
        let serviceTotalDisplay = document.getElementById('service_total_display');
        let extraTotalDisplay = document.getElementById('extra_total_display');
        let totalDisplay = document.getElementById('total_amount_display');
        let extraItemsList = [];
        
        // Pagination variables
        let currentPage = 1;
        const rowsPerPage = 10;
        let filteredRows = [];
        
        // Searchable Customer Dropdown
        const customerSearch = document.getElementById('customer_search');
        const customerSelect = document.getElementById('customer_id');
        const customerDropdown = document.getElementById('customer_dropdown');
        const customerOptions = document.querySelectorAll('.customer-option');
        
        function initCustomerSearch() {
            customerSearch.addEventListener('focus', () => {
                customerDropdown.classList.remove('hidden');
                filterCustomerOptions();
            });
            
            customerSearch.addEventListener('input', filterCustomerOptions);
            
            customerOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const value = option.dataset.value;
                    const text = option.textContent;
                    customerSelect.value = value;
                    customerSearch.value = text;
                    customerDropdown.classList.add('hidden');
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!customerSearch.contains(e.target) && !customerDropdown.contains(e.target)) {
                    customerDropdown.classList.add('hidden');
                }
            });
        }
        
        function filterCustomerOptions() {
            const searchTerm = customerSearch.value.toLowerCase();
            let hasVisible = false;
            
            customerOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    option.classList.remove('hidden');
                    hasVisible = true;
                } else {
                    option.classList.add('hidden');
                }
            });
            
            if (hasVisible && customerDropdown.classList.contains('hidden') === false) {
                customerDropdown.classList.remove('hidden');
            }
        }
        
        // Filter and Pagination Functions
        function filterTransactions() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.transaction-row');
            
            filteredRows = [];
            let totalRevenue = 0;
            let pendingCount = 0;
            let completedCount = 0;
            
            rows.forEach(row => {
                const customerName = row.querySelector('.customer-name')?.textContent.toLowerCase() || '';
                const transactionId = row.getAttribute('data-id') || '';
                const status = row.getAttribute('data-status') || '';
                const totalAmount = parseFloat(row.querySelector('.total-amount')?.textContent.replace('₱', '').replace(/,/g, '') || 0);
                
                let matchesSearch = customerName.includes(searchTerm) || transactionId.includes(searchTerm);
                let matchesStatus = statusFilter === 'all' || status === statusFilter;
                
                if (matchesSearch && matchesStatus) {
                    filteredRows.push(row);
                    totalRevenue += totalAmount;
                    if (status === 'Pending') pendingCount++;
                    if (status === 'Completed') completedCount++;
                }
            });
            
            document.getElementById('totalVisibleCount').textContent = rows.length;
            document.getElementById('totalRevenue').textContent = `₱${totalRevenue.toFixed(2)}`;
            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('completedCount').textContent = completedCount;
            document.getElementById('totalCount').textContent = filteredRows.length;
            document.getElementById('totalRecords').textContent = filteredRows.length;
            document.getElementById('visibleCount').textContent = filteredRows.length;
            
            // Reset to first page and render
            currentPage = 1;
            renderPage();
        }
        
        function renderPage() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageRows = filteredRows.slice(start, end);
            
            // Hide all rows first
            document.querySelectorAll('.transaction-row').forEach(row => {
                row.style.display = 'none';
            });
            
            // Show rows for current page
            pageRows.forEach(row => {
                row.style.display = '';
            });
            
            // Update pagination info
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            const startRecord = filteredRows.length > 0 ? start + 1 : 0;
            const endRecord = Math.min(end, filteredRows.length);
            
            document.getElementById('pageStart').textContent = startRecord;
            document.getElementById('pageEnd').textContent = endRecord;
            document.getElementById('totalRecords').textContent = filteredRows.length;
            
            // Render pagination buttons
            renderPaginationButtons(totalPages);
        }
        
        function renderPaginationButtons(totalPages) {
            const container = document.getElementById('paginationNumbers');
            if (!container) return;
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            
            let html = '';
            
            // Previous button
            html += `
                <button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''}">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            // Page numbers
            const maxVisible = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let endPage = Math.min(totalPages, startPage + maxVisible - 1);
            
            if (endPage - startPage + 1 < maxVisible) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }
            
            if (startPage > 1) {
                html += `<button onclick="changePage(1)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</button>`;
                if (startPage > 2) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button onclick="changePage(${i})" 
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ${i === currentPage ? 'bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'}">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
                html += `<button onclick="changePage(${totalPages})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">${totalPages}</button>`;
            }
            
            // Next button
            html += `
                <button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            container.innerHTML = html;
        }
        
        function changePage(page) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderPage();
        }
        
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            filterTransactions();
        }
        
        const LOAD_PER_KG = 6;
        
        // Initialize search dropdown
        initCustomerSearch();
        
        // Add event listeners for filters
        document.getElementById('searchInput').addEventListener('keyup', filterTransactions);
        document.getElementById('statusFilter').addEventListener('change', filterTransactions);
        
        // Initial filter and pagination call
        setTimeout(() => {
            filterTransactions();
        }, 500);

        function calculateLoads() {
            const weight = parseFloat(weightInput.value) || 0;
            const loads = Math.ceil(weight / LOAD_PER_KG);
            loadsDisplay.value = loads;
            return loads;
        }
        
        function calculateServiceTotal() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const price = selectedOption && selectedOption.dataset.price ? parseFloat(selectedOption.dataset.price) : 0;
            const loads = calculateLoads();
            const serviceTotal = price * loads;
            serviceTotalDisplay.textContent = `₱${serviceTotal.toFixed(2)}`;
            return serviceTotal;
        }
        
        function calculateExtraTotal() {
            let extraTotal = 0;
            extraItemsList.forEach(item => {
                extraTotal += item.subtotal;
            });
            extraTotalDisplay.textContent = `₱${extraTotal.toFixed(2)}`;
            return extraTotal;
        }
        
        function calculateTotal() {
            const serviceTotal = calculateServiceTotal();
            const extraTotal = calculateExtraTotal();
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
            
            if (quantity < 1) {
                alert('Quantity must be at least 1');
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

        serviceSelect.addEventListener('change', () => calculateTotal());
        weightInput.addEventListener('input', () => {
            calculateLoads();
            calculateTotal();
        });

        function openAddModal() {
            document.getElementById('transaction_id').value = '';
            form.reset();
            extraItemsList = [];
            updateExtraItemsList();
            calculateTotal();
            loadsDisplay.value = '';
            weightInput.value = '';
            customerSearch.value = '';
            customerSelect.value = '';
            modalTitle.textContent = 'Create New Transaction';
            submitButtonText.textContent = 'Create Transaction';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        async function editTransaction(id) {
            try {
                const response = await fetch(`/staff/transactions/${id}`);
                if (!response.ok) throw new Error('Failed to fetch transaction');
                const transaction = await response.json();
                
                document.getElementById('transaction_id').value = transaction.id;
                customerSelect.value = transaction.customer_id;
                const selectedCustomer = customerSelect.options[customerSelect.selectedIndex];
                customerSearch.value = selectedCustomer ? selectedCustomer.textContent : '';
                document.getElementById('service_type_id').value = transaction.service_type_id;
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
                if (!response.ok) throw new Error('Failed to fetch transaction');
                const transaction = await response.json();
                
                document.getElementById('view_transaction_id').textContent = transaction.id;
                document.getElementById('view_date').textContent = transaction.transaction_date ? new Date(transaction.transaction_date).toLocaleDateString() : '-';
                document.getElementById('view_customer').textContent = transaction.customer_name || '-';
                document.getElementById('view_service').textContent = transaction.service_name || '-';
                document.getElementById('view_staff').textContent = transaction.staff_name || '-';
                document.getElementById('view_weight').textContent = transaction.weight ? `${parseFloat(transaction.weight).toFixed(2)} kg` : '0 kg';
                document.getElementById('view_loads').textContent = transaction.number_of_loads || 0;
                
                const serviceTotal = transaction.service_total || (transaction.number_of_loads * (transaction.service_price || 0));
                const extraTotal = transaction.extra_total || 0;
                
                document.getElementById('view_service_total').textContent = `₱${parseFloat(serviceTotal || 0).toFixed(2)}`;
                document.getElementById('view_extra_total').textContent = `₱${parseFloat(extraTotal).toFixed(2)}`;
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
                alert('Error loading transaction details: ' + error.message);
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

        // Load extra items totals for each transaction
        document.addEventListener('DOMContentLoaded', function() {
            let promises = [];
            @foreach($transactions as $transaction)
            promises.push(
                Promise.all([
                    fetch(`/staff/transactions/extra-items/{{ $transaction->transaction_id }}`).then(res => res.json()),
                    fetch(`/staff/transactions/service-price/{{ $transaction->transaction_id }}`).then(res => res.json())
                ])
                .then(([extraData, serviceData]) => {
                    const extraTotalElem = document.getElementById(`extra_total_{{ $transaction->transaction_id }}`);
                    const totalAmountElem = document.getElementById(`total_amount_{{ $transaction->transaction_id }}`);
                    if (extraTotalElem) {
                        extraTotalElem.textContent = `₱${extraData.extra_total.toFixed(2)}`;
                    }
                    if (totalAmountElem) {
                        const serviceTotal = (serviceData.service_price || 0) * ({{ $transaction->number_of_loads ?? 0 }});
                        const totalAmount = serviceTotal + extraData.extra_total;
                        totalAmountElem.textContent = `₱${totalAmount.toFixed(2)}`;
                    }
                })
                .catch(error => console.error('Error loading data:', error))
            );
            @endforeach
            
            Promise.all(promises).then(() => {
                filterTransactions();
            });
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const customerId = document.getElementById('customer_id').value;
            const serviceTypeId = document.getElementById('service_type_id').value;
            const statusId = document.getElementById('status_id').value;
            const weight = document.getElementById('weight').value;
            const staffId = document.getElementById('staff_id').value;
            
            if (!customerId || !serviceTypeId || !staffId || !statusId) {
                alert('Please fill in all required fields');
                return;
            }
            
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            const serviceName = selectedOption ? selectedOption.text.toLowerCase() : '';
            const isWashService = serviceName.includes('wash');
            
            if (!isWashService) {
                const loads = calculateLoads();
                if (!loads || loads < 1) {
                    alert('Number of loads must be at least 1 for this service type');
                    return;
                }
            }
            
            const submitBtn = document.querySelector('#transactionForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const transactionId = document.getElementById('transaction_id').value;
            const url = transactionId ? `/staff/transactions/${transactionId}` : '/staff/transactions';
            const loads = calculateLoads();
            
            const formData = {
                customer_id: customerId,
                service_type_id: serviceTypeId,
                staff_id: staffId,
                status_id: statusId,
                weight: weight ? parseFloat(weight) : null,
                number_of_loads: isWashService ? null : loads,
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || JSON.stringify(data.errors || 'Unknown error')));
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
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        alert('Error deleting transaction: ' + (data.message || 'Unknown error'));
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