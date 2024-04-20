<div class="modal fade" id="delete_post{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('users.destroy',[$user->id])}}" method="POST">
                @method('DELETE')
                @csrf
            <div class="modal-body">
              <label> Are you sure you want to delete the post?</label> <span class="text-danger">{{$user->title}}</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete User</button>
            </div>
            </form>
        </div>
    </div>
</div>
