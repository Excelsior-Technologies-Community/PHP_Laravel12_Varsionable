<!DOCTYPE html>
<html>
<head>
<title>Edit Post</title>
<style>
body { font-family: Arial; background:#f4f6f9; padding:40px; }
.form-box { background:white; padding:30px; max-width:600px; margin:auto; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
input, textarea { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
button { background:#e67e22; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }
</style>
</head>
<body>

<div class="form-box">
<h2>Edit Post</h2>

<form method="POST" action="{{ route('posts.update', $post) }}">
@csrf
@method('PUT')

<input type="text" name="title" value="{{ $post->title }}">
<textarea name="content">{{ $post->content }}</textarea>

<button type="submit">Update</button>
</form>

</div>
</body>
</html>