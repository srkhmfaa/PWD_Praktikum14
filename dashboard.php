<?php
require_once 'auth.php';
requireLogin(); // Auto redirect ke index.html jika belum login

$displayName = $_SESSION['full_name'] ?: $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – <?= htmlspecialchars($displayName) ?></title>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --green: #085041; --green-light: #E1F5EE; --green-mid: #9FE1CB; }
    body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

    /* Sidebar */
    #sidebar {
      width: 240px; min-height: 100vh;
      background: var(--green); color: #fff;
      position: fixed; top: 0; left: 0;
      display: flex; flex-direction: column;
    }
    #sidebar .brand {
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,.15);
    }
    #sidebar .brand h6 { color: var(--green-light); font-weight: 600; margin: 0 0 2px; }
    #sidebar .brand small { color: var(--green-mid); font-size: 11px; }
    #sidebar .user-info {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,.15);
      font-size: 13px;
    }
    #sidebar .user-info .name { color: #fff; font-weight: 500; }
    #sidebar .user-info .role { color: var(--green-mid); font-size: 11px; }
    #sidebar .nav-link {
      color: rgba(255,255,255,.7);
      padding: .65rem 1.5rem;
      font-size: 14px;
      border-left: 3px solid transparent;
      transition: all .2s;
    }
    #sidebar .nav-link:hover,
    #sidebar .nav-link.active {
      color: #fff;
      background: rgba(255,255,255,.1);
      border-left-color: var(--green-mid);
    }
    #sidebar .nav-link i { width: 20px; }
    #sidebar .logout-area { padding: 1rem 1.5rem; margin-top: auto; }

    /* Main */
    #main { margin-left: 240px; padding: 2rem; }

    /* Cards */
    .dash-card {
      background: #fff; border: 1px solid #e2e8f0;
      border-radius: 12px; padding: 1.25rem;
      box-shadow: 0 2px 8px rgba(0,0,0,.05);
    }
    .stat-icon {
      width: 44px; height: 44px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem;
    }

    /* Section */
    .section { display: none; }
    .section.active { display: block; }

    /* Form */
    .lbl { font-size: 12px; color: #6b7280; display: block; margin-bottom: 4px; }
    .inp-row {
      display: flex; align-items: center;
      border: 1px solid #e2e8f0; border-radius: 8px;
      overflow: hidden; margin-bottom: 12px;
      transition: border-color .2s, box-shadow .2s;
    }
    .inp-row:focus-within { border-color: var(--green); box-shadow: 0 0 0 3px var(--green-light); }
    .inp-row input {
      flex: 1; border: none; background: transparent;
      padding: 9px 10px; font-size: 14px; outline: none; color: #111;
    }
    .inp-row input::placeholder { color: #9ca3af; }
    .eye { padding: 0 10px; cursor: pointer; font-size: 15px; color: #9ca3af; border: none; background: none; }

    /* Button */
    .sbtn {
      padding: .65rem 1.5rem; border: none; border-radius: 8px;
      background: var(--green); color: var(--green-light);
      font-size: 14px; font-weight: 500; cursor: pointer; transition: opacity .15s;
    }
    .sbtn:hover { opacity: .85; }
    .sbtn:disabled { opacity: .6; cursor: not-allowed; }
    .sbtn-danger {
      padding: .65rem 1.5rem; border: none; border-radius: 8px;
      background: #dc2626; color: #fff;
      font-size: 14px; font-weight: 500; cursor: pointer; transition: opacity .15s;
    }
    .sbtn-danger:hover { opacity: .85; }

    /* Alert */
    .alert-box { font-size: 13px; padding: 9px 12px; border-radius: 8px; margin-bottom: 12px; display: none; }
    .alert-success { background: var(--green-light); color: var(--green); display: block; }
    .alert-danger  { background: #fef2f2; color: #dc2626; display: block; }

    /* Skeleton */
    .skeleton { background: #e2e8f0; border-radius: 6px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* Danger zone */
    .danger-zone { border: 1px solid #fca5a5; border-radius: 12px; background: #fff5f5; padding: 1.5rem; }

    @media(max-width:768px) {
      #sidebar { width:100%; min-height:auto; position:static; }
      #main { margin-left:0; }
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<nav id="sidebar">
  <div class="brand">
    <h6>Secure Dashboard</h6>
    <small>Pemrograman Web P14</small>
  </div>
  <div class="user-info">
    <div class="name"><?= htmlspecialchars($displayName) ?></div>
    <div class="role">Pengguna Aktif</div>
  </div>
  <ul class="nav flex-column py-2">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-section="overview">
        <i class="bi bi-grid me-2"></i>Beranda
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-section="profile">
        <i class="bi bi-person me-2"></i>Profil Saya
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-section="edit">
        <i class="bi bi-pencil me-2"></i>Edit Profil
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#" data-section="delete" style="color:rgba(255,150,150,.8)">
        <i class="bi bi-trash3 me-2"></i>Hapus Akun
      </a>
    </li>
  </ul>
  <div class="logout-area">
    <button class="sbtn w-100" id="btnLogout" style="background:rgba(255,255,255,.15);color:#fff">
      <i class="bi bi-box-arrow-left me-1"></i>Logout
    </button>
  </div>
</nav>

<!-- MAIN CONTENT -->
<main id="main">

  <!-- SECTION: BERANDA -->
  <section class="section active" id="sec-overview">
    <h5 class="fw-semibold mb-4">Beranda</h5>
    <div class="row g-3" id="statsRow">
      <!-- Diisi JS -->
      <div class="col-6 col-lg-3"><div class="skeleton" style="height:90px"></div></div>
      <div class="col-6 col-lg-3"><div class="skeleton" style="height:90px"></div></div>
      <div class="col-6 col-lg-3"><div class="skeleton" style="height:90px"></div></div>
      <div class="col-6 col-lg-3"><div class="skeleton" style="height:90px"></div></div>
    </div>
  </section>

  <!-- SECTION: PROFIL -->
  <section class="section" id="sec-profile">
    <h5 class="fw-semibold mb-4">Profil Saya</h5>
    <div class="dash-card">
      <div id="profileContent">
        <div class="skeleton mb-3" style="height:20px;width:40%"></div>
        <div class="skeleton mb-3" style="height:20px;width:60%"></div>
        <div class="skeleton mb-3" style="height:20px;width:50%"></div>
        <div class="skeleton mb-3" style="height:20px;width:55%"></div>
      </div>
    </div>
  </section>

  <!-- SECTION: EDIT PROFIL -->
  <section class="section" id="sec-edit">
    <h5 class="fw-semibold mb-4">Edit Profil</h5>
    <div class="dash-card" style="max-width:480px">
      <div class="alert-box" id="al-edit"></div>
      <label class="lbl">Nama Lengkap</label>
      <div class="inp-row"><input type="text" id="edit_fullname" placeholder="Nama lengkap"></div>
      <label class="lbl">Email *</label>
      <div class="inp-row"><input type="email" id="edit_email" placeholder="Email"></div>
      <hr style="border-color:#e2e8f0;margin:1rem 0">
      <p style="font-size:12px;color:#9ca3af;margin-bottom:12px">Isi bagian ini hanya jika ingin ganti password:</p>
      <label class="lbl">Password Saat Ini</label>
      <div class="inp-row">
        <input type="password" id="current_pass" placeholder="Password lama">
        <button class="eye" type="button" onclick="toggleEye('current_pass', this)"><i class="bi bi-eye"></i></button>
      </div>
      <label class="lbl">Password Baru</label>
      <div class="inp-row">
        <input type="password" id="new_pass" placeholder="Password baru">
        <button class="eye" type="button" onclick="toggleEye('new_pass', this)"><i class="bi bi-eye"></i></button>
      </div>
      <button class="sbtn" id="editBtn" onclick="submitEdit()">
        <span class="btn-text">Simpan Perubahan</span>
        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
      </button>
    </div>
  </section>

  <!-- SECTION: HAPUS AKUN -->
  <section class="section" id="sec-delete">
    <h5 class="fw-semibold mb-4 text-danger">Hapus Akun</h5>
    <div class="danger-zone" style="max-width:480px">
      <p class="fw-semibold mb-1">⚠️ Tindakan ini permanen dan tidak bisa dibatalkan.</p>
      <p style="font-size:13px;color:#6b7280;margin-bottom:1rem">Seluruh data akun akan dihapus selamanya dari sistem.</p>
      <div class="alert-box" id="al-delete"></div>
      <label class="lbl">Masukkan Password Konfirmasi</label>
      <div class="inp-row" style="max-width:320px">
        <input type="password" id="confirm_pass" placeholder="Password akun kamu">
        <button class="eye" type="button" onclick="toggleEye('confirm_pass', this)"><i class="bi bi-eye"></i></button>
      </div>
      <button class="sbtn-danger" id="deleteBtn" onclick="submitDelete()">
        <span class="btn-text"><i class="bi bi-trash3 me-1"></i>Hapus Akun Permanen</span>
        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
      </button>
    </div>
  </section>

</main>=
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
let profileData = null;

/* ── NAVIGASI SIDEBAR ── */
document.querySelectorAll('[data-section]').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const target = link.dataset.section;
    document.querySelectorAll('[data-section]').forEach(l => l.classList.remove('active'));
    link.classList.add('active');
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById('sec-' + target).classList.add('active');
    if (target === 'profile') renderProfile();
    if (target === 'edit' && profileData) prefillEdit();
  });
});

/* ── TOGGLE EYE ── */
function toggleEye(id, btn) {
  const input = document.getElementById(id);
  const icon  = btn.querySelector('i');
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

/* ── ALERT ── */
function showAlert(id, type, msg) {
  const el = document.getElementById(id);
  el.className = 'alert-box alert-' + type;
  el.textContent = msg;
}
function hideAlert(id) {
  const el = document.getElementById(id);
  el.className = 'alert-box';
}

/* ── LOADING ── */
function setLoading(btn, state) {
  const text    = btn.querySelector('.btn-text');
  const spinner = btn.querySelector('.spinner-border');
  btn.disabled  = state;
  if (!btn._label) btn._label = text.innerHTML;
  text.innerHTML = state ? 'Memproses...' : btn._label;
  spinner.classList.toggle('d-none', !state);
}

/* ── FORMAT TANGGAL ── */
function fmtDate(str) {
  return new Date(str).toLocaleDateString('id-ID', {
    day: '2-digit', month: 'long', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
}

/* ── FETCH PROFIL ── */
async function fetchProfile() {
  try {
    const res  = await fetch('api/get_profile.php');
    const data = await res.json();
    if (res.ok) {
      profileData = data.user;
      renderStats();
      renderProfile();
    }
  } catch (err) {
    console.error('Gagal memuat profil:', err);
  }
}

/* ── RENDER STAT CARDS ── */
function renderStats() {
  if (!profileData) return;
  const stats = [
    { icon: 'bi-person-badge', bg: '#E1F5EE', color: '#085041', label: 'ID',        val: '#' + profileData.id },
    { icon: 'bi-at',           bg: '#ede9fe', color: '#6d28d9', label: 'Username',  val: profileData.username },
    { icon: 'bi-envelope',     bg: '#fef9c3', color: '#92400e', label: 'Email',     val: profileData.email },
    { icon: 'bi-calendar',     bg: '#fce7f3', color: '#9d174d', label: 'Terdaftar', val: fmtDate(profileData.created_at) },
  ];
  document.getElementById('statsRow').innerHTML = stats.map(s => `
    <div class="col-6 col-lg-3">
      <div class="dash-card d-flex align-items-center gap-3">
        <div class="stat-icon" style="background:${s.bg};color:${s.color}">
          <i class="bi ${s.icon}"></i>
        </div>
        <div>
          <div style="font-size:11px;color:#6b7280">${s.label}</div>
          <div style="font-size:13px;font-weight:600;word-break:break-all">${s.val}</div>
        </div>
      </div>
    </div>
  `).join('');
}

/* ── RENDER TABEL PROFIL ── */
function renderProfile() {
  if (!profileData) return;
  const rows = [
    ['ID',              '#' + profileData.id],
    ['Nama Lengkap',    profileData.full_name || '<em style="color:#9ca3af">Belum diisi</em>'],
    ['Username',        profileData.username],
    ['Email',           profileData.email],
    ['Terdaftar',       fmtDate(profileData.created_at)],
    ['Terakhir Update', fmtDate(profileData.updated_at)],
  ];
  document.getElementById('profileContent').innerHTML = `
    <table class="table table-sm table-bordered align-middle" style="font-size:14px">
      <tbody>
        ${rows.map(([k,v]) => `
          <tr>
            <th style="width:160px;color:#6b7280;font-weight:500;background:#f8fafc">${k}</th>
            <td>${v}</td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;
}

/* ── PREFILL FORM EDIT ── */
function prefillEdit() {
  document.getElementById('edit_fullname').value = profileData.full_name || '';
  document.getElementById('edit_email').value    = profileData.email || '';
}

/* ── SUBMIT EDIT PROFIL ── */
async function submitEdit() {
  const btn = document.getElementById('editBtn');
  hideAlert('al-edit');

  const fd = new FormData();
  fd.append('full_name',        document.getElementById('edit_fullname').value.trim());
  fd.append('email',            document.getElementById('edit_email').value.trim());
  fd.append('current_password', document.getElementById('current_pass').value);
  fd.append('new_password',     document.getElementById('new_pass').value);

  setLoading(btn, true);
  try {
    const res  = await fetch('api/update_profile.php', { method: 'POST', body: fd });
    const data = await res.json();
    showAlert('al-edit', res.ok ? 'success' : 'danger', data.message);
    if (res.ok) {
      await fetchProfile();
      document.getElementById('current_pass').value = '';
      document.getElementById('new_pass').value     = '';
    }
  } catch {
    showAlert('al-edit', 'danger', 'Gagal menghubungi server.');
  } finally {
    setLoading(btn, false);
  }
}

/* ── SUBMIT HAPUS AKUN ── */
async function submitDelete() {
  if (!confirm('Yakin ingin menghapus akun secara permanen?')) return;
  const btn = document.getElementById('deleteBtn');
  hideAlert('al-delete');

  const fd = new FormData();
  fd.append('confirm_password', document.getElementById('confirm_pass').value);

  setLoading(btn, true);
  try {
    const res  = await fetch('api/delete_account.php', { method: 'POST', body: fd });
    const data = await res.json();
    showAlert('al-delete', res.ok ? 'success' : 'danger', data.message);
    if (res.ok) setTimeout(() => window.location.href = data.redirect, 1500);
  } catch {
    showAlert('al-delete', 'danger', 'Gagal menghubungi server.');
  } finally {
    setLoading(btn, false);
  }
}

/* ── LOGOUT ── */
document.getElementById('btnLogout').addEventListener('click', async () => {
  try {
    const res  = await fetch('api/logout.php');
    const data = await res.json();
    window.location.href = data.redirect;
  } catch {
    window.location.href = 'index.html';
  }
});

/* ── INIT ── */
fetchProfile();
</script>
</body>
</html>