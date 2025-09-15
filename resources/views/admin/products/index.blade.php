<x-main :user="$user">

        <div class="flex justify-between items-center">

        
            <h1 class="text-2xl font-bold">
                Products
            </h1>
            
            <x-forms.form id="create-product-form" action="{{ route('admin.products.store') }}" method="POST">
                
                <x-button data-modal-target="crud-modal" data-modal-toggle="crud-modal">
                    Add Product
                </x-button>

                <!-- add modal -->
                <x-modals.create header="Create Product">

                    <div class="space-y-6">
                        <x-forms.input 
                            name="name"
                            label="Name"
                            placeholder="Enter name of product"/>

                        <x-forms.select label="Select category" name="category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-forms.select>

                        <h1 class="font-bold text-base">Small Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="small" name="size[]">

                            <x-forms.input name="price[]" placeholder="Enter price"/>
                            <x-forms.input name="quantity_stock[]" placeholder="Enter quantity stock"/>
                        </div>

                        <h1 class="font-bold text-base">Medium Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="medium" name="size[]">

                            <x-forms.input name="price[]" placeholder="Enter price"/>
                            <x-forms.input name="quantity_stock[]" placeholder="Enter quantity stock"/>
                        </div>

                        <h1 class="font-bold text-base">Large Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="large" name="size[]">

                            <x-forms.input name="price[]" placeholder="Enter price"/>
                            <x-forms.input name="quantity_stock[]" placeholder="Enter quantity stock"/>
                        </div>

                        <x-slot name="footer">
                            <x-forms.button>
                                Create Product
                            </x-forms.button>
                        </x-slot>
                    </div>

                    

                </x-modals.create>

            </x-forms.form>

            

        </div>
        
        <div
            id="product-list" 
            class="mt-5 grid grid-cols-1 gap-y-4 gap-x-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach ($products as $product)
                <x-product-card :product="$product" :categories="$categories"/>
                <x-modals.delete
                    :title="$product->name"
                    id="{{ $product->id }}" 
                    :action="route('admin.products.destroy', $product->id)"/>
                <x-modals.edit 
                    id="{{ $product->id }}"
                    header="Edit product: {{ $product->name }}"
                    :action="route('admin.products.update', $product->id)">


                    <div class="space-y-6">
                        <x-forms.input 
                            name="name"
                            label="Name"
                            placeholder="Enter name of product"
                            value="{{ $product->name }}"/>

                        <x-forms.select label="Select category" name="category">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} 
                                </option>
                            @endforeach
                        </x-forms.select>

                        <h1 class="font-bold text-base">Small Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="Small" name="size[]">
                            <x-forms.input 
                                label="Price" 
                                name="price[]" 
                                placeholder="Enter price" 
                                value="{{ optional($product->prices->where('size', 'small')->first())->price }}"/>

                            <x-forms.input 
                                label="Quantities" 
                                name="quantity_stock[]" 
                                placeholder="Enter quantity stock" 
                                value="{{ optional($product->prices->where('size', 'small')->first())->quantity_stock }}"/>
                        </div>

                        <h1 class="font-bold text-base">Medium Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="Medium" name="size[]">
                            <x-forms.input 
                                label="Price" 
                                name="price[]" 
                                placeholder="Enter price" 
                                value="{{ optional($product->prices->where('size', 'medium')->first())->price }}"/>
                            <x-forms.input 
                                label="Quantities" 
                                name="quantity_stock[]" 
                                placeholder="Enter quantity stock" 
                                value="{{ optional($product->prices->where('size', 'medium')->first())->quantity_stock }}"/>
                        </div>

                        <h1 class="font-bold text-base">Large Size</h1>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="hidden" value="Large" name="size[]">
                            <x-forms.input 
                                label="Price" 
                                name="price[]" 
                                placeholder="Enter price" 
                                value="{{ optional($product->prices->where('size', 'large')->first())->price }}"/>
                            <x-forms.input 
                                label="Quantities" 
                                name="quantity_stock[]" 
                                placeholder="Enter quantity stock" 
                                value="{{ optional($product->prices->where('size', 'large')->first())->quantity_stock }}"/>
                        </div>


                    </div>
                    <x-slot name="footer">
                        <x-forms.button>
                            Update Product
                        </x-forms.button>
                    </x-slot>
                
                </x-modals.edit>
            @endforeach

        </div>


</x-main>


