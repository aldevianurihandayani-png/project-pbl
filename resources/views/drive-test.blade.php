<!doctype html><meta charset="utf-8">
<title>Test Upload ke Google Drive</title>
<body style="font-family:Arial;margin:40px">
  <h2>Test Upload ke Google Drive</h2>
  @if(session('ok')) <p style="color:green">{{ session('ok') }}</p> @endif
  @if($errors->any()) <p style="color:red">{{ implode(', ', $errors->all()) }}</p> @endif
  <form method="POST" action="{{ route('drive.test.upload') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
  </form>
</body>
