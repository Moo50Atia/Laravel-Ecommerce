<div class="container">
<h2>products List</h2>
<a href="{{ route('vendor.products.create') }}" class="btn btn-primary mb-3">Create products</a>
<table class="table">
    <thead>
        <tr><th>name</th><th>slug</th><th>description</th><th>short_description</th><th>sku</th><th>price</th><th>brand_id</th><th>category_id</th><th>is_active</th><th>is_featured</th><th>weight</th><th>dimensions</th></tr>
    </thead>
    <tbody>
        @foreach ($products as $item)
                <tr>
                    <td>{{$item->name}}</td>
<td>{{$item->slug}}</td>
<td>{{$item->description}}</td>
<td>{{$item->short_description}}</td>
<td>{{$item->sku}}</td>
<td>{{$item->price}}</td>
<td>{{$item->brand_id}}</td>
<td>{{$item->category_id}}</td>
<td>{{$item->is_active}}</td>
<td>{{$item->is_featured}}</td>
<td>{{$item->weight}}</td>
<td>{{$item->dimensions}}</td>
<td>
                        <a href="{{ route('vendor.products.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('vendor.products.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>