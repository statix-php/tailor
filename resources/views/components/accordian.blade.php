<div>
    @if($component->has('header')) 
        <h1>{{ $getHeader() }}</h1>
    @endif

    @foreach ($getSections() as $section)
    
        <!-- todo -->

    @endforeach
</div>