<!DOCTYPE html>
<html lang="en">
<head>
    <!-- masukkan header dari layouts -> header.blade -->
    @include('layouts.sidebar')
</head>
<body>
    Selamat Datang {{ $nama }}
    <hr>
    
    <!-- Masukkan body dari layouts -> body.blade -->
    @include('layouts.navigation')

    <hr>
    <!-- masukkan footer dari layouts -> footer.blade -->
</body>
</html>