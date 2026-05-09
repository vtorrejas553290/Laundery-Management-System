<x-app-layout>
    <div class="p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Staff Management</h1>
                <p class="text-gray-600">Manage your team members</p>
            </div>
            <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Staff
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-4 flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by name, ID, email, or contact..." 
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
                Showing <span id="visibleCount">0</span> of <span id="totalVisibleCount">0</span> staff members
            </div>
        </div>

        <!-- Staff Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Staff Members</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Middle Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody" class="divide-y divide-gray-200">
                            @forelse($staff as $member)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 staff-row" 
                                data-id="{{ $member->id }}"
                                data-first-name="{{ strtolower($member->first_name) }}"
                                data-middle-name="{{ strtolower($member->middle_name ?? '') }}"
                                data-last-name="{{ strtolower($member->last_name) }}"
                                data-full-name="{{ strtolower($member->first_name . ' ' . $member->last_name) }}"
                                data-email="{{ strtolower($member->email) }}"
                                data-contact="{{ strtolower($member->contact) }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 staff-id">{{ $member->id }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 first-name">{{ $member->first_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 middle-name">{{ $member->middle_name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 last-name">{{ $member->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 email">{{ $member->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 contact">{{ $member->contact }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 age">{{ $member->age }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="viewStaff('{{ $member->id }}')" class="text-green-600 hover:text-green-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button onclick='openEditModal(@json($member))' class="text-blue-600 hover:text-blue-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteStaff('{{ $member->id }}')" class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noRecordsRow">
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">No staff members found. Click "Add Staff" to create one.</td>
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

    <!-- Staff Modal (Create/Edit) -->
    <div id="staffModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 32rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;" id="modal-title">Add New Staff Member</h3>
                    <button onclick="closeModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="staffForm">
                    @csrf
                    <input type="hidden" id="staff_id" name="staff_id">
                    
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <!-- Name Fields Row -->
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
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
                                        placeholder="First name"
                                    >
                                </div>
                                <div>
                                    <label for="middle_name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                        Middle Name
                                    </label>
                                    <input 
                                        type="text" 
                                        name="middle_name" 
                                        id="middle_name" 
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                        placeholder="Middle name"
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
                                        placeholder="Last name"
                                    >
                                </div>
                            </div>

                            <!-- Birthday and Age Row -->
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                                <div>
                                    <label for="birthday" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                        Birthday <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        name="birthday" 
                                        id="birthday" 
                                        required 
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                        onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    >
                                </div>
                                <div>
                                    <label for="age" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                        Age <span style="color: #ef4444;">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        name="age" 
                                        id="age" 
                                        readonly
                                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background-color: #f3f4f6; cursor: not-allowed;"
                                        placeholder="Auto-calculated"
                                    >
                                </div>
                            </div>

                            <!-- Contact and Address -->
                            <div>
                                <label for="contact" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Contact Number <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="contact" 
                                    id="contact" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="09171234567"
                                >
                            </div>
                            <div>
                                <label for="address" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Address <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="address" 
                                    id="address" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Enter address"
                                >
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Email <span style="color: #ef4444;">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    required 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="staff@example.com"
                                >
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Password <span style="color: #ef4444;" id="password_required">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;"
                                    onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 2px rgba(59,130,246,0.1)'"
                                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none'"
                                    placeholder="Minimum 8 characters"
                                >
                                <span style="font-size: 0.7rem; color: #6b7280;">Leave blank to keep current password when editing</span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem;">
                        <button type="button" onclick="closeModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                            <span id="submitButtonText">Save Staff</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Staff Modal -->
    <div id="viewStaffModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999;">
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"></div>
        
        <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
            <div style="position: relative; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); max-width: 56rem; width: 100%; margin: auto; max-height: 90vh; overflow-y: auto;">
                
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; background: white; z-index: 10;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #111827;">Staff Member Details</h3>
                    <button onclick="closeViewStaffModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer;">
                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div style="padding: 1.5rem;">
                    <!-- Personal Information Section -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-200">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900" id="view_staff_id">-</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</label>
                                <p class="mt-1 text-base text-gray-900" id="view_full_name">-</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Age</label>
                                <p class="mt-1 text-base font-semibold text-blue-600" id="view_age">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information Section -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-200">Contact Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</label>
                                <p class="mt-1 text-base text-gray-900" id="view_email">-</p>
                            </div>
                            <div class="border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</label>
                                <p class="mt-1 text-base text-gray-900" id="view_contact">-</p>
                            </div>
                            <div class="col-span-2 border-b border-gray-100 pb-3">
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address</label>
                                <p class="mt-1 text-base text-gray-900" id="view_address">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information Section -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-200">Account Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-purple-600 uppercase tracking-wider">Birthday</label>
                                <p class="mt-1 text-base text-gray-900" id="view_birthday">-</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-purple-600 uppercase tracking-wider">Member Since</label>
                                <p class="mt-1 text-base text-gray-900" id="view_created_date">-</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-purple-600 uppercase tracking-wider">Last Updated</label>
                                <p class="mt-1 text-base text-gray-900" id="view_updated_date">-</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <label class="text-xs font-medium text-purple-600 uppercase tracking-wider">Status</label>
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 0 0 0.75rem 0.75rem; position: sticky; bottom: 0; background: white;">
                    <button type="button" onclick="closeViewStaffModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: #374151; background: white; border: 1px solid #d1d5db; border-radius: 0.5rem; cursor: pointer;">
                        Close
                    </button>
                    <button type="button" onclick="editStaffFromView()" class="edit-from-view-btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 500; color: white; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;">
                        Edit Staff
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById('staffModal');
        let form = document.getElementById('staffForm');
        let modalTitle = document.getElementById('modal-title');
        let submitButtonText = document.getElementById('submitButtonText');
        let birthdayInput = document.getElementById('birthday');
        let ageInput = document.getElementById('age');
        let emailInput = document.getElementById('email');
        let passwordInput = document.getElementById('password');
        let passwordRequired = document.getElementById('password_required');
        let currentViewStaff = null;
        
        // Pagination variables
        let currentPage = 1;
        const rowsPerPage = 5;
        let filteredRows = [];

        function calculateAge(birthday) {
            if (!birthday) return '';
            const birthDate = new Date(birthday);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        function updateAge() {
            const birthday = birthdayInput.value;
            if (birthday) {
                const age = calculateAge(birthday);
                ageInput.value = age;
            } else {
                ageInput.value = '';
            }
        }

        birthdayInput.addEventListener('change', updateAge);
        birthdayInput.addEventListener('input', updateAge);
        
        // Filter and Pagination Functions
        function filterStaff() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.staff-row');
            
            filteredRows = [];
            
            rows.forEach(row => {
                const id = row.querySelector('.staff-id')?.textContent.toLowerCase() || '';
                const firstName = row.getAttribute('data-first-name') || '';
                const lastName = row.getAttribute('data-last-name') || '';
                const middleName = row.getAttribute('data-middle-name') || '';
                const fullName = row.getAttribute('data-full-name') || '';
                const email = row.getAttribute('data-email') || '';
                const contact = row.getAttribute('data-contact') || '';
                
                let matchesSearch = id.includes(searchTerm) || 
                                   firstName.includes(searchTerm) || 
                                   lastName.includes(searchTerm) || 
                                   middleName.includes(searchTerm) ||
                                   fullName.includes(searchTerm) ||
                                   email.includes(searchTerm) ||
                                   contact.includes(searchTerm);
                
                if (matchesSearch) {
                    filteredRows.push(row);
                }
            });
            
            document.getElementById('totalVisibleCount').textContent = rows.length;
            document.getElementById('totalRecords').textContent = filteredRows.length;
            document.getElementById('visibleCount').textContent = filteredRows.length;
            
            // Reset to first page and render
            currentPage = 1;
            renderStaffPage();
        }
        
        function renderStaffPage() {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const pageRows = filteredRows.slice(start, end);
            
            // Hide all rows first
            document.querySelectorAll('.staff-row').forEach(row => {
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
                <button onclick="changeStaffPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
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
                html += `<button onclick="changeStaffPage(1)" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</button>`;
                if (startPage > 2) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
            }
            
            for (let i = startPage; i <= endPage; i++) {
                html += `
                    <button onclick="changeStaffPage(${i})" 
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ${i === currentPage ? 'bg-blue-600 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'}">
                        ${i}
                    </button>
                `;
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300">...</span>`;
                }
                html += `<button onclick="changeStaffPage(${totalPages})" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">${totalPages}</button>`;
            }
            
            // Next button
            html += `
                <button onclick="changeStaffPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            container.innerHTML = html;
        }
        
        function changeStaffPage(page) {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderStaffPage();
        }
        
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            filterStaff();
        }

        function openAddModal() {
            document.getElementById('staff_id').value = '';
            form.reset();
            ageInput.value = '';
            birthdayInput.value = '';
            emailInput.disabled = false;
            emailInput.required = true;
            passwordInput.required = true;
            passwordRequired.style.display = 'inline';
            modalTitle.textContent = 'Add New Staff Member';
            submitButtonText.textContent = 'Save Staff';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(staff) {
            document.getElementById('staff_id').value = staff.id;
            document.getElementById('first_name').value = staff.first_name;
            document.getElementById('middle_name').value = staff.middle_name || '';
            document.getElementById('last_name').value = staff.last_name;
            emailInput.value = staff.email;
            emailInput.disabled = true;
            emailInput.required = false;
            passwordInput.value = '';
            passwordInput.required = false;
            passwordRequired.style.display = 'none';
            
            if (staff.birthday) {
                const birthday = new Date(staff.birthday);
                const formattedBirthday = birthday.toISOString().split('T')[0];
                birthdayInput.value = formattedBirthday;
                ageInput.value = calculateAge(formattedBirthday);
            } else {
                birthdayInput.value = '';
                ageInput.value = '';
            }
            
            document.getElementById('contact').value = staff.contact;
            document.getElementById('address').value = staff.address;
            modalTitle.textContent = 'Edit Staff Member';
            submitButtonText.textContent = 'Update Staff';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        async function viewStaff(id) {
            try {
                const response = await fetch(`/admin/staff/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const staff = await response.json();
                
                document.getElementById('view_staff_id').textContent = staff.id || '-';
                
                let fullName = staff.first_name || '';
                if (staff.middle_name) fullName += ' ' + staff.middle_name;
                fullName += ' ' + (staff.last_name || '');
                document.getElementById('view_full_name').textContent = fullName.trim();
                
                document.getElementById('view_age').textContent = staff.age ? `${staff.age} years old` : '-';
                document.getElementById('view_email').textContent = staff.email || '-';
                document.getElementById('view_contact').textContent = staff.contact || '-';
                document.getElementById('view_address').textContent = staff.address || '-';
                
                if (staff.birthday) {
                    const birthday = new Date(staff.birthday);
                    document.getElementById('view_birthday').textContent = birthday.toLocaleDateString();
                } else {
                    document.getElementById('view_birthday').textContent = '-';
                }
                
                if (staff.created_at) {
                    const date = new Date(staff.created_at);
                    document.getElementById('view_created_date').textContent = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                } else {
                    document.getElementById('view_created_date').textContent = '-';
                }
                
                if (staff.updated_at && staff.updated_at !== staff.created_at) {
                    const updatedDate = new Date(staff.updated_at);
                    document.getElementById('view_updated_date').textContent = updatedDate.toLocaleDateString() + ' ' + updatedDate.toLocaleTimeString();
                } else if (staff.created_at) {
                    document.getElementById('view_updated_date').textContent = 'Not updated yet';
                } else {
                    document.getElementById('view_updated_date').textContent = '-';
                }
                
                currentViewStaff = staff;
                document.getElementById('viewStaffModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                
            } catch (error) {
                console.error('Error fetching staff:', error);
                alert('Error loading staff details: ' + error.message);
            }
        }
        
        function closeViewStaffModal() {
            document.getElementById('viewStaffModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentViewStaff = null;
        }
        
        function editStaffFromView() {
            if (currentViewStaff) {
                closeViewStaffModal();
                openEditModal(currentViewStaff);
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.querySelector('#staffForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Processing...';
            
            const staffId = document.getElementById('staff_id').value;
            const url = staffId ? `/admin/staff/${staffId}` : '/admin/staff';
            
            const birthday = birthdayInput.value;
            const age = calculateAge(birthday);
            
            const formData = {
                first_name: document.getElementById('first_name').value,
                middle_name: document.getElementById('middle_name').value,
                last_name: document.getElementById('last_name').value,
                birthday: birthday,
                age: age,
                contact: document.getElementById('contact').value,
                address: document.getElementById('address').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                _token: '{{ csrf_token() }}',
                _method: staffId ? 'PUT' : 'POST'
            };
            
            if (staffId && !formData.password) {
                delete formData.password;
            }
            
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

        async function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                try {
                    const response = await fetch(`/admin/staff/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Error deleting staff member');
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
        
        const viewStaffModal = document.getElementById('viewStaffModal');
        if (viewStaffModal) {
            viewStaffModal.addEventListener('click', function(event) {
                if (event.target === viewStaffModal) {
                    closeViewStaffModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (modal.style.display === 'block') {
                    closeModal();
                }
                if (viewStaffModal && viewStaffModal.style.display === 'block') {
                    closeViewStaffModal();
                }
            }
        });
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keyup', filterStaff);
            setTimeout(() => {
                filterStaff();
            }, 100);
        });
    </script>
</x-app-layout>