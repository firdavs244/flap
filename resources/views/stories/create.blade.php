@extends('layout')
@section('quest', 'active')
@section('slot')
    <br><br>
        <div class="container" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createStoryModalLabel">Yangi Savollar Guruhini Yaratish</h5>
                </div>
                <div class="modal-body">
                    <form id="createStoryForm" action="{{ route('stories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="createStoryTitle">Sarlavha</label>
                            <input type="text" class="form-control" id="createStoryTitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="createStoryContent">Guruh Haqida</label>
                            <textarea class="form-control" id="createStoryContent" name="body" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="ml-5" for="createStoryImage">Guruh Rasmi (ixtiyoriy)</label>
                            <input type="file" class="form-control border-0" name="photo">
                        </div>
                        <button type="submit" class="btn btn-primary">Yaratish</button>
                    </form>
                </div>
            </div>
        </div>
@endsection
