<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FINARA | Finacial App Nusantara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="m-0 p-0 h-screen bg-cover bg-center relative" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('assets/images/bg-education.jpg') }}');">
    <div class="w-full flex justify-between items-center px-6 py-3 bg-[linear-gradient(to_right,_#8BEDFF_0%,_#99D8FF_20%,_#8EBBFF_40%,_#A9B7F5_60%,_#BFA8E6_80%,_#C8A2C8_100%)]">
        <div class="flex items-center gap-3 font-bold text-lg">
            <img src="{{ asset('assets/images/logo.finara.jpg') }}" class="h-12" style="mix-blend-mode: multiply;">FINARA -
            <span>Finacial App Nusantara</span>
        </div>
        <div class="flex items-center gap-4 font-bold">
            <div class="relative group">
                <button class="px-4 py-2 rounded-lg bg-[#38D0CD] text-black font-semibold hover:bg-[#2EFBF7] transition-all duration-200 w-[150px] text-center">Login | App</button>
                <div class="absolute left-0 mt-2 w-[150px] bg-[#38D0CD] rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 translate-y-2 group-hover:translate-y-0 z-50">
                    <a href="#" onclick="openWithCode(event, '{{ route('login.admin') }}', 'admin')" class="block px-4 py-2 hover:bg-[#2EFBF7] transition rounded-t-xl text-center">Operator</a>
                    <a href="#" onclick="openWithCode(event, '{{ route('login.staff') }}', 'staff')" class="block px-4 py-2 hover:bg-[#2EFBF7] transition text-center">Pegawai</a>
                    <a href="{{ route('login.parent') }}" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] rounded-b-xl text-center">Orang tua</a>
                </div>
            </div>
            <div class="relative group">
                <button class="px-4 py-2 rounded-lg bg-[#38D0CD] text-black font-semibold hover:bg-[#2EFBF7] transition-all duration-200 w-[150px] text-center">About Us</button>
                <div class="absolute left-0 mt-2 w-[150px] bg-[#38D0CD] rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 translate-y-2 group-hover:translate-y-0 z-50">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] rounded-t-xl text-center">Sambutan</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] text-center">Visi & Misi</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] rounded-b-xl text-center">Struktur</a>
                </div>
            </div>
            <div class="relative group">
                <button class="px-4 py-2 rounded-lg bg-[#38D0CD] text-black font-semibold hover:bg-[#2EFBF7] transition-all duration-200 w-[150px] text-center">More Info</button>
                <div class="absolute left-0 mt-2 w-[150px] bg-[#38D0CD] rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 translate-y-2 group-hover:translate-y-0 z-50">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] rounded-t-xl text-center">Features</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] text-center">Contacts</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] text-center">Location</a>
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-[#2EFBF7] rounded-b-xl text-center">FAQ</a>
                </div>
            </div>
        </div>
    </div>
   <div class="text-center mt-32 bg-[linear-gradient(to_right,_#EA4FFF_0%,_#F153D7_50%,_#F22090_100%)] bg-clip-text text-transparent drop-shadow-[0_0_15px_rgba(0,0,0,0.5)]">
        <h1 class="text-[72px] font-normal italic">Welcome</h1>
        <h2 class="text-[55px] font-bold -mt-6">Sistem Pembukuan Pendidikan</h2>
    </div>
    <div class="absolute bottom-6 left-6">
        <img src="{{ asset('assets/images/finara1.png') }}" alt="SAI" class="h-16 md:h-[65px]">
    </div>
    <div class="absolute bottom-6 right-6">
        <img src="{{ asset('assets/images/finara1.png') }}" alt="SAI" class="h-16 md:h-[65px]">
    </div>
    <div class="absolute bottom-40 left-1/2 -translate-x-1/2 text-center text-white drop-shadow-lg">
        <p class="text-xl font-semibold">Solusi Pembukuan Modern Untuk Pendidikan Indonesia</p>
        <p class="text-md mt-2 max-w-xl mx-auto opacity-90">Kelola keuangan sekolah dengan mudah, cepat, dan terintegrasi dalam satu aplikasi berbasis teknologi terbaru.</p>
    </div>
    <div id="codeModal" class="fixed inset-0 hidden bg-black/60 flex items-center justify-center z-50">
        <div id="modalBox" class="bg-gradient-to-br from-[#4CC0FF] to-[#3700FF] p-8 rounded-2xl w-[350px] text-center shadow-2xl">
            <h2 class="text-white text-xl font-bold mb-4">Enter Access Code</h2>
            <div class="relative">
                <input type="password" id="accessCodeInput" class="w-full p-3 rounded-xl text-center text-[#1A237E] font-bold outline-none border-2 border-white" placeholder="Enter code">
                <img id="togglePassword" src="{{ asset('assets/images/HidePassword.png') }}" class="absolute right-4 top-1/2 -translate-y-1/2 h-6 w-6 cursor-pointer select-none" alt="toggle password">
            </div>
            <p id="attemptInfo" class="text-white mt-2 text-sm"></p>
            <p id="loadingInfo" class="text-white mt-2 text-sm hidden">Verifying<span id="dots">.</span></p>
            <p id="grantedMessage" class="hidden opacity-0 text-green-300 font-bold text-lg mt-3 transition-all duration-500"><span class="inline-block mr-2">✔</span> Access Granted!</p>
            <div class="mt-6 flex gap-3">
                <button id="verifyBtn" onclick="checkCode()" class="w-1/2 bg-white rounded-xl py-2 font-bold hover:bg-[#3700FF] hover:text-white transition flex items-center justify-center gap-2">
                    <span id="verifyText">Verify</span>
                    <span id="verifySpinner" class="hidden animate-spin h-5 w-5 border-2 border-[#1A237E] border-t-transparent rounded-full"></span>
                </button>
                <button onclick="closeModal()" class="w-1/2 bg-red-500 text-white rounded-xl py-2 hover:bg-red-600 transition">Cancel</button>
            </div>
        </div>
    </div>
    <script>
    function openWithCode(event, url, type) {
        event.preventDefault();
        window.currentType = type;
        document.getElementById("accessCodeInput").value = ""
        document.getElementById("attemptInfo").innerText = ""
        document.getElementById("codeModal").classList.remove("hidden");
    }
    function closeModal(){
        document.getElementById("codeModal").classList.add("hidden");
    }
    async function checkCode(){
        const code = document.getElementById("accessCodeInput").value;
        const attemptInfo = document.getElementById("attemptInfo");
        const input = document.getElementById("accessCodeInput");
        const verifyBtn = document.getElementById("verifyBtn");
        const verifyText = document.getElementById("verifyText");
        const spinner = document.getElementById("verifySpinner");
        const granted = document.getElementById("grantedMessage");

        // reset pesan sebelumnya
        attemptInfo.innerText = "";
        if (granted) {
            // sembunyikan granted message kalau sempat muncul sebelumnya
            granted.classList.add("hidden");
            granted.classList.add("opacity-0");
        }
        input.disabled = true;
        verifyBtn.disabled = true;
        verifyText.innerText = "Verifying...";
        spinner.classList.remove("hidden");
        try {
            const res = await fetch("{{ route('verify.code') }}", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    type: window.currentType,
                    code: code
                })
            });

            const contentType = res.headers.get('content-type') || '';
            let data = null;
            if (contentType.includes('application/json')) {
                data = await res.json();
            } else {
                const text = await res.text().catch(()=>"");
                console.error("Non-JSON response:", res.status, text);
                attemptInfo.innerText = `Server returned non-JSON response (${res.status}). Refresh and try again.`;
                return;
            }

            // kalau server return 4xx/5xx, beri pesan juga
            if (!res.ok) {
                if (res.status === 419) {
                    attemptInfo.innerText = "Session expired (419). Refresh halaman lalu coba lagi.";
                    return;
                }
                if (res.status === 422 && data?.errors) {
                    const firstKey = Object.keys(data.errors)[0];
                    const firstMsg = firstKey ? (data.errors[firstKey]?.[0] || "") : "";
                    attemptInfo.innerText = firstMsg || data.message || `Validasi gagal (${res.status}).`;
                    return;
                }
                attemptInfo.innerText = data?.message || `Server error (${res.status}). Try again.`;
                return;
            }

            spinner.classList.add("hidden");
            if (data.status === "success") {
                // show granted (tidak mengganggu layout karena awalnya .hidden .opacity-0)
                if (granted) {
                    granted.classList.remove("hidden");
                    // biar fade in terlihat
                    setTimeout(()=> granted.classList.remove("opacity-0"), 10);
                }

                verifyText.innerText = "Redirecting...";
                spinner.classList.remove("hidden");

                setTimeout(()=> {
                    window.location.href = data.redirect;
                }, 1100);
                return;
            }
            if (data.status === "misconfigured") {
                attemptInfo.innerText = data.message || "Access code belum dikonfigurasi.";
                verifyText.innerText = "Verify";
                input.disabled = false;
                verifyBtn.disabled = false;
                input.focus();
                return;
            }
            if (data.status === "already_logged_in") {
                const currentRole = data.current_role;
                const requestedType = data.requested_type || window.currentType;
                const desiredRole = requestedType === 'admin' ? 'admin' : 'pegawai';

                // Kalau role sama, langsung arahkan ke dashboard
                if (data.redirect && currentRole && currentRole === desiredRole) {
                    attemptInfo.innerText = "Kamu sudah login. Mengarahkan ke dashboard...";
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 300);
                    return;
                }

                // Kalau role beda (mis: masih login sebagai orangtua tapi mau login operator), minta logout dulu
                const label = requestedType === 'admin' ? 'Operator' : 'Pegawai';
                attemptInfo.innerText = `Kamu masih login sebagai ${currentRole || 'user'}.`;

                const confirmLogout = confirm(`Kamu masih login sebagai ${currentRole || 'user'}. Logout dulu untuk login ${label}?`);
                if (confirmLogout && data.logout_url) {
                    try {
                        await fetch(data.logout_url, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                        });
                    } catch (e) {
                        console.error('Logout failed', e);
                    }
                    // Setelah logout, reload agar session bersih (user bisa klik Login | App lagi)
                    window.location.reload();
                    return;
                }

                // Kalau user tidak mau logout, arahkan saja ke dashboard yg sedang aktif (jika ada)
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 300);
                    return;
                }

                verifyText.innerText = "Verify";
                input.disabled = false;
                verifyBtn.disabled = false;
                input.focus();
                return;
            }
            if (data.status === "error") {
                attemptInfo.innerText = `Wrong code (${data.attempts}/3)`;

                verifyText.innerText = "Verify";
                input.disabled = false;
                verifyBtn.disabled = false;
                input.value = "";
                input.focus();
                return;
            }
            if (data.status === "locked" || data.status === "cooldown") {
                verifyText.innerText = "Verify";
                input.disabled = true;
                startTimer(data.remaining);
                verifyBtn.disabled = false;
                return;
            }
            attemptInfo.innerText = "Unexpected server response.";
            verifyText.innerText = "Verify";
            input.disabled = false;
            verifyBtn.disabled = false;
        } catch (err) {
            console.error("checkCode error:", err);
            // network error / JSON parse error
            attemptInfo.innerText = "Network error. Check your connection and try again.";
            verifyText.innerText = "Verify";
            // reset UI
            input.disabled = false;
            verifyBtn.disabled = false;
            spinner.classList.add("hidden");
        }
    }
    function startTimer(seconds){
        const attemptInfo = document.getElementById("attemptInfo")
        const input = document.getElementById("accessCodeInput")
        const interval = setInterval(()=>{
            attemptInfo.innerText = `Too many attempts! Try again in ${seconds}s`
            seconds--
            if(seconds < 0){
                clearInterval(interval);
                input.disabled = false
                attemptInfo.innerText = "You can try again ✅"
            }
        },1000)
    }
    document.getElementById("accessCodeInput")?.addEventListener("keypress", e=>{
        if(e.key==="Enter") checkCode()
    })
    // Toggle show/hide password
    // Toggle show/hide password using img src
    const pwInput = document.getElementById("accessCodeInput");
    const togglePw = document.getElementById("togglePassword");

    togglePw.addEventListener("click", () => {
        if (pwInput.type === "password") {
            pwInput.type = "text";
            togglePw.src = "{{ asset('assets/images/ShowPassword.png') }}"; // mata dicoret
        } else {
            pwInput.type = "password";
            togglePw.src = "{{ asset('assets/images/HidePassword.png') }}"; // mata biasa
        }
    });
    </script>
</body>
</html>