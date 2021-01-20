@foreach($collections as $comment)
    @include('comments.comment',['comment'=>$comment])
@endforeach
