@extends('layouts.guest')
@section('title', 'FINARA | Financial App Nusantara')
@section('header-title')
    Register Parent Account
@endsection
@section('content')
<form method="POST" action="{{ route('register.parent.post') }}">
    @csrf
    <h3 class="text-3xl font-bold text-center mb-6 text-[#3700FF]">Register</h3>
    <div class="mb-4 relative flex gap-2">
        <input type="number" name="nisn" id="nisn" placeholder="Child ID Number" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
        <button type="button" onclick="verifyNisn()" id="verifbtn" class="px-4 py-3 rounded-xl bg-[linear-gradient(to_bottom,_#6BD2C9_0%,_#70BCE5_20%,_#36AEC8_40%,_#47AEC6_60%,_#329EB9_80%,_#68A3D6_100%)] font-semibold text-[#1A237E] transition-all duration-300">
            Verify
        </button>
    </div>
    <div class="mb-4">
        <input type="text" name="name" placeholder="Name" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="mb-4">
        <input type="email" name="email" id="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="mb-4">
        <input type="password" name="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="mb-4">
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <button type="submit" class="w-full py-2 rounded-xl text-[#DCBCBC] font-bold text-xl bg-[linear-gradient(to_bottom,_#916FF5_0%,_#6637E8_20%,_#3B099F_40%,_#4F33B1_60%,_#5A33A9_80%,_#8467DB_100%)]">
        Create Parent Account
    </button>
</form>
<script>
function verifyNisn() {
    let nisn = document.getElementById("nisn").value;
    let btn = document.getElementById("verifbtn");
    let input = document.getElementById("nisn");
    if(nisn.trim() === "") {
        alert("Please enter Child ID Number first.");
        return;
    }
    fetch("{{ route('verify.nisn') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ nisn: nisn })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.valid) {
            alert("NISN tidak valid atau belum terdaftar di sistem.");
            return;
        }
        if (data.taken) {
            alert("NISN ini sudah terhubung dengan akun orangtua lain.");
            return;
        }
        btn.innerHTML = "✔ Verified";
        btn.classList.remove(
            "bg-[linear-gradient(to_bottom,_#6BD2C9_0%,_#70BCE5_20%,_#36AEC8_40%,_#47AEC6_60%,_#329EB9_80%,_#68A3D6_100%)]", 
            "text-[#1A237E]"
        );
        btn.classList.add("bg-green-600", "text-[#DCBCBC]");
        btn.disabled = true;
        input.disabled = true;
    });
}
</script>
@endsection