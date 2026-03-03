<!DOCTYPE html>
<html>
<head>
<title>Posts</title>
<style>
body { font-family: Arial; background: #f4f6f9; margin:0; padding:30px; }
.container { max-width: 900px; margin:auto; }
.card { background:white; padding:20px; margin-bottom:20px; border-radius:8px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
.btn { padding:8px 15px; text-decoration:none; border-radius:5px; font-size:14px; }
.btn-primary { background:#3498db; color:white; }
.btn-warning { background:#f39c12; color:white; }
.btn-info { background:#2ecc71; color:white; }
h1 { margin-bottom:20px; }
</style>
</head>
<body>

<div class="container">
<h1> All Posts</h1>

<a href="{{ route('posts.create') }}" class="btn btn-primary">+ Create Post</a>

@foreach($posts as $post)
<div class="card">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content }}</p>

    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('posts.versions', $post) }}" class="btn btn-info">Versions</a>
</div>
@endforeach

</div>
</body>
</html>