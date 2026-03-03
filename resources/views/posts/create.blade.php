<!DOCTYPE html>
<html>
<head>
<title>Create Post</title>
<style>
body { font-family: Arial; background:#eef2f7; padding:40px; }
.form-box { background:white; padding:30px; max-width:600px; margin:auto; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
input, textarea { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; }
button { background:#3498db; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; }
</style>
</head>
<body>

<div class="form-box">
<h2>Create Post</h2>

<form method="POST" action="{{ route('posts.store') }}">
@csrf
<input type="text" name="title" placeholder="Enter Title">
<textarea name="content" placeholder="Enter Content"></textarea>
<button type="submit">Save</button>
</form>

</div>
</body>
</html>