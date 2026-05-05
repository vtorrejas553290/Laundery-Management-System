<x-staff-layout>
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

        <!-- Payments Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Payment ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Transaction ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Payment Amount</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Payment Method</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Date</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 align-middle">{{ $payment->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 align-middle">{{ $payment->transaction_id }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 align-middle">₱{{ number_format($payment->payment_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 align-middle">{{ $payment->payment_method }}</td>
                                <td class="px-4 py-3 text-sm align-middle">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($payment->payment_status == 'Paid') bg-green-100 text-green-800
                                        @elseif($payment->payment_status == 'Pending') bg-yellow-100 text-yellow-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        {{ $payment->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 align-middle">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : '—' }}</td>
                                <td class="px-4 py-3 text-right align-middle">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewPayment('{{ $payment->id }}')" class="text-green-600 hover:text-green-800 p-1" title="View Payment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openEditModal(@json($payment))' class="text-blue-600 hover:text-blue-800 p-1" title="Edit Payment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deletePayment('{{ $payment->id }}')" class="text-red-600 hover:text-red-800 p-1" title="Delete Payment">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No payments found. Click "Add Payment" to create one.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Transaction Payment Status Summary -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-sm font-medium text-green-800 mb-2">Paid Transactions</h3>
                <p class="text-2xl font-bold text-green-900">
                    {{ $transactions->where('payment_status', 'Paid')->count() }}
                </p>
            </div>
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <h3 class="text-sm font-medium text-yellow-800 mb-2">Partial Payments</h3>
                <p class="text-2xl font-bold text-yellow-900">
                    {{ $transactions->where('payment_status', 'Partial')->count() }}
                </p>
            </div>
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <h3 class="text-sm font-medium text-red-800 mb-2">Pending Payments</h3>
                <p class="text-2xl font-bold text-red-900">
                    {{ $transactions->where('payment_status', 'Pending')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Payment Modal (Create/Edit) -->
    <div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 32rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add Payment</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="paymentForm">
                    @csrf
                    <input type="hidden" id="payment_id" name="payment_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <!-- Transaction ID -->
                            <div>
                                <label for="transaction_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Transaction ID <span style="color: #ef4444;">*</span>
                                </label>
                                <select 
                                    name="transaction_id" 
                                    id="transaction_id" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                >
                                    <option value="">Select transaction</option>
                                    @foreach($transactions as $transaction)
                                        <option value="{{ $transaction->id }}">{{ $transaction->id }} - ₱{{ number_format($transaction->total_amount, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Payment Amount -->
                            <div>
                                <label for="payment_amount" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Payment Amount (₱) <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="payment_amount" 
                                    id="payment_amount" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="0.00"
                                >
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
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                >
                                    <option value="">Select method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
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
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                >
                                    <option value="Paid">Paid</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Partial">Partial</option>
                                </select>
                            </div>
                            
                            <!-- Transaction Info Display -->
                            <div id="transaction_info" style="background-color: #f3f4f6; border-radius: 0.5rem; padding: 0.75rem; margin-top: 0.5rem; display: none;">
                                <div style="font-size: 0.75rem; color: #6b7280;">Transaction Total: <span id="transaction_total" style="font-weight: 600; color: #1f2937;">₱0.00</span></div>
                                <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">Payment Status: <span id="transaction_payment_status" style="font-weight: 600;"></span></div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
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
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Payment Details</h3>
                    <button onclick="closeViewPaymentModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
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
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_payment_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</label>
                                <p class="mt-1 text-base text-gray-900" id="view_transaction_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</label>
                                <p class="mt-1 text-base text-gray-900" id="view_payment_method">-</p>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Amount</label>
                                <p class="mt-1 text-2xl font-bold text-blue-600" id="view_payment_amount">-</p>
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
                    
                    <!-- Transaction Details Section -->
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Transaction Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_customer_name">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Total</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900" id="view_transaction_total">-</p>
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

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                    <button type="button" onclick="closeViewPaymentModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editPaymentFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Payment
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
        let currentViewPayment = null;
        
        // Transaction data for reference
        const transactionsData = @json($transactions);
        
        function updateTransactionInfo() {
            const transactionId = document.getElementById('transaction_id').value;
            const infoDiv = document.getElementById('transaction_info');
            const totalSpan = document.getElementById('transaction_total');
            const statusSpan = document.getElementById('transaction_payment_status');
            
            if (transactionId) {
                const transaction = transactionsData.find(t => t.id === transactionId);
                if (transaction) {
                    totalSpan.textContent = `₱${parseFloat(transaction.total_amount).toFixed(2)}`;
                    let statusColor = '';
                    switch(transaction.payment_status) {
                        case 'Paid': statusColor = '#10b981'; break;
                        case 'Partial': statusColor = '#f59e0b'; break;
                        default: statusColor = '#ef4444';
                    }
                    statusSpan.innerHTML = `<span style="color: ${statusColor};">${transaction.payment_status || 'Pending'}</span>`;
                    infoDiv.style.display = 'block';
                    return;
                }
            }
            infoDiv.style.display = 'none';
        }
        
        document.getElementById('transaction_id').addEventListener('change', updateTransactionInfo);

        function openAddModal() {
            document.getElementById('payment_id').value = '';
            form.reset();
            document.getElementById('transaction_info').style.display = 'none';
            modalTitle.textContent = 'Add Payment';
            submitButtonText.textContent = 'Add Payment';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(payment) {
            document.getElementById('payment_id').value = payment.id;
            document.getElementById('transaction_id').value = payment.transaction_id;
            document.getElementById('payment_amount').value = payment.payment_amount;
            document.getElementById('payment_method').value = payment.payment_method;
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
                const response = await fetch(`/staff/payments/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const payment = await response.json();
                console.log('Payment data:', payment);
                
                // Populate view modal
                document.getElementById('view_payment_id').textContent = payment.id || '-';
                document.getElementById('view_transaction_id').textContent = payment.transaction_id || '-';
                document.getElementById('view_payment_method').textContent = payment.payment_method || '-';
                document.getElementById('view_payment_amount').textContent = `₱${parseFloat(payment.payment_amount || 0).toFixed(2)}`;
                
                // Payment Status with styling
                const statusSpan = document.getElementById('view_payment_status');
                const status = payment.payment_status || 'Pending';
                let statusClass = '';
                if (status === 'Paid') statusClass = 'text-green-600 font-semibold';
                else if (status === 'Partial') statusClass = 'text-orange-600 font-semibold';
                else statusClass = 'text-yellow-600 font-semibold';
                statusSpan.innerHTML = `<span class="${statusClass}">${status}</span>`;
                
                // Payment Date
                if (payment.paid_at) {
                    const date = new Date(payment.paid_at);
                    document.getElementById('view_payment_date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                } else {
                    document.getElementById('view_payment_date').textContent = '-';
                }
                
                // Transaction Details
                if (payment.transaction) {
                    document.getElementById('view_customer_name').textContent = payment.transaction.customer_name || '-';
                    document.getElementById('view_transaction_total').textContent = `₱${parseFloat(payment.transaction.total_amount || 0).toFixed(2)}`;
                    
                    const transStatusSpan = document.getElementById('view_transaction_status');
                    const transStatus = payment.transaction.payment_status || 'Pending';
                    let transStatusClass = '';
                    if (transStatus === 'Paid') transStatusClass = 'text-green-600 font-semibold';
                    else if (transStatus === 'Partial') transStatusClass = 'text-orange-600 font-semibold';
                    else transStatusClass = 'text-yellow-600 font-semibold';
                    transStatusSpan.innerHTML = `<span class="${transStatusClass}">${transStatus}</span>`;
                    
                    if (payment.transaction.created_at) {
                        const transDate = new Date(payment.transaction.created_at);
                        document.getElementById('view_transaction_date').textContent = transDate.toLocaleDateString();
                    } else {
                        document.getElementById('view_transaction_date').textContent = '-';
                    }
                } else {
                    document.getElementById('view_customer_name').textContent = '-';
                    document.getElementById('view_transaction_total').textContent = '-';
                    document.getElementById('view_transaction_status').innerHTML = '<span class="text-gray-600">-</span>';
                    document.getElementById('view_transaction_date').textContent = '-';
                }
                
                // Store current payment for edit button
                currentViewPayment = payment;
                
                // Show modal
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
                openEditModal(currentViewPayment);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#paymentForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const paymentId = document.getElementById('payment_id').value;
            const url = paymentId ? `/staff/payments/${paymentId}` : '/staff/payments';
            
            const formData = {
                transaction_id: document.getElementById('transaction_id').value,
                payment_amount: document.getElementById('payment_amount').value,
                payment_method: document.getElementById('payment_method').value,
                payment_status: document.getElementById('payment_status').value,
                _token: '{{ csrf_token() }}',
                _method: paymentId ? 'PUT' : 'POST'
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

        async function deletePayment(id) {
            if (confirm('Are you sure you want to delete this payment?')) {
                try {
                    const response = await fetch(`/staff/payments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Error deleting payment');
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
        viewPaymentModal.addEventListener('click', function(event) {
            if (event.target === viewPaymentModal) {
                closeViewPaymentModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewPaymentModal.style.display === 'block') {
                    closeViewPaymentModal();
                }
            }
        });
    </script>
</x-staff-layout>