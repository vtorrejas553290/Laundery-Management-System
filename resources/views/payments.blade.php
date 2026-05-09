<x-app-layout>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Payments</h1>
                <p class="text-gray-600">Manage payment records</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Payment
            </button>
        </div>

        <!-- Transaction Payment Status Summary at the TOP -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-sm font-medium text-green-800 mb-2">Paid Transactions</h3>
                <p class="text-2xl font-bold text-green-900" id="paid_count">0</p>
            </div>
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <h3 class="text-sm font-medium text-red-800 mb-2">Unpaid Transactions</h3>
                <p class="text-2xl font-bold text-red-900" id="unpaid_count">0</p>
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
                
                <!-- Status Filter - Using Tailwind classes -->
                <select id="statusFilter" class="px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZT0iY3VycmVudENvbG9yIiBjbGFzcz0idy00IGgtNCI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNOC4yNSAxNUwxMiAxOC43NSAxNS43NSAxNW0tNy41LTZMMTIgNS4yNSAxNS43NSA5IiAvPjwvc3ZnPg=='); background-position: right 0.75rem center; background-size: 1rem;">
                    <option value="all">All Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
                
                <!-- Clear Filters Button -->
                <button onclick="clearFilters()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Clear Filters
                </button>
            </div>
            
            <div class="text-sm text-gray-500">
                Showing <span id="visibleCount">0</span> of <span id="totalVisibleCount">0</span> records
            </div>
        </div>

        <!-- Payments Table (Paid + Unpaid) -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Records</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Amount</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody" class="divide-y divide-gray-200">
                            @forelse($allPayments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 payment-row" 
                                data-status="{{ $payment->payment_status }}"
                                data-customer="{{ strtolower($payment->customer_name) }}"
                                data-transaction-id="{{ strtolower($payment->transaction_id) }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $payment->payment_id ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->transaction_id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 customer-name">{{ $payment->customer_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->service_type }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 payment-amount">
                                    ₱{{ number_format($payment->payment_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->payment_method ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->payment_status == 'Paid') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $payment->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 payment-date">
                                    {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : ($payment->transaction_date ? \Carbon\Carbon::parse($payment->transaction_date)->format('M d, Y') : '—') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($payment->payment_status == 'Paid')
                                            <button onclick="viewPayment('{{ $payment->payment_id }}')" class="text-green-600 hover:text-green-800 p-1" title="View Payment">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <!-- NO EDIT BUTTON FOR PAID STATUS -->
                                            <button onclick="deletePayment('{{ $payment->payment_id }}')" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button onclick="addPaymentForTransaction('{{ $payment->transaction_id }}')" class="text-blue-600 hover:text-blue-800 p-1" title="Add Payment">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noRecordsRow">
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">No payment records found.</td>
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

    <!-- Payment Modal (Create/Edit) with Searchable Transaction Dropdown -->
    <div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 32rem; width: 100%; margin: auto; max-height: 90vh; display: flex; flex-direction: column;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; flex-shrink: 0;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add Payment</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="paymentForm" style="overflow-y: auto; flex: 1;">
                    @csrf
                    <input type="hidden" id="payment_id" name="payment_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <!-- Transaction ID - Searchable Dropdown -->
                            <div id="transaction_id_container">
                                <label for="transaction_search" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Transaction <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="transaction_search" placeholder="Search by transaction ID or customer..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    autocomplete="off">
                                <select name="transaction_id" id="transaction_id" required style="display: none;">
                                    <option value="">Select transaction</option>
                                    @foreach($transactions as $transaction)
                                        <option value="{{ $transaction->id }}" 
                                            data-payment-status="{{ $transaction->payment_status }}" 
                                            data-total="{{ $transaction->calculated_total }}"
                                            data-customer="{{ $transaction->customer_name }}">
                                            {{ $transaction->id }} - {{ $transaction->customer_name }} - ₱{{ number_format($transaction->calculated_total, 2) }} ({{ $transaction->payment_status ?? 'Unpaid' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="transaction_dropdown" class="absolute z-50 hidden bg-white border border-gray-300 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg" style="width: calc(100% - 2rem); min-width: 300px;">
                                    @foreach($transactions as $transaction)
                                        <div class="transaction-option px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm border-b last:border-b-0" 
                                            data-value="{{ $transaction->id }}"
                                            data-status="{{ $transaction->payment_status ?? 'Unpaid' }}"
                                            data-total="{{ $transaction->calculated_total }}"
                                            data-customer="{{ $transaction->customer_name }}">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">{{ $transaction->id }}</span>
                                                <span class="text-xs px-2 py-0.5 rounded-full {{ $transaction->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $transaction->payment_status ?? 'Unpaid' }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $transaction->customer_name }}</div>
                                            <div class="text-xs text-blue-600 mt-1">₱{{ number_format($transaction->calculated_total, 2) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-blue-600 mt-1">Payment amount will include service + extra items (calculated by trigger)</p>
                            </div>
                            
                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Payment Method <span style="color: #ef4444;">*</span>
                                </label>
                                <select 
                                    name="payment_method" 
                                    id="payment_method" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;"
                                >
                                    <option value="">Select method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                </select>
                            </div>
                            
                            <!-- Payment Status -->
                            <div>
                                <label for="payment_status" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Payment Status <span style="color: #ef4444;">*</span>
                                </label>
                                <select 
                                    name="payment_status" 
                                    id="payment_status" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;"
                                >
                                    <option value="Paid">Paid</option>
                                    <option value="Unpaid">Unpaid</option>
                                </select>
                            </div>
                            
                            <!-- Transaction Info Display -->
                            <div id="transaction_info" style="background-color: #f3f4f6; border-radius: 0.5rem; padding: 0.75rem; margin-top: 0.5rem; display: none;">
                                <div style="font-size: 0.75rem; color: #6b7280;">Customer: <span id="transaction_customer" style="font-weight: 600; color: #1f2937;">-</span></div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">Transaction Total (including extras): <span id="transaction_total" style="font-weight: 600; color: #1f2937;">₱0.00</span></div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">Current Status: <span id="transaction_payment_status" style="font-weight: 600;"></span></div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;" class="text-blue-600">⚡ Trigger will calculate payment amount including extras</div>
                            </div>
                            
                            <!-- Warning message for already paid transactions -->
                            <div id="paid_warning" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 0.75rem; display: none;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span style="font-size: 0.875rem; font-weight: 500; color: #991b1b;">This transaction has already been paid.</span>
                                </div>
                                <p style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">You cannot add another payment for a fully paid transaction.</p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; flex-shrink: 0;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" id="submitPaymentBtn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="submitButtonText">Add Payment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Payment Modal -->
    <div id="viewPaymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Payment Details</h3>
                    <button onclick="closeViewPaymentModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_payment_id">-</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</label>
                                <p class="mt-1 text-base text-gray-900" id="view_transaction_id">-</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_customer_name">-</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</label>
                                <p class="mt-1 text-base text-gray-900" id="view_payment_method">-</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Amount</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600" id="view_payment_amount">-</p>
                                <p class="text-xs text-gray-500 mt-1">Includes service + extra items</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</label>
                                <p class="mt-1" id="view_payment_status">-</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</label>
                                <p class="mt-1 text-base text-gray-600" id="view_payment_date">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Transaction Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_service_type">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Service Total</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_service_total">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Extra Items Total</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_extra_total">-</p>
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Extra Items</label>
                                    <div class="mt-1 text-sm text-gray-900" id="view_extra_items_list">-</div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Total</label>
                                    <p class="mt-1 text-lg font-bold text-blue-600" id="view_transaction_total">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Status</label>
                                    <p class="mt-1" id="view_transaction_status">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Date</label>
                                    <p class="mt-1 text-sm text-gray-600" id="view_transaction_date">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; position: sticky; bottom: 0; background: white;">
                    <button type="button" onclick="closeViewPaymentModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById('paymentModal');
        let form = document.getElementById('paymentForm');
        let modalTitle = document.getElementById('modal-title');
        let submitButtonText = document.getElementById('submitButtonText');
        let submitButton = document.getElementById('submitPaymentBtn');
        let currentViewPayment = null;
        
        // Pagination variables
        let currentPage = 1;
        const rowsPerPage = 10;
        let filteredRows = [];
        
        // Searchable Transaction Dropdown
        const transactionSearch = document.getElementById('transaction_search');
        const transactionSelect = document.getElementById('transaction_id');
        const transactionDropdown = document.getElementById('transaction_dropdown');
        const transactionOptions = document.querySelectorAll('.transaction-option');
        
        function initTransactionSearch() {
            transactionSearch.addEventListener('focus', () => {
                transactionDropdown.classList.remove('hidden');
                filterTransactionOptions();
            });
            
            transactionSearch.addEventListener('input', filterTransactionOptions);
            
            transactionOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const value = option.dataset.value;
                    const status = option.dataset.status;
                    const total = option.dataset.total;
                    const customer = option.dataset.customer;
                    const text = option.querySelector('.font-medium')?.textContent || value;
                    
                    transactionSelect.value = value;
                    transactionSearch.value = `${text} - ${customer} (${status})`;
                    transactionDropdown.classList.add('hidden');
                    
                    // Trigger change event to update info
                    const event = new Event('change');
                    transactionSelect.dispatchEvent(event);
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!transactionSearch.contains(e.target) && !transactionDropdown.contains(e.target)) {
                    transactionDropdown.classList.add('hidden');
                }
            });
        }
        
        function filterTransactionOptions() {
            const searchTerm = transactionSearch.value.toLowerCase();
            let hasVisible = false;
            
            transactionOptions.forEach(option => {
                const text = option.textContent.toLowerCase();
                const id = option.dataset.value?.toLowerCase() || '';
                const customer = option.dataset.customer?.toLowerCase() || '';
                
                if (text.includes(searchTerm) || id.includes(searchTerm) || customer.includes(searchTerm)) {
                    option.classList.remove('hidden');
                    hasVisible = true;
                } else {
                    option.classList.add('hidden');
                }
            });
            
            if (hasVisible && transactionDropdown.classList.contains('hidden') === false) {
                transactionDropdown.classList.remove('hidden');
            }
        }
        
        function updatePaymentSummary() {
            const transactionsData = @json($transactions);
            let paid = 0, unpaid = 0;
            
            transactionsData.forEach(transaction => {
                const status = transaction.payment_status || 'Unpaid';
                if (status === 'Paid') {
                    paid++;
                } else {
                    unpaid++;
                }
            });
            
            document.getElementById('paid_count').textContent = paid;
            document.getElementById('unpaid_count').textContent = unpaid;
        }
        
        // Filter and Pagination Functions for Payment Records
        function filterPayments() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.payment-row');
            
            filteredRows = [];
            
            rows.forEach(row => {
                const customerName = row.querySelector('.customer-name')?.textContent.toLowerCase() || '';
                const transactionId = row.getAttribute('data-transaction-id') || '';
                const status = row.getAttribute('data-status') || '';
                
                let matchesSearch = customerName.includes(searchTerm) || transactionId.includes(searchTerm);
                let matchesStatus = statusFilter === 'all' || status === statusFilter;
                
                if (matchesSearch && matchesStatus) {
                    filteredRows.push(row);
                }
            });
            
            document.getElementById('totalVisibleCount').textContent = rows.length;
            document.getElementById('totalRecords').textContent = filteredRows.length;
            
            // Reset to first page and render
            currentPage = 1;
            renderPaymentPage();
        }
        
        function renderPaymentPage() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageRows = filteredRows.slice(start, end);
            
            // Hide all rows first
            document.querySelectorAll('.payment-row').forEach(row => {
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
            document.getElementById('visibleCount').textContent = filteredRows.length;
            
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
                <button onclick="changePaymentPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
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
                html += `<button onclick="changePaymentPage(1)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</button>`;
                if (startPage > 2) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button onclick="changePaymentPage(${i})" 
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ${i === currentPage ? 'bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'}">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
                html += `<button onclick="changePaymentPage(${totalPages})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">${totalPages}</button>`;
            }
            
            // Next button
            html += `
                <button onclick="changePaymentPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            container.innerHTML = html;
        }
        
        function changePaymentPage(page) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderPaymentPage();
        }
        
        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            filterPayments();
        }
        
        function updateTransactionInfo() {
            const selectedOption = transactionSelect.options[transactionSelect.selectedIndex];
            const transactionId = transactionSelect.value;
            const infoDiv = document.getElementById('transaction_info');
            const warningDiv = document.getElementById('paid_warning');
            const totalSpan = document.getElementById('transaction_total');
            const customerSpan = document.getElementById('transaction_customer');
            const statusSpan = document.getElementById('transaction_payment_status');
            
            if (transactionId && selectedOption) {
                const paymentStatus = selectedOption.getAttribute('data-payment-status');
                const totalAmount = selectedOption.getAttribute('data-total');
                const customerName = selectedOption.getAttribute('data-customer');
                
                customerSpan.textContent = customerName || '-';
                totalSpan.textContent = `₱${parseFloat(totalAmount || 0).toFixed(2)}`;
                
                let statusColor = '';
                let statusText = paymentStatus || 'Unpaid';
                if (statusText === 'Paid') {
                    statusColor = '#10b981';
                } else {
                    statusColor = '#ef4444';
                }
                statusSpan.innerHTML = `<span style="color: ${statusColor};">${statusText}</span>`;
                infoDiv.style.display = 'block';
                
                if (paymentStatus === 'Paid') {
                    warningDiv.style.display = 'block';
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.5';
                    submitButton.style.cursor = 'not-allowed';
                } else {
                    warningDiv.style.display = 'none';
                    submitButton.disabled = false;
                    submitButton.style.opacity = '1';
                    submitButton.style.cursor = 'pointer';
                }
                return;
            }
            infoDiv.style.display = 'none';
            warningDiv.style.display = 'none';
            submitButton.disabled = false;
            submitButton.style.opacity = '1';
            submitButton.style.cursor = 'pointer';
        }
        
        function addPaymentForTransaction(transactionId) {
            openAddModal();
            setTimeout(() => {
                const select = transactionSelect;
                for(let i = 0; i < select.options.length; i++) {
                    if(select.options[i].value === transactionId) {
                        select.selectedIndex = i;
                        const selectedOption = select.options[i];
                        transactionSearch.value = `${selectedOption.value} - ${selectedOption.getAttribute('data-customer')} (${selectedOption.getAttribute('data-payment-status') || 'Unpaid'})`;
                        updateTransactionInfo();
                        break;
                    }
                }
            }, 100);
        }
        
        transactionSelect.addEventListener('change', updateTransactionInfo);
        document.getElementById('payment_status').addEventListener('change', function() {
            const methodField = document.getElementById('payment_method');
            if (this.value === 'Paid') {
                methodField.required = true;
                methodField.style.borderColor = '#ef4444';
            } else {
                methodField.required = false;
                methodField.style.borderColor = '#d1d5db';
            }
        });

        function openAddModal() {
            document.getElementById('payment_id').value = '';
            form.reset();
            transactionSearch.value = '';
            transactionSelect.value = '';
            document.getElementById('transaction_info').style.display = 'none';
            document.getElementById('paid_warning').style.display = 'none';
            submitButton.disabled = false;
            submitButton.style.opacity = '1';
            submitButton.style.cursor = 'pointer';
            modalTitle.textContent = 'Add Payment';
            submitButtonText.textContent = 'Add Payment';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(payment) {
            document.getElementById('payment_id').value = payment.payment_id;
            transactionSelect.value = payment.transaction_id;
            const selectedOption = transactionSelect.options[transactionSelect.selectedIndex];
            if (selectedOption) {
                transactionSearch.value = `${selectedOption.value} - ${selectedOption.getAttribute('data-customer')} (${selectedOption.getAttribute('data-payment-status') || 'Unpaid'})`;
            }
            document.getElementById('payment_method').value = payment.payment_method || '';
            document.getElementById('payment_status').value = payment.payment_status;
            updateTransactionInfo();
            modalTitle.textContent = 'Edit Payment';
            submitButtonText.textContent = 'Update Payment';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        async function viewPayment(id) {
            try {
                const response = await fetch(`/admin/payments/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const payment = await response.json();
                
                document.getElementById('view_payment_id').textContent = payment.id || '-';
                document.getElementById('view_transaction_id').textContent = payment.transaction_id || '-';
                document.getElementById('view_customer_name').textContent = payment.transaction?.customer_name || '-';
                document.getElementById('view_payment_method').textContent = payment.payment_method || '-';
                document.getElementById('view_payment_amount').textContent = `₱${parseFloat(payment.payment_amount || 0).toFixed(2)}`;
                
                const statusSpan = document.getElementById('view_payment_status');
                const status = payment.payment_status || 'Unpaid';
                let statusClass = status === 'Paid' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold';
                statusSpan.innerHTML = `<span class="${statusClass}">${status}</span>`;
                
                if (payment.paid_at) {
                    const date = new Date(payment.paid_at);
                    document.getElementById('view_payment_date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                } else {
                    document.getElementById('view_payment_date').textContent = '-';
                }
                
                if (payment.transaction) {
                    document.getElementById('view_service_type').textContent = payment.transaction.service_type || '-';
                    document.getElementById('view_service_total').textContent = `₱${parseFloat(payment.transaction.service_total || 0).toFixed(2)}`;
                    document.getElementById('view_extra_total').textContent = `₱${parseFloat(payment.transaction.extra_total || 0).toFixed(2)}`;
                    document.getElementById('view_transaction_total').textContent = `₱${parseFloat(payment.transaction.total_amount || 0).toFixed(2)}`;
                    
                    if (payment.transaction.extra_items && payment.transaction.extra_items.length > 0) {
                        let itemsHtml = '<ul class="list-disc list-inside">';
                        payment.transaction.extra_items.forEach(item => {
                            itemsHtml += `<li>${item.item_name} x${item.quantity} - ₱${parseFloat(item.subtotal).toFixed(2)}</li>`;
                        });
                        itemsHtml += '</ul>';
                        document.getElementById('view_extra_items_list').innerHTML = itemsHtml;
                    } else {
                        document.getElementById('view_extra_items_list').innerHTML = 'No extra items';
                    }
                    
                    const transStatusSpan = document.getElementById('view_transaction_status');
                    const transStatus = payment.transaction.payment_status || 'Unpaid';
                    let transStatusClass = transStatus === 'Paid' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold';
                    transStatusSpan.innerHTML = `<span class="${transStatusClass}">${transStatus}</span>`;
                    
                    if (payment.transaction.created_at) {
                        const transDate = new Date(payment.transaction.created_at);
                        document.getElementById('view_transaction_date').textContent = transDate.toLocaleDateString();
                    } else {
                        document.getElementById('view_transaction_date').textContent = '-';
                    }
                }
                
                currentViewPayment = payment;
                document.getElementById('viewPaymentModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching payment:', error);
                alert('Error loading payment details: ' + error.message);
            }
        }
        
        function closeViewPaymentModal() {
            document.getElementById('viewPaymentModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewPayment = null;
        }
        
        function editPaymentFromView() {
            if (currentViewPayment) {
                closeViewPaymentModal();
                const paymentData = {
                    payment_id: currentViewPayment.id,
                    transaction_id: currentViewPayment.transaction_id,
                    payment_method: currentViewPayment.payment_method,
                    payment_status: currentViewPayment.payment_status
                };
                openEditModal(paymentData);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (submitButton.disabled) {
                alert('Cannot add payment. This transaction has already been paid.');
                return;
            }
            
            const paymentStatus = document.getElementById('payment_status').value;
            const paymentMethod = document.getElementById('payment_method').value;
            
            if (paymentStatus === 'Paid' && !paymentMethod) {
                alert('Payment method is required when status is Paid');
                return;
            }
            
            const submitBtn = submitButton;
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const paymentId = document.getElementById('payment_id').value;
            const url = paymentId ? `/admin/payments/${paymentId}` : '/admin/payments';
            
            const transactionSelectElem = transactionSelect;
            const selectedOption = transactionSelectElem.options[transactionSelectElem.selectedIndex];
            const transactionPaymentStatus = selectedOption ? selectedOption.getAttribute('data-payment-status') : null;
            
            if (transactionPaymentStatus === 'Paid') {
                alert('Cannot add payment. This transaction has already been paid.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }
            
            const formData = {
                transaction_id: transactionSelectElem.value,
                payment_method: paymentMethod,
                payment_status: paymentStatus,
                _token: '{{ csrf_token() }}',
                _method: paymentId ? 'PUT' : 'POST'
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
                    alert('Error: ' + (data.message || data.error || 'Unknown error'));
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

        async function deletePayment(id) {
            if (confirm('Are you sure you want to delete this payment?')) {
                try {
                    const response = await fetch(`/admin/payments/${id}`, {
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
                        alert('Error deleting payment: ' + (data.message || 'Unknown error'));
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
        
        const viewPaymentModal = document.getElementById('viewPaymentModal');
        if (viewPaymentModal) {
            viewPaymentModal.addEventListener('click', function(event) {
                if (event.target === viewPaymentModal) {
                    closeViewPaymentModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewPaymentModal && viewPaymentModal.style.display === 'block') {
                    closeViewPaymentModal();
                }
            }
        });
        
        // Initialize all event listeners
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentSummary();
            initTransactionSearch();
            
            // Add event listeners for filters
            document.getElementById('searchInput').addEventListener('keyup', filterPayments);
            document.getElementById('statusFilter').addEventListener('change', filterPayments);
            
            // Initial filter
            setTimeout(() => {
                filterPayments();
            }, 500);
        });
    </script>
</x-app-layout>