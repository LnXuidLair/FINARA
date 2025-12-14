@extends('layouts.guest')
@section('title', 'FINARA | Finacial App Nusantara')
@section('header-title')
    Activate Staff Account
@endsection
@section('content')
<form method="POST" action="{{ route('register.staff.post') }}">
    @csrf
    <h3 class="text-3xl font-bold text-center mb-6 text-[#3700FF]">Activate</h3>
    <div class="mb-4">
        <select name="job_position" 
            class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] 
            placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
            <option disabled selected>Select Job Position</option>
            <option value="headmaster">Headmaster</option>
            <option value="teacher">Teacher</option>
            <option value="regular staff">Regular Staff</option>
        </select>
    </div>
    <div class="mb-4">
        <input type="text" name="name" placeholder="Name"
            class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] 
            placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="mb-4 relative flex gap-2">
        <input type="number" name="nip" id="nip" placeholder="Staff ID Number"
            class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] 
            placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
        <button type="button"
            onclick="verifyStaffId()" id="verifbtn"
            class="px-4 py-3 rounded-xl bg-[linear-gradient(to_bottom,_#6BD2C9_0%,_#70BCE5_20%,_#36AEC8_40%,_#47AEC6_60%,_#329EB9_80%,_#68A3D6_100%)] font-semibold text-[#1A237E] transition-all duration-300">
            Verify
        </button>
    </div>
    <div class="mb-4">
        <input type="password" name="password" placeholder="Password"
            class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] 
            placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <div class="mb-4">
        <input type="password" name="password_confirmation" placeholder="Confirm Password"
            class="w-full px-4 py-3 rounded-xl border border-[#1A237E] bg-[#4CC0FF] 
            placeholder-[#1A237E] font-semibold text-[#1A237E]" required>
    </div>
    <button type="submit"
        class="w-full py-2 rounded-xl text-[#DCBCBC] font-bold text-xl 
        bg-[linear-gradient(to_bottom,_#916FF5_0%,_#6637E8_20%,_#3B099F_40%,_#4F33B1_60%,_#5A33A9_80%,_#8467DB_100%)]">
        Activate Account
    </button>
</form>
<script>
function verifyStaffId() {
    let id = document.getElementById("nip").value;
    let btn = document.getElementById("verifbtn");
    let input = document.getElementById("nip");
    if(id.trim() === "") {
        alert("Please enter Staff ID Number first.");
        return;
    }
    fetch("{{ route('verify.staff') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ nip: id })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.valid) {
            alert("NIP anda tidak valid atau belum terdaftar");
            return;
        }
        if (data.verified) {
            btn.innerHTML = "✔ Already Verified";
            btn.classList.remove("bg-[linear-gradient(to_bottom,_#6BD2C9_0%,_#70BCE5_20%,_#36AEC8_40%,_#47AEC6_60%,_#329EB9_80%,_#68A3D6_100%)]", "text-[#1A237E]");
            btn.classList.add("bg-green-600", "text-[#DCBCBC]");
            btn.disabled = true;
            input.disabled = true;
            return;
        }
        btn.innerHTML = "✔ Verified";
        btn.classList.remove("bg-[linear-gradient(to_bottom,_#6BD2C9_0%,_#70BCE5_20%,_#36AEC8_40%,_#47AEC6_60%,_#329EB9_80%,_#68A3D6_100%)]", "text-[#1A237E]");
        btn.classList.add("bg-green-600", "text-[#DCBCBC]");
        btn.disabled = true;
        input.disabled = true;
    });
}
</script>
@endsection