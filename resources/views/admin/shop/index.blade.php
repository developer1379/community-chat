@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto text-left">
    <!-- Premium Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-600/40 text-emerald-200 border border-emerald-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">shopping_bag</span> Shop Inventory Management
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Shop Items
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-2xl font-medium leading-relaxed">
                Add, edit, update, or remove coin shop upgrades. Changes reflect instantly on the public shop dashboard.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 dark:bg-rose-950/20 dark:border-rose-900/50 dark:text-rose-450 rounded-2xl text-xs font-semibold space-y-1">
            <p class="font-bold">Please correct the following errors:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Shop Items List -->
        <div class="lg:col-span-2 border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-850 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-555">storefront</span> Current Items
                </h2>
                <span class="text-xs font-bold px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full">
                    {{ count($shopItems) }} total
                </span>
            </div>

            @if($shopItems->isEmpty())
                <div class="p-12 text-center text-slate-500 dark:text-slate-450">
                    <span class="material-symbols-outlined text-5xl mb-3 text-slate-300">shopping_bag</span>
                    <p class="font-semibold">No shop items exist yet.</p>
                    <p class="text-xs mt-1">Create one using the form on the right.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-850">
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400">Name &amp; Details</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400">Category</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Price</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Duration</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Stock</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                            @foreach($shopItems as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors font-semibold text-slate-700 dark:text-slate-300">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900 dark:text-white text-sm">
                                            {{ $item->name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            Key: {{ $item->key }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1 line-clamp-1">
                                            {{ $item->description }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs">
                                        <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/45 text-blue-600 dark:text-blue-400 font-bold border border-blue-150/50 dark:border-blue-900/30">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="p-4 text-center font-bold">
                                        {{ $item->duration }}
                                    </td>
                                    <td class="p-4 text-center">
                                        {{ $item->stock !== null ? $item->stock : 'Unlimited' }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- Edit Trigger -->
                                            <button onclick="openEditModal('{{ $item->id }}', '{{ addslashes($item->name) }}', '{{ addslashes($item->category) }}', '{{ addslashes($item->description) }}', '{{ $item->price }}', '{{ $item->stock }}', '{{ addslashes($item->duration) }}', '{{ addslashes($item->key) }}')" class="p-2 rounded-xl text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-450 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="Edit shop item">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.shop.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete \'{{ $item->name }}\'?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 dark:hover:text-rose-450 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="Delete shop item">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Add Shop Item Form -->
        <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-555">add_box</span> Add New Shop Item
            </h2>

            <form action="{{ route('admin.shop.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div class="space-y-1.5 text-left">
                    <label for="name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Item Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Bold Thread Title" value="{{ old('name') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>

                <!-- Category -->
                <div class="space-y-1.5 text-left">
                    <label for="category" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Category <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                        <option value="Feature Updates">Feature Updates</option>
                        <option value="Promot your content">Promot your content</option>
                        <option value="User Access">User Access</option>
                        <option value="Private threads">Private threads</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="space-y-1.5 text-left">
                    <label for="description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="Explain the upgrade..."
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-955/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">{{ old('description') }}</textarea>
                </div>

                <!-- Price -->
                <div class="space-y-1.5 text-left">
                    <label for="price" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Price (DF Coins) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="price" id="price" required placeholder="5.00" value="{{ old('price') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>

                <!-- Duration -->
                <div class="space-y-1.5 text-left">
                    <label for="duration" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Duration <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="duration" id="duration" required placeholder="e.g. Permanent, 1 months" value="{{ old('duration', 'Permanent') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>

                <!-- Stock -->
                <div class="space-y-1.5 text-left">
                    <label for="stock" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Stock (Blank for Unlimited)
                    </label>
                    <input type="number" name="stock" id="stock" placeholder="Unlimited" value="{{ old('stock') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
                </div>

                <!-- Key -->
                <div class="space-y-1.5 text-left">
                    <label for="key" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        Item Key (Unique System Identifier) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="key" id="key" required placeholder="e.g. bold_title" value="{{ old('key') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-955/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold font-mono">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full px-6 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm shadow-lg shadow-emerald-500/20 transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span> Create Shop Item
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Shop Item Edit Overlay/Modal -->
<div id="editShopItemModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-lg p-6 sm:p-8 m-4 shadow-2xl relative text-left">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-650 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
            <span class="material-symbols-outlined text-lg">close</span>
        </button>

        <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-emerald-600">edit_note</span> Edit Shop Item
        </h3>

        <form id="editShopItemForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="space-y-1.5 text-left">
                <label for="edit_name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Item Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="edit_name" required placeholder="e.g. Bold Title"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
            </div>

            <!-- Category -->
            <div class="space-y-1.5 text-left">
                <label for="edit_category" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Category <span class="text-rose-500">*</span>
                </label>
                <select name="category" id="edit_category" required
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold font-sans">
                    <option value="Feature Updates">Feature Updates</option>
                    <option value="Promot your content">Promot your content</option>
                    <option value="User Access">User Access</option>
                    <option value="Private threads">Private threads</option>
                </select>
            </div>

            <!-- Description -->
            <div class="space-y-1.5 text-left">
                <label for="edit_description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Description
                </label>
                <textarea name="description" id="edit_description" rows="3" placeholder="Brief explanation..."
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold"></textarea>
            </div>

            <!-- Price -->
            <div class="space-y-1.5 text-left">
                <label for="edit_price" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Price (DF Coins) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" name="price" id="edit_price" required placeholder="5.00"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
            </div>

            <!-- Duration -->
            <div class="space-y-1.5 text-left">
                <label for="edit_duration" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Duration <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="duration" id="edit_duration" required placeholder="Permanent"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold font-sans">
            </div>

            <!-- Stock -->
            <div class="space-y-1.5 text-left">
                <label for="edit_stock" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Stock (Blank for Unlimited)
                </label>
                <input type="number" name="stock" id="edit_stock" placeholder="Unlimited"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold">
            </div>

            <!-- Key -->
            <div class="space-y-1.5 text-left">
                <label for="edit_key" class="block text-xs font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    Item Key (System Identifier) <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="key" id="edit_key" required placeholder="e.g. bold_title"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-semibold font-mono">
            </div>

            <!-- Modal Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-350 dark:hover:bg-slate-700 text-sm font-extrabold transition-all cursor-pointer font-sans">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold shadow-lg shadow-emerald-500/20 transition-all cursor-pointer font-sans">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name, category, description, price, stock, duration, key) {
        const modal = document.getElementById('editShopItemModal');
        const form = document.getElementById('editShopItemForm');
        
        // Setup action path
        form.action = `/admin/shop/${id}`;

        // Populate fields
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_category').value = category;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_stock').value = stock === 'null' || stock === '' ? '' : stock;
        document.getElementById('edit_duration').value = duration;
        document.getElementById('edit_key').value = key;

        // Show Modal
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        const modal = document.getElementById('editShopItemModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
