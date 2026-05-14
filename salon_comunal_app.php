<?php
// Archivo de inicio - salon_comunal_app.php
// Este archivo es solo una interfaz, todos los datos se procesan en /api/
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SalónApp — Gestión de Salón Comunal</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
--bg:#0a0f1e;--bg2:#111827;--bg3:#1a2332;--bg4:#1e2d42;
--accent:#4f9cf9;--accent2:#7c3aed;--accent3:#10b981;--accent4:#f59e0b;--danger:#ef4444;--warn:#f97316;
--text:#f0f4ff;--text2:#8b9ab8;--text3:#4a5568;
--card:#131c2e;--border:#1e3a5f;--border2:#243b5e;
--radius:12px;--radius-sm:8px;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden}
.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}

/* ===== LOGIN ===== */
#loginPage{min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);position:relative;overflow:hidden}
#loginPage::before{content:'';position:absolute;width:600px;height:600px;background:radial-gradient(circle,rgba(79,156,249,0.08) 0%,transparent 70%);top:-100px;left:-100px;border-radius:50%;pointer-events:none}
#loginPage::after{content:'';position:absolute;width:400px;height:400px;background:radial-gradient(circle,rgba(124,58,237,0.06) 0%,transparent 70%);bottom:-50px;right:-50px;border-radius:50%;pointer-events:none}
.login-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:48px 40px;width:420px;max-width:95vw;position:relative;z-index:1}
.login-logo{display:flex;align-items:center;gap:12px;margin-bottom:32px}
.login-logo-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px}
.login-logo-text{font-family:'DM Serif Display',serif;font-size:24px;color:var(--text)}
.login-logo-sub{font-size:12px;color:var(--text2);letter-spacing:1px;text-transform:uppercase}
.form-group{margin-bottom:20px}
.form-label{display:block;font-size:13px;color:var(--text2);margin-bottom:8px;font-weight:500}
.form-input{width:100%;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;color:var(--text);font-size:14px;font-family:inherit;outline:none;transition:border 0.2s}
.form-input:focus{border-color:var(--accent)}
.form-input::placeholder{color:var(--text3)}
.form-select{width:100%;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;color:var(--text);font-size:14px;font-family:inherit;outline:none;transition:border 0.2s;appearance:none;cursor:pointer}
.form-select:focus{border-color:var(--accent)}
.btn{padding:12px 24px;border-radius:var(--radius-sm);border:none;cursor:pointer;font-family:inherit;font-size:14px;font-weight:500;transition:all 0.2s;display:inline-flex;align-items:center;gap:8px}
.btn-primary{background:linear-gradient(135deg,var(--accent),#3b82f6);color:#fff;width:100%;justify-content:center}
.btn-primary:hover{opacity:0.9;transform:translateY(-1px)}
.btn-secondary{background:var(--bg3);color:var(--text);border:1px solid var(--border2)}
.btn-secondary:hover{background:var(--bg4)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{opacity:0.85}
.btn-success{background:var(--accent3);color:#fff}
.btn-success:hover{opacity:0.85}
.btn-warn{background:var(--warn);color:#fff}
.btn-warn:hover{opacity:0.85}
.btn-sm{padding:6px 14px;font-size:12px}
.login-switch{text-align:center;margin-top:20px;font-size:13px;color:var(--text2)}
.login-switch a{color:var(--accent);cursor:pointer;text-decoration:none}
.login-switch a:hover{text-decoration:underline}
.error-msg{background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:16px}
.success-msg{background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:16px}

/* ===== APP LAYOUT ===== */
#appPage{display:none;min-height:100vh;flex-direction:column}
.topbar{background:var(--card);border-bottom:1px solid var(--border);padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar-logo{font-family:'DM Serif Display',serif;font-size:20px;color:var(--text)}
.topbar-logo span{color:var(--accent)}
.user-badge{display:flex;align-items:center;gap:10px}
.user-avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:13px}
.avatar-admin{background:linear-gradient(135deg,var(--accent),var(--accent2))}
.avatar-supervisor{background:linear-gradient(135deg,var(--accent3),#059669)}
.avatar-residente{background:linear-gradient(135deg,var(--accent4),#d97706)}
.user-info{text-align:right}
.user-name{font-size:14px;font-weight:500}
.user-role{font-size:11px;color:var(--text2);text-transform:uppercase;letter-spacing:0.5px}
.role-tag{padding:3px 8px;border-radius:4px;font-size:10px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase}
.role-admin{background:rgba(79,156,249,0.15);color:var(--accent)}
.role-supervisor{background:rgba(16,185,129,0.15);color:var(--accent3)}
.role-residente{background:rgba(245,158,11,0.15);color:var(--accent4)}

.app-body{display:flex;flex:1}
.sidebar{width:220px;min-height:calc(100vh - 64px);background:var(--card);border-right:1px solid var(--border);padding:20px 0;position:sticky;top:64px;height:calc(100vh - 64px);overflow-y:auto}
.nav-section{padding:0 12px;margin-bottom:24px}
.nav-section-title{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;padding:0 12px;margin-bottom:8px}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:var(--radius-sm);cursor:pointer;font-size:13px;color:var(--text2);transition:all 0.15s;margin-bottom:2px}
.nav-item:hover{background:var(--bg3);color:var(--text)}
.nav-item.active{background:rgba(79,156,249,0.12);color:var(--accent)}
.nav-icon{width:18px;text-align:center;font-size:15px}
.nav-badge{margin-left:auto;background:var(--danger);color:#fff;font-size:10px;padding:1px 6px;border-radius:10px;font-weight:600}

.main-content{flex:1;padding:28px;overflow-y:auto;min-height:calc(100vh - 64px)}

/* ===== PANELS ===== */
.panel{display:none}
.panel.active{display:block}
.page-header{margin-bottom:28px}
.page-title{font-family:'DM Serif Display',serif;font-size:28px;color:var(--text)}
.page-subtitle{font-size:14px;color:var(--text2);margin-top:4px}

/* ===== CARDS ===== */
.card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px}
.card-title{font-size:15px;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:28px}
.stat-card{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);padding:18px;position:relative;overflow:hidden}
.stat-card::after{content:'';position:absolute;top:0;right:0;width:60px;height:60px;border-radius:0 0 0 100%;opacity:0.07}
.stat-card.blue::after{background:var(--accent)}
.stat-card.green::after{background:var(--accent3)}
.stat-card.amber::after{background:var(--accent4)}
.stat-card.purple::after{background:var(--accent2)}
.stat-card.red::after{background:var(--danger)}
.stat-icon{font-size:22px;margin-bottom:8px}
.stat-val{font-size:28px;font-weight:600;line-height:1}
.stat-label{font-size:12px;color:var(--text2);margin-top:4px}

/* ===== TABLE ===== */
.table-wrapper{overflow-x:auto;border-radius:var(--radius);border:1px solid var(--border)}
table{width:100%;border-collapse:collapse;font-size:13px}
th{background:var(--bg3);color:var(--text2);font-weight:500;padding:12px 16px;text-align:left;border-bottom:1px solid var(--border);font-size:11px;text-transform:uppercase;letter-spacing:0.5px}
td{padding:12px 16px;border-bottom:1px solid var(--border);color:var(--text);vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:var(--bg3)}

/* ===== STATUS BADGES ===== */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-pending{background:rgba(245,158,11,0.15);color:var(--accent4)}
.badge-approved{background:rgba(16,185,129,0.15);color:var(--accent3)}
.badge-rejected{background:rgba(239,68,68,0.15);color:#fca5a5}
.badge-active{background:rgba(79,156,249,0.15);color:var(--accent)}
.badge-inactive{background:rgba(75,85,99,0.3);color:var(--text2)}

/* ===== SEMAPHORE ===== */
.semaphore-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;margin-top:16px}
.sem-card{background:var(--bg3);border-radius:var(--radius);padding:16px;border-left:4px solid}
.sem-red{border-color:#ef4444}
.sem-amber{border-color:#f59e0b}
.sem-green{border-color:#10b981}
.sem-dot{width:12px;height:12px;border-radius:50%;display:inline-block;margin-right:8px}
.dot-red{background:#ef4444;box-shadow:0 0 8px #ef444480}
.dot-amber{background:#f59e0b;box-shadow:0 0 8px #f59e0b80}
.dot-green{background:#10b981;box-shadow:0 0 8px #10b98180}

/* ===== MODAL ===== */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:1000;align-items:center;justify-content:center;padding:20px}
.modal-overlay.open{display:flex}
.modal{background:var(--bg2);border:1px solid var(--border2);border-radius:16px;padding:32px;width:100%;max-width:500px;max-height:85vh;overflow-y:auto}
.modal-title{font-family:'DM Serif Display',serif;font-size:22px;margin-bottom:24px}
.modal-footer{display:flex;gap:12px;margin-top:24px;justify-content:flex-end}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}

/* ===== SEMAPHORE LEGEND ===== */
.sem-legend{display:flex;gap:20px;margin-bottom:16px;font-size:13px;color:var(--text2)}
.sem-legend-item{display:flex;align-items:center;gap:6px}

/* ===== CHART CONTAINER ===== */
.chart-box{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:20px}
.chart-title{font-size:14px;font-weight:500;margin-bottom:16px;color:var(--text)}
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}

/* ===== INVENTORY ===== */
.inv-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-top:16px}
.inv-card{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);padding:16px;transition:border-color 0.2s}
.inv-card:hover{border-color:var(--border2)}
.inv-name{font-weight:600;font-size:14px;margin-bottom:6px}
.inv-qty{font-size:26px;font-weight:700;color:var(--accent)}
.inv-unit{font-size:11px;color:var(--text2);margin-left:4px}
.inv-status{font-size:11px;margin-top:8px}
.inv-low{color:#fca5a5}
.inv-ok{color:#6ee7b7}

/* ===== FILTERS ===== */
.filter-bar{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;align-items:center}
.filter-select{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;color:var(--text);font-size:13px;font-family:inherit;outline:none;cursor:pointer}

/* ===== NOTIFICATION ===== */
.notif{position:fixed;top:80px;right:24px;background:var(--bg2);border:1px solid var(--border2);border-radius:var(--radius-sm);padding:14px 18px;font-size:13px;z-index:2000;max-width:320px;animation:slideIn 0.3s ease;box-shadow:0 8px 32px rgba(0,0,0,0.4)}
.notif.success{border-color:rgba(16,185,129,0.4);color:#6ee7b7}
.notif.error{border-color:rgba(239,68,68,0.4);color:#fca5a5}
.notif.warn{border-color:rgba(245,158,11,0.4);color:#fcd34d}
@keyframes slideIn{from{transform:translateX(100px);opacity:0}to{transform:translateX(0);opacity:1}}

.divider{height:1px;background:var(--border);margin:20px 0}
.text-muted{color:var(--text2);font-size:13px}
.flex{display:flex}.items-center{align-items:center}.gap-8{gap:8px}.gap-12{gap:12px}.gap-16{gap:16px}.justify-between{justify-content:space-between}.flex-wrap{flex-wrap:wrap}.mb-16{margin-bottom:16px}.mb-20{margin-bottom:20px}.mb-8{margin-bottom:8px}.mt-8{margin-top:8px}.mt-16{margin-top:16px}

.report-btn{background:var(--bg3);border:1px solid var(--border2);border-radius:var(--radius);padding:20px;cursor:pointer;transition:all 0.2s;text-align:left;display:flex;align-items:center;gap:16px}
.report-btn:hover{border-color:var(--accent);background:rgba(79,156,249,0.06)}
.report-icon{font-size:28px}
.report-name{font-weight:600;font-size:14px;margin-bottom:4px}
.report-desc{font-size:12px;color:var(--text2)}
.reports-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}

.empty-state{text-align:center;padding:48px 20px;color:var(--text2)}
.empty-icon{font-size:48px;margin-bottom:12px}
.empty-text{font-size:14px}

.tabs{display:flex;gap:4px;background:var(--bg3);border-radius:var(--radius-sm);padding:4px;width:fit-content;margin-bottom:20px}
.tab{padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;color:var(--text2);transition:all 0.15s}
.tab.active{background:var(--bg);color:var(--text);font-weight:500}

.profile-card{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);padding:24px;display:flex;align-items:center;gap:20px}
.profile-avatar{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700}

.fc-pill{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:4px;font-size:11px}

canvas{max-width:100%;height:220px !important}
</style>
</head>
<body>
<h2 class="sr-only">SalónApp — Sistema de gestión de salón comunal con reservas, inventario y usuarios</h2>

<!-- ===== LOGIN PAGE ===== -->
<div id="loginPage">
  <div class="login-card">
    <div class="login-logo">
      <div class="login-logo-icon">🏛️</div>
      <div>
        <div class="login-logo-text">SalónApp</div>
        <div class="login-logo-sub">Gestión Comunal</div>
      </div>
    </div>

    <div id="loginView">
      <div id="loginError" class="error-msg" style="display:none"></div>
      <div class="form-group">
        <label class="form-label">Correo electrónico</label>
        <input type="email" id="loginEmail" class="form-input" placeholder="tu@correo.com">
      </div>
      <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" id="loginPass" class="form-input" placeholder="••••••••">
      </div>
      <button class="btn btn-primary" onclick="doLogin()">Iniciar Sesión</button>
      <div class="login-switch" style="margin-top:16px">
        ¿No tienes cuenta? <a onclick="showRegister()">Regístrate como residente</a>
      </div>
      <div class="login-switch" style="margin-top:10px;font-size:11px;color:var(--text3)">
        Admin: admin@salon.com / admin123
      </div>
    </div>

    <div id="registerView" style="display:none">
      <div id="regError" class="error-msg" style="display:none"></div>
      <div id="regSuccess" class="success-msg" style="display:none"></div>
      <div class="form-group">
        <label class="form-label">Nombre completo</label>
        <input type="text" id="regName" class="form-input" placeholder="Juan Pérez">
      </div>
      <div class="form-group">
        <label class="form-label">Correo electrónico</label>
        <input type="email" id="regEmail" class="form-input" placeholder="tu@correo.com">
      </div>
      <div class="form-group">
        <label class="form-label">Número de apartamento</label>
        <input type="text" id="regApto" class="form-input" placeholder="Ej: 301">
      </div>
      <div class="form-group">
        <label class="form-label">Teléfono</label>
        <input type="text" id="regPhone" class="form-input" placeholder="300 000 0000">
      </div>
      <div class="form-group">
        <label class="form-label">Contraseña</label>
        <input type="password" id="regPass" class="form-input" placeholder="Mínimo 6 caracteres">
      </div>
      <div class="form-group">
        <label class="form-label">Confirmar contraseña</label>
        <input type="password" id="regPass2" class="form-input" placeholder="Repite la contraseña">
      </div>
      <button class="btn btn-primary" onclick="doRegister()">Crear Cuenta</button>
      <div class="login-switch" style="margin-top:16px">
        ¿Ya tienes cuenta? <a onclick="showLogin()">Iniciar sesión</a>
      </div>
    </div>
  </div>
</div>

<!-- ===== APP PAGE ===== -->
<div id="appPage">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-logo">Salón<span>App</span></div>
      <span id="topRoleBadge" class="role-tag"></span>
    </div>
    <div class="user-badge">
      <div class="user-info">
        <div class="user-name" id="topUserName"></div>
        <div class="user-role" id="topUserRole"></div>
      </div>
      <div id="topAvatar" class="user-avatar avatar-residente"></div>
      <button class="btn btn-secondary btn-sm" onclick="doLogout()">Salir</button>
    </div>
  </div>

  <div class="app-body">
    <!-- SIDEBAR -->
    <div class="sidebar">
      <div class="nav-section">
        <div class="nav-section-title">Principal</div>
        <div class="nav-item active" onclick="goPanel('dashboard')"><span class="nav-icon">📊</span> Dashboard</div>
        <div class="nav-item" onclick="goPanel('reservas')"><span class="nav-icon">📅</span> Mis Reservas</div>
        <div class="nav-item" onclick="goPanel('nuevaReserva')"><span class="nav-icon">➕</span> Nueva Reserva</div>
      </div>
      <div class="nav-section" id="navAdmin">
        <div class="nav-section-title">Administración</div>
        <div class="nav-item" onclick="goPanel('todasReservas')"><span class="nav-icon">📋</span> Todas las Reservas <span class="nav-badge" id="pendingBadge" style="display:none">0</span></div>
        <div class="nav-item" onclick="goPanel('usuarios')"><span class="nav-icon">👥</span> Usuarios</div>
        <div class="nav-item" onclick="goPanel('inventario')"><span class="nav-icon">📦</span> Inventario</div>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">Mi Cuenta</div>
        <div class="nav-item" onclick="goPanel('perfil')"><span class="nav-icon">👤</span> Perfil</div>
      </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">

      <!-- DASHBOARD -->
      <div class="panel active" id="panel-dashboard">
        <div class="page-header">
          <div class="page-title">Dashboard</div>
          <div class="page-subtitle" id="dashboardWelcome"></div>
        </div>
        <div class="stats-grid" id="dashStats"></div>
        <div class="charts-grid" id="dashCharts">
          <div class="chart-box">
            <div class="chart-title">Reservas por mes</div>
            <canvas id="chartMeses"></canvas>
          </div>
          <div class="chart-box">
            <div class="chart-title">Estado de reservas</div>
            <canvas id="chartEstados"></canvas>
          </div>
        </div>
        <div id="dashSemaforo"></div>
      </div>

      <!-- MIS RESERVAS -->
      <div class="panel" id="panel-reservas">
        <div class="page-header flex justify-between items-center">
          <div><div class="page-title">Mis Reservas</div><div class="page-subtitle">Historial de solicitudes</div></div>
          <button class="btn btn-primary btn-sm" onclick="goPanel('nuevaReserva')">+ Nueva Reserva</button>
        </div>
        <div class="filter-bar">
          <select class="filter-select" onchange="renderMisReservas()" id="filtroMisReservas">
            <option value="all">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="aprobada">Aprobada</option>
            <option value="rechazada">Rechazada</option>
          </select>
        </div>
        <div class="table-wrapper"><table><thead><tr><th>Fecha</th><th>Evento</th><th>Asistentes</th><th>Estado</th><th>Insumos</th><th>Observaciones</th></tr></thead><tbody id="tbodyMisReservas"></tbody></table></div>
      </div>

      <!-- NUEVA RESERVA -->
      <div class="panel" id="panel-nuevaReserva">
        <div class="page-header">
          <div class="page-title">Nueva Reserva</div>
          <div class="page-subtitle">Horario disponible: 12:00 — 00:00</div>
        </div>
        <div class="card" style="max-width:600px">
          <div id="reservaError" class="error-msg" style="display:none"></div>
          <div class="form-group">
            <label class="form-label">Fecha del evento <span style="color:var(--accent4);font-size:11px">(mín. 48h · máx. 90 días)</span></label>
            <input type="date" id="resDate" class="form-input" onchange="checkDisponibilidad()">
            <div id="dispStatus" class="text-muted mt-8" style="font-size:12px"><span style="color:var(--text2);font-size:12px">📌 Solo puedes reservar entre <b>48 horas</b> y <b>90 días</b> a partir de hoy.</span></div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Nombre del evento</label>
              <input type="text" id="resNombre" class="form-input" placeholder="Cumpleaños, reunión...">
            </div>
            <div class="form-group">
              <label class="form-label">N° de asistentes</label>
              <input type="number" id="resAsistentes" class="form-input" placeholder="Ej: 20" min="1" max="100">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea id="resDesc" class="form-input" rows="3" placeholder="Describe brevemente el evento..."></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Insumos requeridos (selecciona)</label>
            <div id="insumosCheck" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px"></div>
          </div>
          <div class="form-group">
            <label class="form-label">Observaciones adicionales</label>
            <input type="text" id="resObs" class="form-input" placeholder="Algún requerimiento especial...">
          </div>
          <button class="btn btn-primary" onclick="crearReserva()">Solicitar Reserva</button>
        </div>
      </div>

      <!-- TODAS LAS RESERVAS (admin/supervisor) -->
      <div class="panel" id="panel-todasReservas">
        <div class="page-header">
          <div class="page-title">Gestión de Reservas</div>
          <div class="page-subtitle">Revisa, autoriza o rechaza las solicitudes de los residentes</div>
        </div>
        <div style="background:rgba(79,156,249,0.07);border:1px solid rgba(79,156,249,0.2);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;font-size:13px;color:var(--text2)">
          ℹ️ Las reservas <b>pendientes</b> pueden ser <span style="color:#6ee7b7">aprobadas</span> o <span style="color:#fca5a5">rechazadas</span>. Haz clic en <b>Gestionar</b> para revisar el detalle y tomar acción.
        </div>
        <div class="filter-bar">
          <select class="filter-select" onchange="renderTodasReservas()" id="filtroEstado">
            <option value="all">Todos</option>
            <option value="pendiente">Pendientes</option>
            <option value="aprobada">Aprobadas</option>
            <option value="rechazada">Rechazadas</option>
          </select>
          <select class="filter-select" onchange="renderTodasReservas()" id="filtroMes">
            <option value="all">Todo el período</option>
          </select>
        </div>
        <div class="table-wrapper"><table><thead><tr><th>Fecha</th><th>Residente</th><th>Apto</th><th>Evento</th><th>Asistentes</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tbodyTodasReservas"></tbody></table></div>
      </div>

      <!-- USUARIOS -->
      <div class="panel" id="panel-usuarios">
        <div class="page-header flex justify-between items-center">
          <div><div class="page-title">Gestión de Usuarios</div><div class="page-subtitle">Solo el administrador puede gestionar usuarios</div></div>
          <button class="btn btn-primary btn-sm" id="btnNuevoUsuario" onclick="openModal('modalUsuario')">+ Nuevo Usuario</button>
        </div>
        <div class="table-wrapper"><table><thead><tr><th>Nombre</th><th>Email</th><th>Perfil</th><th>Apto</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tbodyUsuarios"></tbody></table></div>
      </div>

      <!-- INVENTARIO -->
      <div class="panel" id="panel-inventario">
        <div class="page-header flex justify-between items-center">
          <div><div class="page-title">Inventario</div><div class="page-subtitle">Control de insumos del salón comunal</div></div>
          <button class="btn btn-primary btn-sm" id="btnNuevoInsumo" onclick="openModal('modalInsumo')">+ Agregar Insumo</button>
        </div>
        <div class="inv-grid" id="inventarioGrid"></div>
      </div>

      <!-- PERFIL -->
      <div class="panel" id="panel-perfil">
        <div class="page-header">
          <div class="page-title">Mi Perfil</div>
          <div class="page-subtitle">Información de tu cuenta</div>
        </div>
        <div class="profile-card mb-20">
          <div class="profile-avatar" id="perfilAvatar"></div>
          <div>
            <div style="font-size:20px;font-weight:600" id="perfilNombre"></div>
            <div style="font-size:14px;color:var(--text2);margin-top:4px" id="perfilEmail"></div>
            <div style="margin-top:8px" id="perfilRoleBadge"></div>
          </div>
        </div>
        <div class="card" style="max-width:500px">
          <div class="card-title">Cambiar Contraseña</div>
          <div id="passError" class="error-msg" style="display:none"></div>
          <div id="passSuccess" class="success-msg" style="display:none"></div>
          <div class="form-group"><label class="form-label">Contraseña actual</label><input type="password" id="passActual" class="form-input" placeholder="••••••••"></div>
          <div class="form-group"><label class="form-label">Nueva contraseña</label><input type="password" id="passNueva" class="form-input" placeholder="••••••••"></div>
          <div class="form-group"><label class="form-label">Confirmar nueva</label><input type="password" id="passConfirm" class="form-input" placeholder="••••••••"></div>
          <button class="btn btn-secondary" onclick="cambiarPassword()">Actualizar Contraseña</button>
        </div>
      </div>

    </div><!-- main-content -->
  </div><!-- app-body -->
</div><!-- appPage -->

<!-- ===== MODALES ===== -->
<!-- Modal Usuario -->
<div class="modal-overlay" id="modalUsuario">
  <div class="modal">
    <div class="modal-title" id="modalUsuarioTitle">Nuevo Usuario</div>
    <div id="muError" class="error-msg" style="display:none"></div>
    <input type="hidden" id="muId">
    <div class="form-group"><label class="form-label">Nombre completo</label><input type="text" id="muNombre" class="form-input" placeholder="Nombre y apellido"></div>
    <div class="form-group"><label class="form-label">Correo electrónico</label><input type="email" id="muEmail" class="form-input" placeholder="email@correo.com"></div>
    <div class="form-row">
      <div class="form-group"><label class="form-label">Perfil</label>
        <select id="muPerfil" class="form-select">
          <option value="residente">Residente</option>
          <option value="supervisor">Supervisor</option>
          <option value="administrador">Administrador</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Apartamento</label><input type="text" id="muApto" class="form-input" placeholder="Ej: 301"></div>
    </div>
    <div class="form-group"><label class="form-label">Teléfono</label><input type="text" id="muPhone" class="form-input" placeholder="300 000 0000"></div>
    <div class="form-group" id="muPassGroup"><label class="form-label">Contraseña inicial</label><input type="password" id="muPass" class="form-input" placeholder="Mínimo 6 caracteres"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalUsuario')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarUsuario()">Guardar</button>
    </div>
  </div>
</div>

<!-- Modal Insumo -->
<div class="modal-overlay" id="modalInsumo">
  <div class="modal">
    <div class="modal-title" id="modalInsumoTitle">Nuevo Insumo</div>
    <div id="miError" class="error-msg" style="display:none"></div>
    <input type="hidden" id="miId">
    <div class="form-group"><label class="form-label">Nombre del insumo</label><input type="text" id="miNombre" class="form-input" placeholder="Ej: Sillas plásticas"></div>
    <div class="form-row">
      <div class="form-group"><label class="form-label">Cantidad disponible</label><input type="number" id="miCantidad" class="form-input" placeholder="0" min="0"></div>
      <div class="form-group"><label class="form-label">Unidad</label>
        <select id="miUnidad" class="form-select">
          <option value="unidades">Unidades</option>
          <option value="metros">Metros</option>
          <option value="juegos">Juegos</option>
          <option value="cajas">Cajas</option>
          <option value="rollos">Rollos</option>
          <option value="metros²">Metros²</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group"><label class="form-label">Stock mínimo</label><input type="number" id="miMinimo" class="form-input" placeholder="5" min="0"></div>
      <div class="form-group"><label class="form-label">Categoría</label>
        <select id="miCategoria" class="form-select">
          <option value="mobiliario">Mobiliario</option>
          <option value="vajilla">Vajilla</option>
          <option value="limpieza">Limpieza</option>
          <option value="decoracion">Decoración</option>
          <option value="audio">Audio/Visual</option>
          <option value="cocina">Cocina</option>
          <option value="otro">Otro</option>
        </select>
      </div>
    </div>
    <div class="form-group"><label class="form-label">Descripción</label><textarea id="miDesc" class="form-input" rows="2" placeholder="Estado y ubicación del insumo..."></textarea></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalInsumo')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarInsumo()">Guardar</button>
    </div>
  </div>
</div>

<!-- Modal Acción Reserva -->
<div class="modal-overlay" id="modalAccionReserva">
  <div class="modal">
    <div class="modal-title" id="modalAccionTitle">Gestionar Reserva</div>
    <input type="hidden" id="arId">
    <div id="arInfo" style="background:var(--bg3);border-radius:var(--radius-sm);padding:16px;margin-bottom:16px;font-size:13px;line-height:1.9"></div>
    <div id="arComentarioGroup" class="form-group"><label class="form-label">Comentario para el residente (opcional)</label><textarea id="arComentario" class="form-input" rows="2" placeholder="Motivo de aprobación/rechazo..."></textarea></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modalAccionReserva')">Cerrar</button>
      <div id="arAcciones" style="display:flex;gap:8px">
        <button class="btn btn-danger" onclick="accionReserva('rechazada')">❌ Rechazar</button>
        <button class="btn btn-success" onclick="accionReserva('aprobada')">✅ Aprobar</button>
      </div>
    </div>
  </div>
</div>

<script>
// ========== CONFIG ==========
const API_BASE = './api';
let currentUser = null;
let reservasLocales = [];
let usuariosLocales = [];
let inventarioLocales = [];
let charts = {};

// ========== API CALLS ==========
async function apiCall(endpoint, metodo = 'GET', datos = null) {
  try {
    const opciones = {
      method: metodo,
      headers: { 'Content-Type': 'application/json' }
    };
    
    if (datos) {
      opciones.body = JSON.stringify(datos);
    }
    
    const respuesta = await fetch(API_BASE + endpoint, opciones);
    const texto = await respuesta.text();
    let resultado;
    try {
      resultado = texto ? JSON.parse(texto) : {};
    } catch (err) {
      throw new Error('Respuesta inválida del servidor: ' + texto.slice(0, 200));
    }
    
    if (!respuesta.ok) {
      throw new Error(resultado.error || `HTTP ${respuesta.status} ${respuesta.statusText}`);
    }
    
    if (!resultado.exito && resultado.error) {
      throw new Error(resultado.error);
    }
    
    return resultado;
  } catch (error) {
    console.error('Error en API:', error);
    showNotif('Error: ' + error.message, 'error');
    throw error;
  }
}

// ========== AUTH ==========
async function doLogin() {
  const email = document.getElementById('loginEmail').value.trim();
  const pass = document.getElementById('loginPass').value.trim();
  
  try {
    const resultado = await apiCall('/auth/login', 'POST', { email, password: pass });
    if (resultado.exito) {
      currentUser = resultado.usuario;
      sessionStorage.setItem('user', JSON.stringify(currentUser));
      launchApp();
    }
  } catch (error) {
    showLoginError('Error al iniciar sesión: ' + error.message);
  }
}

async function doRegister() {
  const nombre = document.getElementById('regName').value.trim();
  const email = document.getElementById('regEmail').value.trim();
  const apto = document.getElementById('regApto').value.trim();
  const telefono = document.getElementById('regPhone').value.trim();
  const pass = document.getElementById('regPass').value.trim();
  const pass2 = document.getElementById('regPass2').value.trim();
  
  hideEl('regError');
  hideEl('regSuccess');
  
  if (!nombre || !email || !apto || !pass) {
    showEl('regError', 'Completa todos los campos obligatorios');
    return;
  }
  
  if (pass.length < 6) {
    showEl('regError', 'La contraseña debe tener mínimo 6 caracteres');
    return;
  }
  
  if (pass !== pass2) {
    showEl('regError', 'Las contraseñas no coinciden');
    return;
  }
  
  try {
    const resultado = await apiCall('/auth/register', 'POST', {
      nombre, email, apto, telefono, password: pass
    });
    
    if (resultado.exito) {
      showEl('regSuccess', '¡Cuenta creada! Ya puedes iniciar sesión.');
      setTimeout(() => showLogin(), 2000);
    }
  } catch (error) {
    showEl('regError', 'Error: ' + error.message);
  }
}

function doLogout() {
  currentUser = null;
  sessionStorage.removeItem('user');
  document.getElementById('appPage').style.display = 'none';
  document.getElementById('loginPage').style.display = 'flex';
  resetLoginForm();
}

// ========== APP LAUNCH ==========
function launchApp() {
  document.getElementById('loginPage').style.display = 'none';
  const app = document.getElementById('appPage');
  app.style.display = 'flex';
  
  const isAdmin = currentUser.perfil === 'administrador';
  const isSup = currentUser.perfil === 'supervisor';
  
  document.getElementById('topUserName').textContent = currentUser.nombre;
  document.getElementById('topUserRole').textContent = currentUser.perfil;
  
  const rb = document.getElementById('topRoleBadge');
  rb.textContent = currentUser.perfil;
  rb.className = 'role-tag role-' + currentUser.perfil;
  
  const av = document.getElementById('topAvatar');
  av.textContent = currentUser.nombre.slice(0, 2).toUpperCase();
  av.className = 'user-avatar avatar-' + (currentUser.perfil === 'administrador' ? 'admin' : currentUser.perfil === 'supervisor' ? 'supervisor' : 'residente');
  
  document.getElementById('navAdmin').style.display = (isAdmin || isSup) ? 'block' : 'none';
  document.getElementById('btnNuevoUsuario').style.display = isAdmin ? 'inline-flex' : 'none';
  
  cargarDatos();
  goPanel('dashboard');
}

// ========== CARGAR DATOS INICIALES ==========
async function cargarDatos() {
  try {
    const [reservas, usuarios, inventario] = await Promise.all([
      apiCall('/reservas', 'GET'),
      apiCall('/usuarios', 'GET'),
      apiCall('/inventario', 'GET')
    ]);
    
    reservasLocales = reservas.reservas || [];
    usuariosLocales = usuarios.usuarios || [];
    inventarioLocales = inventario.inventario || [];
    
    updatePendingBadge();
  } catch (error) {
    console.error('Error al cargar datos:', error);
    showNotif('Error al cargar datos', 'error');
  }
}

// ========== NAVIGATION ==========
function goPanel(name) {
  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('panel-' + name).classList.add('active');
  
  document.querySelectorAll('.nav-item').forEach(n => {
    if (n.textContent.toLowerCase().includes(name.toLowerCase())) {
      n.classList.add('active');
    }
  });
  
  const renders = {
    dashboard: renderDashboard,
    reservas: renderMisReservas,
    todasReservas: () => { renderFiltrosMes(); renderTodasReservas(); },
    usuarios: renderUsuarios,
    inventario: renderInventario,
    perfil: renderPerfil,
    nuevaReserva: renderNuevaReserva
  };
  
  if (renders[name]) renders[name]();
}

// ========== DASHBOARD ==========
function renderDashboard() {
  const isAdmin = currentUser.perfil === 'administrador';
  const isSup = currentUser.perfil === 'supervisor';
  
  const pendientes = reservasLocales.filter(r => r.estado === 'pendiente').length;
  const aprobadas = reservasLocales.filter(r => r.estado === 'aprobada').length;
  
  const hoy = new Date().toISOString().split('T')[0];
  const proximas = reservasLocales.filter(r => r.fecha >= hoy && r.estado === 'aprobada').length;
  
  document.getElementById('dashboardWelcome').textContent = 'Bienvenido/a, ' + currentUser.nombre;
  
  let statsHTML = '';
  if (isAdmin || isSup) {
    statsHTML += statCard('blue', '📅', reservasLocales.length, 'Total reservas') +
                 statCard('amber', '⏳', pendientes, 'Pendientes') +
                 statCard('green', '✅', aprobadas, 'Aprobadas') +
                 statCard('purple', '🔮', proximas, 'Próximas') +
                 statCard('red', '👥', usuariosLocales.filter(u => u.activo).length, 'Usuarios activos');
  } else {
    const misRes = reservasLocales.filter(r => r.userId === currentUser.id);
    statsHTML += statCard('blue', '📅', misRes.length, 'Mis reservas') +
                 statCard('amber', '⏳', misRes.filter(r => r.estado === 'pendiente').length, 'Pendientes') +
                 statCard('green', '✅', misRes.filter(r => r.estado === 'aprobada').length, 'Aprobadas');
  }
  
  document.getElementById('dashStats').innerHTML = statsHTML;
  renderDashCharts(reservasLocales);
}

function statCard(c, icon, val, label) {
  return `<div class="stat-card ${c}"><div class="stat-icon">${icon}</div><div class="stat-val">${val}</div><div class="stat-label">${label}</div></div>`;
}

function renderDashCharts(reservas) {
  destroyChart('chartMeses');
  destroyChart('chartEstados');
  
  const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  const porMes = new Array(12).fill(0);
  
  reservas.forEach(r => {
    const m = new Date(r.fecha + 'T12:00:00').getMonth();
    porMes[m]++;
  });
  
  charts.chartMeses = new Chart(document.getElementById('chartMeses'), {
    type: 'bar',
    data: {
      labels: meses,
      datasets: [{
        data: porMes,
        backgroundColor: 'rgba(79,156,249,0.7)',
        borderColor: 'rgba(79,156,249,1)',
        borderWidth: 1,
        borderRadius: 4
      }]
    },
    options: chartOpts()
  });
  
  const estados = { pendiente: 0, aprobada: 0, rechazada: 0 };
  reservas.forEach(r => estados[r.estado] = (estados[r.estado] || 0) + 1);
  
  charts.chartEstados = new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
      labels: ['Pendiente', 'Aprobada', 'Rechazada'],
      datasets: [{
        data: [estados.pendiente, estados.aprobada, estados.rechazada],
        backgroundColor: ['rgba(245,158,11,0.8)', 'rgba(16,185,129,0.8)', 'rgba(239,68,68,0.8)'],
        borderWidth: 0
      }]
    },
    options: { ...chartOpts(), cutout: '65%' }
  });
}

function chartOpts() {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { labels: { color: '#8b9ab8', font: { size: 11 } } }
    },
    scales: {
      x: { display: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#8b9ab8', font: { size: 10 } } },
      y: { display: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#8b9ab8', font: { size: 10 } } }
    }
  };
}

function destroyChart(id) {
  if (charts[id]) {
    charts[id].destroy();
    delete charts[id];
  }
}

// ========== RESERVAS ==========
function renderMisReservas() {
  const filtro = document.getElementById('filtroMisReservas').value;
  let reservas = reservasLocales.filter(r => r.userId === currentUser.id);
  
  if (filtro !== 'all') {
    reservas = reservas.filter(r => r.estado === filtro);
  }
  
  const tbody = document.getElementById('tbodyMisReservas');
  if (!reservas.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text2)">No tienes reservas aún</td></tr>';
    return;
  }
  
  tbody.innerHTML = reservas.sort((a, b) => b.fecha.localeCompare(a.fecha)).map(r => `
    <tr>
      <td>${formatDate(r.fecha)}</td>
      <td style="font-weight:500">${r.nombre}</td>
      <td>${r.asistentes}</td>
      <td>${badgeEstado(r.estado)}</td>
      <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:11px;color:var(--text2)">${(r.insumos || []).join(', ') || '—'}</td>
      <td style="font-size:12px;color:var(--text2)">${r.comentario || r.obs || '—'}</td>
    </tr>`).join('');
}

function renderNuevaReserva() {
  const insumos = inventarioLocales;
  document.getElementById('insumosCheck').innerHTML = insumos.filter(i => i.activo).map(i => `
    <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;background:var(--bg3);padding:8px 12px;border-radius:6px;border:1px solid var(--border)">
      <input type="checkbox" value="${i.nombre}" style="accent-color:var(--accent)"> ${i.nombre} <span style="font-size:11px;color:var(--text3)">(${i.cantidad} ${i.unidad})</span>
    </label>`).join('');
  
  const today = new Date();
  const min = new Date(today);
  min.setDate(min.getDate() + 2);
  min.setHours(0, 0, 0, 0);
  
  const max = new Date(today);
  max.setDate(max.getDate() + 90);
  
  document.getElementById('resDate').min = min.toISOString().split('T')[0];
  document.getElementById('resDate').max = max.toISOString().split('T')[0];
}

function checkDisponibilidad() {
  const fecha = document.getElementById('resDate').value;
  if (!fecha) {
    document.getElementById('dispStatus').innerHTML = '<span style="color:var(--text2);font-size:12px">📌 Solo puedes reservar entre <b>48 horas</b> y <b>90 días</b> a partir de hoy.</span>';
    return;
  }
  
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const eventDate = new Date(fecha + 'T12:00:00');
  const diff = Math.round((eventDate - today) / (1000 * 60 * 60 * 24));
  
  if (diff < 2) {
    document.getElementById('dispStatus').innerHTML = '<span style="color:#fca5a5">⛔ Reserva no permitida: se requieren mínimo <b>48 horas</b> de anticipación.</span>';
    return;
  }
  
  if (diff > 90) {
    document.getElementById('dispStatus').innerHTML = '<span style="color:#fca5a5">⛔ Reserva no permitida: no se puede reservar con más de <b>90 días</b> de anticipación.</span>';
    return;
  }
  
  const conflicto = reservasLocales.find(r => r.fecha === fecha && r.estado !== 'rechazada');
  if (conflicto) {
    document.getElementById('dispStatus').innerHTML = '<span style="color:#fca5a5">❌ Esta fecha ya está reservada. Selecciona otra fecha.</span>';
  } else {
    document.getElementById('dispStatus').innerHTML = `<span style="color:#6ee7b7">✅ Fecha disponible (en ${diff} día${diff !== 1 ? 's' : ''}) — Horario: mediodía a medianoche</span>`;
  }
}

async function crearReserva() {
  const fecha = document.getElementById('resDate').value;
  const nombre = document.getElementById('resNombre').value.trim();
  const asistentes = parseInt(document.getElementById('resAsistentes').value) || 0;
  const desc = document.getElementById('resDesc').value.trim();
  const obs = document.getElementById('resObs').value.trim();
  const insumos = [...document.querySelectorAll('#insumosCheck input:checked')].map(c => c.value);
  
  hideEl('reservaError');
  
  if (!fecha) {
    showEl('reservaError', 'Selecciona la fecha del evento');
    return;
  }
  
  if (!nombre) {
    showEl('reservaError', 'Ingresa el nombre del evento');
    return;
  }
  
  if (asistentes < 1) {
    showEl('reservaError', 'Indica el número de asistentes');
    return;
  }
  
  try {
    const resultado = await apiCall('/reservas', 'POST', {
      userId: currentUser.id,
      creadoPor: currentUser.id,
      fecha,
      nombre,
      asistentes,
      descripcion: desc,
      insumos,
      obs
    });
    
    if (resultado.exito) {
      showNotif('✅ Reserva solicitada con éxito. Pendiente de aprobación.', 'success');
      document.getElementById('resDate').value = '';
      document.getElementById('resNombre').value = '';
      document.getElementById('resAsistentes').value = '';
      document.getElementById('resDesc').value = '';
      document.getElementById('resObs').value = '';
      document.querySelectorAll('#insumosCheck input').forEach(c => c.checked = false);
      
      await cargarDatos();
      goPanel('reservas');
    }
  } catch (error) {
    showEl('reservaError', 'Error: ' + error.message);
  }
}

// ========== TODAS LAS RESERVAS ==========
function renderFiltrosMes() {
  const meses = new Set(reservasLocales.map(r => r.fecha.slice(0, 7)));
  const sel = document.getElementById('filtroMes');
  const meses2 = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  sel.innerHTML = '<option value="all">Todo el período</option>' + [...meses].sort().reverse().map(m => {
    const [y, mo] = m.split('-');
    return `<option value="${m}">${meses2[parseInt(mo) - 1]} ${y}</option>`;
  }).join('');
}

function renderTodasReservas() {
  const filtroE = document.getElementById('filtroEstado').value;
  const filtroM = document.getElementById('filtroMes').value;
  
  let reservas = reservasLocales.filter(r => true);
  if (filtroE !== 'all') reservas = reservas.filter(r => r.estado === filtroE);
  if (filtroM !== 'all') reservas = reservas.filter(r => r.fecha.startsWith(filtroM));
  
  const tbody = document.getElementById('tbodyTodasReservas');
  if (!reservas.length) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text2)">Sin reservas</td></tr>';
    return;
  }
  
  const isAdmin = currentUser.perfil === 'administrador';
  const isSup = currentUser.perfil === 'supervisor';
  
  tbody.innerHTML = reservas.sort((a, b) => b.fecha.localeCompare(a.fecha)).map(r => {
    const u = usuariosLocales.find(u => u.id === r.userId) || { nombre: 'Desconocido', apto: '—' };
    const canAct = (isAdmin || isSup) && r.estado === 'pendiente';
    const canView = (isAdmin || isSup);
    
    let accion = '—';
    if (canAct) {
      accion = `<button class="btn btn-primary btn-sm" onclick="openAccionReserva('${r.id}')">⚖️ Gestionar</button>`;
    } else if (canView) {
      accion = `<button class="btn btn-secondary btn-sm" onclick="openAccionReserva('${r.id}')">👁️ Ver detalle</button>`;
    }
    
    return `<tr>
      <td>${formatDate(r.fecha)}</td>
      <td>${u.nombre}</td>
      <td>${u.apto}</td>
      <td style="font-weight:500">${r.nombre}</td>
      <td>${r.asistentes}</td>
      <td>${badgeEstado(r.estado)}</td>
      <td>${accion}</td>
    </tr>`;
  }).join('');
}

function openAccionReserva(id) {
  const r = reservasLocales.find(x => x.id === id);
  if (!r) return;
  
  const u = usuariosLocales.find(u => u.id === r.userId) || { nombre: '—', apto: '—' };
  
  document.getElementById('arId').value = id;
  document.getElementById('arComentario').value = r.comentario || '';
  
  const hoy = new Date();
  hoy.setHours(0, 0, 0, 0);
  const eventDate = new Date(r.fecha + 'T12:00:00');
  const diff = Math.round((eventDate - hoy) / (1000 * 60 * 60 * 24));
  const diffTxt = diff >= 0 ? `en ${diff} día${diff !== 1 ? 's' : ''}` : diff === -1 ? 'fue ayer' : `hace ${Math.abs(diff)} días`;
  
  document.getElementById('arInfo').innerHTML = `
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">${badgeEstado(r.estado)}<span style="font-size:12px;color:var(--text2)">${diffTxt}</span></div>
    <b>Residente:</b> ${u.nombre} (Apto ${u.apto})<br>
    <b>Evento:</b> ${r.nombre}<br>
    <b>Fecha:</b> ${formatDate(r.fecha)}<br>
    <b>Asistentes:</b> ${r.asistentes}<br>
    <b>Insumos:</b> ${(r.insumos || []).join(', ') || 'Ninguno'}<br>
    <b>Descripción:</b> ${r.descripcion || '—'}<br>
    ${r.obs ? `<b>Observaciones:</b> ${r.obs}<br>` : ''}
    ${r.comentario ? `<b>Comentario admin:</b> <span style="color:var(--accent4)">${r.comentario}</span>` : ''}`;
  
  const isPending = r.estado === 'pendiente';
  document.getElementById('arAcciones').style.display = isPending ? 'flex' : 'none';
  document.getElementById('arComentarioGroup').style.display = isPending ? 'block' : 'none';
  document.getElementById('modalAccionTitle').textContent = isPending ? 'Gestionar Reserva' : 'Detalle de Reserva';
  
  openModal('modalAccionReserva');
}

async function accionReserva(estado) {
  const id = document.getElementById('arId').value;
  const comentario = document.getElementById('arComentario').value.trim();
  
  try {
    const resultado = await apiCall(`/reservas/${id}`, 'PUT', { estado, comentario });
    
    if (resultado.exito) {
      closeModal('modalAccionReserva');
      await cargarDatos();
      renderTodasReservas();
      showNotif(estado === 'aprobada' ? '✅ Reserva aprobada' : '❌ Reserva rechazada', estado === 'aprobada' ? 'success' : 'error');
    }
  } catch (error) {
    showNotif('Error: ' + error.message, 'error');
  }
}

// ========== USUARIOS ==========
function renderUsuarios() {
  const isAdmin = currentUser.perfil === 'administrador';
  const tbody = document.getElementById('tbodyUsuarios');
  
  tbody.innerHTML = usuariosLocales.map(u => `<tr>
    <td style="font-weight:500">${u.nombre}</td>
    <td style="font-size:12px">${u.email}</td>
    <td><span class="role-tag role-${u.perfil}">${u.perfil}</span></td>
    <td>${u.apto}</td>
    <td style="font-size:12px">${u.telefono || '—'}</td>
    <td>${u.activo ? '<span class="badge badge-active">Activo</span>' : '<span class="badge badge-inactive">Inactivo</span>'}</td>
    <td>${isAdmin && u.id !== currentUser.id ? `<div class="flex gap-8">
      <button class="btn btn-secondary btn-sm" onclick="editUsuario('${u.id}')">Editar</button>
      <button class="btn btn-${u.activo ? 'warn' : 'success'} btn-sm" onclick="toggleUsuario('${u.id}')">${u.activo ? 'Desactivar' : 'Activar'}</button>
      <button class="btn btn-danger btn-sm" onclick="eliminarUsuario('${u.id}')">Eliminar</button>
    </div>` : '—'}</td>
  </tr>`).join('');
}

async function guardarUsuario() {
  const id = document.getElementById('muId').value;
  const nombre = document.getElementById('muNombre').value.trim();
  const email = document.getElementById('muEmail').value.trim();
  const perfil = document.getElementById('muPerfil').value;
  const apto = document.getElementById('muApto').value.trim();
  const phone = document.getElementById('muPhone').value.trim();
  const pass = document.getElementById('muPass').value;
  
  hideEl('muError');
  
  if (!nombre || !email) {
    showEl('muError', 'Nombre y email son obligatorios');
    return;
  }
  
  try {
    if (!id) {
      if (!pass || pass.length < 6) {
        showEl('muError', 'Contraseña de al menos 6 caracteres');
        return;
      }
      
      const resultado = await apiCall('/usuarios', 'POST', {
        nombre, email, perfil, apto, telefono: phone, password: pass
      });
      
      if (resultado.exito) {
        closeModal('modalUsuario');
        await cargarDatos();
        renderUsuarios();
        showNotif('Usuario creado exitosamente', 'success');
      }
    } else {
      const resultado = await apiCall(`/usuarios/${id}`, 'PUT', {
        nombre, email, perfil, apto, telefono: phone, activo: true
      });
      
      if (resultado.exito) {
        closeModal('modalUsuario');
        await cargarDatos();
        renderUsuarios();
        showNotif('Usuario actualizado', 'success');
      }
    }
  } catch (error) {
    showEl('muError', 'Error: ' + error.message);
  }
}

function editUsuario(id) {
  const u = usuariosLocales.find(u => u.id === id);
  if (!u) return;
  
  document.getElementById('muId').value = id;
  document.getElementById('muNombre').value = u.nombre;
  document.getElementById('muEmail').value = u.email;
  document.getElementById('muPerfil').value = u.perfil;
  document.getElementById('muApto').value = u.apto;
  document.getElementById('muPhone').value = u.telefono || '';
  document.getElementById('muPass').value = '';
  document.getElementById('modalUsuarioTitle').textContent = 'Editar Usuario';
  document.getElementById('muPassGroup').querySelector('label').textContent = 'Nueva contraseña (opcional)';
  openModal('modalUsuario');
}

async function toggleUsuario(id) {
  const u = usuariosLocales.find(u => u.id === id);
  if (!u) return;
  
  try {
    const resultado = await apiCall(`/usuarios/${id}`, 'PUT', {
      nombre: u.nombre,
      email: u.email,
      perfil: u.perfil,
      apto: u.apto,
      telefono: u.telefono,
      activo: !u.activo
    });
    
    if (resultado.exito) {
      await cargarDatos();
      renderUsuarios();
      showNotif(u.activo ? 'Usuario desactivado' : 'Usuario activado', 'success');
    }
  } catch (error) {
    showNotif('Error: ' + error.message, 'error');
  }
}

async function eliminarUsuario(id) {
  if (!confirm('¿Eliminar este usuario?')) return;
  
  try {
    const u = usuariosLocales.find(u => u.id === id);
    if (u) {
      const resultado = await apiCall(`/usuarios/${id}`, 'PUT', {
        nombre: u.nombre,
        email: u.email,
        perfil: u.perfil,
        apto: u.apto,
        telefono: u.telefono,
        activo: false
      });
      
      if (resultado.exito) {
        await cargarDatos();
        renderUsuarios();
        showNotif('Usuario eliminado', 'success');
      }
    }
  } catch (error) {
    showNotif('Error: ' + error.message, 'error');
  }
}

// ========== INVENTARIO ==========
function renderInventario() {
  const isAdmin = currentUser.perfil === 'administrador';
  const isSup = currentUser.perfil === 'supervisor';
  
  document.getElementById('btnNuevoInsumo').style.display = (isAdmin || isSup) ? 'inline-flex' : 'none';
  
  document.getElementById('inventarioGrid').innerHTML = inventarioLocales.filter(i => i.activo).map(i => {
    const low = i.cantidad <= i.minimo;
    return `<div class="inv-card">
      <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px">
        <span style="font-size:10px;color:var(--text3);background:var(--bg4);padding:2px 8px;border-radius:4px;text-transform:uppercase">${i.categoria}</span>
        ${(isAdmin || isSup) ? `<div style="display:flex;gap:4px"><button class="btn btn-secondary btn-sm" onclick="editInsumo('${i.id}')" title="Editar">✏️</button><button class="btn btn-danger btn-sm" onclick="eliminarInsumo('${i.id}')" title="Eliminar">🗑️</button></div>` : ''}
      </div>
      <div class="inv-name">${i.nombre}</div>
      <div style="display:flex;align-items:center;gap:8px;margin:6px 0">
        <span class="inv-qty">${i.cantidad}</span><span class="inv-unit">${i.unidad}</span>
      </div>
      <div class="inv-status ${low ? 'inv-low' : 'inv-ok'}">${low ? '⚠️ Stock bajo' : '✅ Stock OK'} (mín: ${i.minimo})</div>
      <div style="font-size:11px;color:var(--text3);margin-top:6px">${i.descripcion || ''}</div>
    </div>`;
  }).join('');
}

async function guardarInsumo() {
  const id = document.getElementById('miId').value;
  const nombre = document.getElementById('miNombre').value.trim();
  const cantidad = parseInt(document.getElementById('miCantidad').value) || 0;
  const unidad = document.getElementById('miUnidad').value;
  const minimo = parseInt(document.getElementById('miMinimo').value) || 0;
  const categoria = document.getElementById('miCategoria').value;
  const desc = document.getElementById('miDesc').value.trim();
  
  hideEl('miError');
  
  if (!nombre) {
    showEl('miError', 'Nombre del insumo es obligatorio');
    return;
  }
  
  try {
    if (!id) {
      const resultado = await apiCall('/inventario', 'POST', {
        nombre, cantidad, unidad, minimo, categoria, descripcion: desc
      });
      
      if (resultado.exito) {
        closeModal('modalInsumo');
        await cargarDatos();
        renderInventario();
        showNotif('Insumo creado', 'success');
      }
    } else {
      const resultado = await apiCall(`/inventario/${id}`, 'PUT', {
        nombre, cantidad, unidad, minimo, categoria, descripcion: desc
      });
      
      if (resultado.exito) {
        closeModal('modalInsumo');
        await cargarDatos();
        renderInventario();
        showNotif('Insumo actualizado', 'success');
      }
    }
  } catch (error) {
    showEl('miError', 'Error: ' + error.message);
  }
}

function editInsumo(id) {
  const i = inventarioLocales.find(i => i.id === id);
  if (!i) return;
  
  document.getElementById('miId').value = id;
  document.getElementById('miNombre').value = i.nombre;
  document.getElementById('miCantidad').value = i.cantidad;
  document.getElementById('miUnidad').value = i.unidad;
  document.getElementById('miMinimo').value = i.minimo;
  document.getElementById('miCategoria').value = i.categoria;
  document.getElementById('miDesc').value = i.descripcion || '';
  document.getElementById('modalInsumoTitle').textContent = 'Editar Insumo';
  openModal('modalInsumo');
}

async function eliminarInsumo(id) {
  const i = inventarioLocales.find(i => i.id === id);
  if (!i) return;
  
  if (!confirm(`¿Eliminar el insumo "${i.nombre}"?`)) return;
  
  try {
    const resultado = await apiCall(`/inventario/${id}`, 'PUT', {
      nombre: i.nombre,
      cantidad: i.cantidad,
      unidad: i.unidad,
      minimo: i.minimo,
      categoria: i.categoria,
      descripcion: i.descripcion,
      activo: false
    });
    
    if (resultado.exito) {
      await cargarDatos();
      renderInventario();
      showNotif('Insumo eliminado', 'success');
    }
  } catch (error) {
    showNotif('Error: ' + error.message, 'error');
  }
}

// ========== PERFIL ==========
function renderPerfil() {
  const u = currentUser;
  const initials = u.nombre.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
  
  document.getElementById('perfilAvatar').textContent = initials;
  document.getElementById('perfilAvatar').className = 'profile-avatar avatar-' + (u.perfil === 'administrador' ? 'admin' : u.perfil === 'supervisor' ? 'supervisor' : 'residente');
  document.getElementById('perfilNombre').textContent = u.nombre;
  document.getElementById('perfilEmail').textContent = u.email;
  document.getElementById('perfilRoleBadge').innerHTML = `<span class="role-tag role-${u.perfil}">${u.perfil}</span>${u.apto ? ' — Apto ' + u.apto : ''}`;
}

async function cambiarPassword() {
  const actual = document.getElementById('passActual').value;
  const nueva = document.getElementById('passNueva').value;
  const confirm = document.getElementById('passConfirm').value;
  
  hideEl('passError');
  hideEl('passSuccess');
  
  if (nueva.length < 6) {
    showEl('passError', 'La nueva contraseña debe tener al menos 6 caracteres');
    return;
  }
  
  if (nueva !== confirm) {
    showEl('passError', 'Las contraseñas no coinciden');
    return;
  }
  
  try {
    const resultado = await apiCall('/cambiar-password', 'POST', {
      userId: currentUser.id,
      passwordActual: actual,
      passwordNueva: nueva
    });
    
    if (resultado.exito) {
      showEl('passSuccess', 'Contraseña actualizada correctamente');
      document.getElementById('passActual').value = '';
      document.getElementById('passNueva').value = '';
      document.getElementById('passConfirm').value = '';
    }
  } catch (error) {
    showEl('passError', 'Error: ' + error.message);
  }
}

// ========== UTILS ==========
function updatePendingBadge() {
  const p = reservasLocales.filter(r => r.estado === 'pendiente').length;
  const badge = document.getElementById('pendingBadge');
  badge.style.display = p > 0 ? 'inline' : 'none';
  badge.textContent = p;
}

function badgeEstado(e) {
  const map = { pendiente: 'badge-pending', aprobada: 'badge-approved', rechazada: 'badge-rejected' };
  const icons = { pendiente: '⏳', aprobada: '✅', rechazada: '❌' };
  return `<span class="badge ${map[e] || 'badge-inactive'}">${icons[e] || ''} ${e}</span>`;
}

function formatDate(d) {
  if (!d) return '—';
  const [y, m, day] = d.split('-');
  const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  return `${parseInt(day)} ${meses[parseInt(m) - 1]} ${y}`;
}

function openModal(id) {
  if (id === 'modalUsuario') {
    document.getElementById('muId').value = '';
    document.getElementById('muNombre').value = '';
    document.getElementById('muEmail').value = '';
    document.getElementById('muPerfil').value = 'residente';
    document.getElementById('muApto').value = '';
    document.getElementById('muPhone').value = '';
    document.getElementById('muPass').value = '';
    document.getElementById('modalUsuarioTitle').textContent = 'Nuevo Usuario';
    document.getElementById('muPassGroup').querySelector('label').textContent = 'Contraseña inicial';
    hideEl('muError');
  }
  
  if (id === 'modalInsumo') {
    document.getElementById('miId').value = '';
    document.getElementById('miNombre').value = '';
    document.getElementById('miCantidad').value = '';
    document.getElementById('miMinimo').value = '5';
    document.getElementById('miDesc').value = '';
    document.getElementById('modalInsumoTitle').textContent = 'Nuevo Insumo';
    hideEl('miError');
  }
  
  document.getElementById(id).classList.add('open');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

function showEl(id, msg) {
  const el = document.getElementById(id);
  el.textContent = msg;
  el.style.display = 'block';
}

function hideEl(id) {
  document.getElementById(id).style.display = 'none';
}

function showNotif(msg, type = 'success') {
  const n = document.createElement('div');
  n.className = 'notif ' + type;
  n.textContent = msg;
  document.body.appendChild(n);
  setTimeout(() => n.remove(), 3500);
}

function showLoginError(msg) {
  showEl('loginError', msg);
}

function showRegister() {
  document.getElementById('loginView').style.display = 'none';
  document.getElementById('registerView').style.display = 'block';
}

function showLogin() {
  document.getElementById('loginView').style.display = 'block';
  document.getElementById('registerView').style.display = 'none';
}

function resetLoginForm() {
  document.getElementById('loginEmail').value = '';
  document.getElementById('loginPass').value = '';
  hideEl('loginError');
}

// Enter key support
document.getElementById('loginPass').addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });
document.getElementById('loginEmail').addEventListener('keydown', e => { if (e.key === 'Enter') doLogin(); });

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', e => {
  if (e.target === o) o.classList.remove('open');
}));

// Auto-login if session exists
const userData = sessionStorage.getItem('user');
if (userData) {
  try {
    currentUser = JSON.parse(userData);
    launchApp();
  } catch (error) {
    console.error('Error al restaurar sesión:', error);
    sessionStorage.removeItem('user');
  }
}
</script>
</body>
</html>
