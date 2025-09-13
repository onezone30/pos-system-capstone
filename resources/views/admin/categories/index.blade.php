<x-main :user="$user">

        <div class="flex justify-between items-center">

        
            <h1 class="text-2xl font-bold">
                Categories
            </h1>
            
            <x-forms.form action="{{ route('admin.categories.store') }}" method="POST">
                
                <x-button data-modal-target="crud-modal" data-modal-toggle="crud-modal">
                    Add Category
                </x-button>

                <x-modals.create header="Create categories">

                    <div class="space-y-6">
                        <x-forms.input 
                            name="name"
                            label="Name"
                            placeholder="Enter category"
                            required/>

                        <x-forms.input 
                            name="description"
                            label="Description"
                            placeholder="Enter description"
                            />

                        <x-slot name="footer">
                            <x-forms.button>
                                Create Category
                            </x-forms.button>
                        </x-slot>
                    </div>

                    

                </x-modals.create>

            </x-forms.form>

            

        </div>
        
        <div class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach ($categories as $category)
                <x-category-card :category="$category" />
                <x-modals.view-category 
                    :id="$category->id" 
                    :category="$category" 
                    header="Category details: {{ $category->name }}"/>
                <x-modals.delete
                    :title="$category->name"
                    id="{{ $category->id }}" 
                    :action="route('admin.categories.destroy', $category->id)"/>
                <x-modals.edit 
                    id="{{ $category->id }}"
                    header="Edit category: {{ $category->name }}"
                    :action="route('admin.categories.update', $category->id)">


                    <div class="space-y-6">
                        <x-forms.input 
                            name="name"
                            label="Name"
                            placeholder="Enter category"
                            value="{{ $category->name }}"
                            required/>

                        <x-forms.input 
                            name="description"
                            label="Description"
                            placeholder="Enter description"
                            value="{{ $category->description }}"
                            />




                    </div>
                    <x-slot name="footer">
                        <x-forms.button>
                            Update Category
                        </x-forms.button>
                    </x-slot>
                
                </x-modals.edit>
            @endforeach

        </div>

</x-main>