@extends('layout')
@section('quest', 'active')
@section('slot')
    <div class="container" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStoryModalLabel">Tahrirlash</h5>
            </div>
            <div class="modal-body">
                <form id="editStoryForm" action="{{ route('stories.update', $story->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="editStoryTitle">Sarlavha</label>
                        <input type="text" class="form-control" id="editStoryTitle" name="title" value="{{ old('title', $story->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="editStoryContent">Guruh Haqida</label>
                        <textarea class="form-control" id="editStoryContent" name="body" required>{{ old('body', $story->body) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="editStoryImage" class="ml-5">Guruh Rasmi (ixtiyoriy)</label>
                        <input type="file" class="form-control border-0" name="photo">
                        @if ($story->photo !== 'default.png')
                            <img src="{{ asset('storage/' . $story->photo) }}" alt="Story Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Yangilash</button>
                </form>
            </div>
        </div>
    </div>
@endsection
