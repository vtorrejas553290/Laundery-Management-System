<x-staff-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Services</h1>
            <p class="text-gray-600">View available laundry services and pricing</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">All Services</h3>
                    <div class="flex gap-3 w-full md:w-auto">
                        <!-- Search Input with Icon on the RIGHT -->
                        <div class="relative flex items-center">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Search services..." 
                                class="w-56 pl-3 pr-9 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            >
                            <svg class="absolute right-3 text-gray-400 w-4 h-4 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="top: 50%; transform: translateY(-50%);" onclick="document.getElementById('searchInput').focus()">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <!-- Category Filter -->
                        <select id="categoryFilter" class="px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white bg-no-repeat bg-right" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZT0iY3VycmVudENvbG9yIiBjbGFzcz0idy00IGgtNCI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNOC4yNSAxNUwxMiAxOC43NSAxNS43NSAxNW0tNy41LTZMMTIgNS4yNSAxNS43NSA5IiAvPjwvc3ZnPg=='); background-position: right 0.75rem center; background-size: 1rem;">
                            <option value="All">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Price per Load</th>
                            </tr>
                        </thead>
                        <tbody id="servicesTableBody" class="divide-y divide-gray-200">
                            @foreach($serviceTypes as $service)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 service-row" 
                                data-name="{{ strtolower($service->name) }}" 
                                data-category="{{ $service->category->category_name }}"
                                data-description="{{ strtolower($service->category->description ?? '') }}">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $service->name }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $service->category->category_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    @if($service->category->description)
                                        <span class="description-text">{{ $service->category->description }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">₱{{ number_format($service->price_per_load, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div id="noResults" class="text-center text-gray-500 py-8 hidden">
                    No services found
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const servicesTableBody = document.getElementById('servicesTableBody');
        const noResults = document.getElementById('noResults');
        const rows = document.querySelectorAll('.service-row');

        function filterServices() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value;
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const category = row.getAttribute('data-category');
                const description = row.getAttribute('data-description');
                
                const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
                const matchesCategory = selectedCategory === 'All' || category === selectedCategory;
                
                if (matchesSearch && matchesCategory) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
                servicesTableBody.style.display = 'none';
            } else {
                noResults.classList.add('hidden');
                servicesTableBody.style.display = '';
            }
        }

        searchInput.addEventListener('input', filterServices);
        categoryFilter.addEventListener('change', filterServices);
    </script>
</x-staff-layout>