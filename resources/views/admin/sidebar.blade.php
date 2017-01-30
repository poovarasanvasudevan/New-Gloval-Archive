<div>
    <div class="list-group">
        @foreach(\App\Page::getAdminPage() as $page)
            <a href="{{$page->url}}" class="list-group-item">{{$page->title}}</a>
        @endforeach

    </div>
</div>