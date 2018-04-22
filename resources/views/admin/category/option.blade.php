@foreach($categories as $category)
    <option value="{{ $category->category_id }}" {{ $selected == $category->category_id ? 'selected' : '' }}>
        @for($i = 0; $i < $n; $i++)&nbsp;@endfor
        {!! $category->category_name !!}
    </option>
    @if(count($category->child) > 0)
        @include('admin.category.option', ['categories' => $category->child, 'n' => $n + 5, 'selected' => $selected])
    @endif
@endforeach

