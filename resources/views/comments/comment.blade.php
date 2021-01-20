<div class="col-md-12">
    <h5><span style="color:#31b0d5">{{$comment->name}}</span>：{{$comment->content}}</h5>
    @if(isset($comments[$comment->id]))
        @include('comments.list',['collections'=>$comments[$comment->id]])
    @endif
    <hr>
</div>
