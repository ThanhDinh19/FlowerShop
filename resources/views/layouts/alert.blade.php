<style>
    /* ✅ Căn góc dưới phải */
    .custom-alert {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 1050;
        min-width: 250px;
        max-width: 350px;
        opacity: 0.95;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.4s ease, fadeOut 0.5s ease 2.5s forwards;
    }

    /* Hiệu ứng trượt + mờ dần */
    @keyframes slideIn {
        from { transform: translateX(50px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
        to { opacity: 0; transform: translateX(20px); }
    }
</style>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
        🎉 {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
        ❌ {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show custom-alert" role="alert">
        ⚠️ {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show custom-alert" role="alert">
        ℹ️ {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
