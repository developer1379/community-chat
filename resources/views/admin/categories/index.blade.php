@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto text-left">
    <!-- Premium Header Banner -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 p-6 sm:p-10 text-white">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-rose-650/40 text-rose-200 border border-rose-500/30 uppercase tracking-widest leading-none">
                <span class="material-symbols-outlined text-xs">category</span> Content Management
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight font-sans">
                Forum Categories
            </h1>
            <p class="text-sm sm:text-lg text-slate-350 max-w-2xl font-medium leading-relaxed">
                Add, remove, or temporarily disable forum categories. Active categories will be displayed on the public forum and allowed for thread creation.
            </p>
        </div>
    </div>

    @if (session('error'))
        <div class="p-4 rounded-2xl border border-rose-500/25 bg-rose-50 text-rose-800 flex items-center gap-3 shadow-sm shadow-rose-500/5">
            <span class="material-symbols-outlined text-rose-600">error</span>
            <p class="font-semibold text-xs">{{ session('error') }}</p>
        </div>
    @endif

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
        <!-- Categories List -->
        <div class="lg:col-span-2 border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-850 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-550">list_alt</span> Current Categories
                </h2>
                <span class="text-xs font-bold px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-full">
                    {{ count($categories) }} total
                </span>
            </div>

            @if($categories->isEmpty())
                <div class="p-12 text-center text-slate-500 dark:text-slate-450">
                    <span class="material-symbols-outlined text-5xl mb-3 text-slate-300">folder_open</span>
                    <p class="font-semibold">No categories exist yet.</p>
                    <p class="text-xs mt-1">Create one using the form on the right.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-850">
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400">Icon &amp; Name</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Order</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Threads</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-center">Status</th>
                                <th class="p-4 text-xs font-extrabold uppercase tracking-wider text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                            @foreach($categories as $category)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                                    <!-- Name & Icon -->
                                    <td class="p-4 flex items-start gap-3.5">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="{{ $category->icon ?: 'fas fa-comments' }} text-base"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                                                {{ $category->name }}
                                            </h3>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                                                /categories/{{ $category->slug }}
                                            </p>
                                            @if($category->description)
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                                    {{ $category->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Order -->
                                    <td class="p-4 text-center font-bold text-slate-700 dark:text-slate-300">
                                        {{ $category->order }}
                                    </td>

                                    <!-- Threads -->
                                    <td class="p-4 text-center">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-indigo-650 dark:text-indigo-400">
                                            {{ $category->threads_count }}
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="p-4 text-center">
                                        @if($category->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-550"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Disabled
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Toggle Status -->
                                            <form action="{{ route('admin.categories.toggle', $category) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-450 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="{{ $category->is_active ? 'Disable category' : 'Enable category' }}">
                                                    <span class="material-symbols-outlined text-[20px]">
                                                        {{ $category->is_active ? 'visibility_off' : 'visibility' }}
                                                    </span>
                                                </button>
                                            </form>

                                            <!-- Delete -->
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete the category \'{{ $category->name }}\'? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-605 dark:hover:text-rose-450 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="Delete category">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
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

        <!-- Add Category Form -->
        <div class="border border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-550">add_box</span> Add New Category
            </h2>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div class="space-y-1.5 text-left">
                    <label for="name" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required placeholder="e.g. general discussion, coding..." value="{{ old('name') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
                </div>

                <!-- Description -->
                <div class="space-y-1.5 text-left">
                    <label for="description" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="Brief explanation of what threads belong in this category..."
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">{{ old('description') }}</textarea>
                </div>

                <!-- Icon -->
                <div class="space-y-1.5 text-left">
                    <label for="icon" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Icon Class
                    </label>
                    <input type="text" name="icon" id="icon" placeholder="e.g. fas fa-comments, fas fa-gamepad" value="{{ old('icon', 'fas fa-comments') }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
                    <span class="text-[10px] text-slate-405 font-bold">Uses FontAwesome icon CSS classes.</span>
                </div>

                <!-- Display Order -->
                <div class="space-y-1.5 text-left">
                    <label for="order" class="block text-xs font-extrabold uppercase tracking-widest text-slate-505 dark:text-slate-400">
                        Display Order <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="order" id="order" required min="0" placeholder="e.g. 0, 10, 20" value="{{ old('order', 0) }}"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all font-semibold">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full px-6 py-3.5 rounded-2xl bg-indigo-650 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-500/20 transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">save</span> Create Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
