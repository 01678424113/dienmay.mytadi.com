<ul>
    @foreach($categories as $category)
    <li>
        <a role="button" data-id="{{ $category->category_id }}" onclick="loadCategory(this);">{!! $category->category_name !!}</a>
        @if(count($category->child) > 0)
        @include('admin.category.tree', ['categories' => $category->child])
        @endif
    </li>
    @endforeach
</ul>