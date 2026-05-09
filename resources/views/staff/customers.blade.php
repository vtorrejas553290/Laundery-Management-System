<x-staff-layout>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Customers</h1>
                <p class="text-gray-600">Manage your customer database</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </button>
        </div>

        <!-- Statistics Summary -->
        <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="text-sm font-medium text-blue-800 mb-2">Total Customers</h3>
                <p class="text-2xl font-bold text-blue-900" id="totalCustomersCount">{{ $customers->count() }}</p>
            </div>
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-sm font-medium text-green-800 mb-2">New This Month</h3>
                <p class="text-2xl font-bold text-green-900" id="newThisMonthCount">
                    {{ $customers->filter(function($customer) {
                        return $customer->created_at && $customer->created_at->isCurrentMonth();
                    })->count() }}
                </p>
            </div>
            <div class="bg-purple-50 rounded-lg border border-purple-200 p-6">
                <h3 class="text-sm font-medium text-purple-800 mb-2">Most Recent</h3>
                <p class="text-lg font-semibold text-purple-900 truncate" id="mostRecentCustomer">
                    {{ $customers->first() ? $customers->first()->first_name . ' ' . $customers->first()->last_name : '—' }}
                </p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4 flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by name, ID, or contact number..." 
                        class="w-80 px-4 py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button onclick="clearSearch()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Clear Search
                </button>
            </div>
            <div class="text-sm text-gray-500">
                Showing <span id="visibleCount">0</span> of <span id="totalVisibleCount">0</span> customers
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Customers</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody" class="divide-y divide-gray-200">
                            @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 customer-row" 
                                data-id="{{ $customer->id }}"
                                data-first-name="{{ strtolower($customer->first_name) }}"
                                data-middle-name="{{ strtolower($customer->middle_name ?? '') }}"
                                data-last-name="{{ strtolower($customer->last_name) }}"
                                data-contact="{{ strtolower($customer->contact_number) }}"
                                data-full-name="{{ strtolower($customer->first_name . ' ' . $customer->last_name) }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 customer-id">{{ $customer->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 first-name">{{ $customer->first_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 middle-name">{{ $customer->middle_name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 last-name">{{ $customer->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 contact-number">{{ $customer->contact_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 created-date">{{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('M d, Y') : '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewCustomer('{{ $customer->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openEditModal(@json($customer))' class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteCustomer('{{ $customer->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noRecordsRow">
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No customers found. Click "Add Customer" to create one.</td>
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

    <!-- Customer Modal (Create/Edit) -->
    <div id="customerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 28rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add New Customer</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="customerForm">
                    @csrf
                    <input type="hidden" id="customer_id" name="customer_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div>
                                <label for="first_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    First Name <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="first_name" 
                                    id="first_name" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter first name"
                                >
                            </div>
                            
                            <div>
                                <label for="middle_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Middle Name <span style="color: #9ca3af; font-size: 0.75rem;">(Optional)</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="middle_name" 
                                    id="middle_name" 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter middle name"
                                >
                            </div>
                            
                            <div>
                                <label for="last_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Last Name <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="last_name" 
                                    id="last_name" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter last name"
                                >
                            </div>
                            
                            <div>
                                <label for="contact_number" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Contact Number <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="contact_number" 
                                    id="contact_number" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="09171234567"
                                >
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="submitButtonText">Save Customer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div id="viewCustomerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 48rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Customer Details</h3>
                    <button onclick="closeViewCustomerModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customer ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_customer_id">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_full_name">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_first_name">-</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_middle_name">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_last_name">-</p>
                            </div>
                            
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</label>
                                <p class="mt-1 text-base text-gray-900" id="view_contact_number">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Account Information</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Member Since</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_created_date">-</p>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                                    <p class="mt-1 text-sm text-gray-900" id="view_updated_date">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Active Customer
                        </span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; position: sticky; bottom: 0; background: white;">
                    <button type="button" onclick="closeViewCustomerModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editCustomerFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Customer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById('customerModal');
        let form = document.getElementById('customerForm');
        let modalTitle = document.getElementById('modal-title');
        let submitButtonText = document.getElementById('submitButtonText');
        let currentViewCustomer = null;
        
        // Pagination variables
        let currentPage = 1;
        const rowsPerPage = 10;
        let filteredRows = [];
        
        // Filter and Pagination Functions
        function filterCustomers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.customer-row');
            
            filteredRows = [];
            
            rows.forEach(row => {
                const id = row.querySelector('.customer-id')?.textContent.toLowerCase() || '';
                const firstName = row.getAttribute('data-first-name') || '';
                const lastName = row.getAttribute('data-last-name') || '';
                const middleName = row.getAttribute('data-middle-name') || '';
                const fullName = row.getAttribute('data-full-name') || '';
                const contact = row.getAttribute('data-contact') || '';
                
                let matchesSearch = id.includes(searchTerm) || 
                                   firstName.includes(searchTerm) || 
                                   lastName.includes(searchTerm) || 
                                   middleName.includes(searchTerm) ||
                                   fullName.includes(searchTerm) ||
                                   contact.includes(searchTerm);
                
                if (matchesSearch) {
                    filteredRows.push(row);
                }
            });
            
            document.getElementById('totalVisibleCount').textContent = rows.length;
            document.getElementById('totalRecords').textContent = filteredRows.length;
            document.getElementById('visibleCount').textContent = filteredRows.length;
            
            // Update statistics for filtered results
            updateFilteredStatistics();
            
            // Reset to first page and render
            currentPage = 1;
            renderCustomerPage();
        }
        
        function updateFilteredStatistics() {
            // Update total customers count (filtered)
            document.getElementById('totalCustomersCount').textContent = filteredRows.length;
            
            // Update most recent customer in filtered results
            if (filteredRows.length > 0) {
                const firstRow = filteredRows[0];
                const firstName = firstRow.querySelector('.first-name')?.textContent || '';
                const lastName = firstRow.querySelector('.last-name')?.textContent || '';
                document.getElementById('mostRecentCustomer').textContent = `${firstName} ${lastName}`;
            } else {
                document.getElementById('mostRecentCustomer').textContent = '—';
            }
            
            // Count new this month from filtered results
            let newThisMonth = 0;
            filteredRows.forEach(row => {
                const createdDate = row.querySelector('.created-date')?.textContent || '';
                if (createdDate.match(/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/)) {
                    newThisMonth++;
                }
            });
            document.getElementById('newThisMonthCount').textContent = newThisMonth;
        }
        
        function renderCustomerPage() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageRows = filteredRows.slice(start, end);
            
            // Hide all rows first
            document.querySelectorAll('.customer-row').forEach(row => {
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
                <button onclick="changeCustomerPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
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
                html += `<button onclick="changeCustomerPage(1)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</button>`;
                if (startPage > 2) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button onclick="changeCustomerPage(${i})" 
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ${i === currentPage ? 'bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'}">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
                html += `<button onclick="changeCustomerPage(${totalPages})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">${totalPages}</button>`;
            }
            
            // Next button
            html += `
                <button onclick="changeCustomerPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            container.innerHTML = html;
        }
        
        function changeCustomerPage(page) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderCustomerPage();
        }
        
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            filterCustomers();
        }

        function openAddModal() {
            document.getElementById('customer_id').value = '';
            form.reset();
            modalTitle.textContent = 'Add New Customer';
            submitButtonText.textContent = 'Save Customer';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(customer) {
            document.getElementById('customer_id').value = customer.id;
            document.getElementById('first_name').value = customer.first_name;
            document.getElementById('middle_name').value = customer.middle_name || '';
            document.getElementById('last_name').value = customer.last_name;
            document.getElementById('contact_number').value = customer.contact_number;
            modalTitle.textContent = 'Edit Customer';
            submitButtonText.textContent = 'Update Customer';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        async function viewCustomer(id) {
            try {
                const response = await fetch(`/staff/customers/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const customer = await response.json();
                
                document.getElementById('view_customer_id').textContent = customer.id || '-';
                document.getElementById('view_first_name').textContent = customer.first_name || '-';
                document.getElementById('view_middle_name').textContent = customer.middle_name || '—';
                document.getElementById('view_last_name').textContent = customer.last_name || '-';
                document.getElementById('view_contact_number').textContent = customer.contact_number || '-';
                
                let fullName = customer.first_name || '';
                if (customer.middle_name) fullName += ' ' + customer.middle_name;
                fullName += ' ' + (customer.last_name || '');
                document.getElementById('view_full_name').textContent = fullName.trim();
                
                if (customer.created_at) {
                    const date = new Date(customer.created_at);
                    document.getElementById('view_created_date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                } else {
                    document.getElementById('view_created_date').textContent = '-';
                }
                
                if (customer.updated_at && customer.updated_at !== customer.created_at) {
                    const updatedDate = new Date(customer.updated_at);
                    document.getElementById('view_updated_date').textContent = updatedDate.toLocaleDateString() + ' ' + updatedDate.toLocaleTimeString();
                } else if (customer.created_at) {
                    document.getElementById('view_updated_date').textContent = 'Not updated yet';
                } else {
                    document.getElementById('view_updated_date').textContent = '-';
                }
                
                currentViewCustomer = customer;
                document.getElementById('viewCustomerModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching customer:', error);
                alert('Error loading customer details: ' + error.message);
            }
        }
        
        function closeViewCustomerModal() {
            document.getElementById('viewCustomerModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewCustomer = null;
        }
        
        function editCustomerFromView() {
            if (currentViewCustomer) {
                closeViewCustomerModal();
                openEditModal(currentViewCustomer);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#customerForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const customerId = document.getElementById('customer_id').value;
            const url = customerId ? `/staff/customers/${customerId}` : '/staff/customers';
            
            const formData = {
                first_name: document.getElementById('first_name').value,
                middle_name: document.getElementById('middle_name').value,
                last_name: document.getElementById('last_name').value,
                contact_number: document.getElementById('contact_number').value,
                _token: '{{ csrf_token() }}',
                _method: customerId ? 'PUT' : 'POST'
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

        async function deleteCustomer(id) {
            if (confirm('Are you sure you want to delete this customer? This will also delete all associated transactions.')) {
                try {
                    const response = await fetch(`/staff/customers/${id}`, {
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
                        alert('Error deleting customer: ' + (data.message || 'Unknown error'));
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
        
        const viewCustomerModal = document.getElementById('viewCustomerModal');
        if (viewCustomerModal) {
            viewCustomerModal.addEventListener('click', function(event) {
                if (event.target === viewCustomerModal) {
                    closeViewCustomerModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewCustomerModal && viewCustomerModal.style.display === 'block') {
                    closeViewCustomerModal();
                }
            }
        });
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keyup', filterCustomers);
            setTimeout(() => {
                filterCustomers();
            }, 100);
        });
    </script>
</x-staff-layout>