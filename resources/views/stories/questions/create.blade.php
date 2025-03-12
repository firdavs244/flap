@extends('layout')
@section('quest', 'active')
@section('slot')
    <br />
    <div class="container">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStoryModalLabel">Yangi Savol Yaratish</h5>
            </div>
            <div class="modal-body">
                <form id="createStoryForm" action="{{ route('questions.store', $story->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="createStoryImage" class="ml-5">Rasm (ixtiyoriy)</label>
                        <input type="file" class="form-control border-0" name="photo">
                    </div>
                    <div class="form-group">
                        <label for="createStoryTitle">Savol</label>
                        <textarea style="height: 400px; font-size: 20px" type="text" class="form-control" id="createStoryTitle" name="question" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Yaratish</button>
                </form>
            </div>
        </div>
    </div>
@endsection
