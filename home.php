<?php
declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 1800,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_logged_in'])) {
    header('Location: pages/auth/Login/sesion.html');
    exit;
}

$nombreUsuario = htmlspecialchars((string) ($_SESSION['usuario_nombre'] ?? 'Usuario'), ENT_QUOTES, 'UTF-8');
$apellidoUsuario = htmlspecialchars((string) ($_SESSION['usuario_apellido'] ?? ''), ENT_QUOTES, 'UTF-8');
$emailUsuario = htmlspecialchars((string) ($_SESSION['usuario_email'] ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNet | Mi panel</title>
    <style>
        :root {
            --bg: #0b1020;
            --bg-soft: #121b2d;
            --panel: rgba(15, 23, 42, 0.88);
            --panel-strong: rgba(17, 24, 39, 0.96);
            --primary: #f97316;
            --primary-dark: #ea580c;
            --secondary: #fb923c;
            --text: #e5edf7;
            --muted: #94a3b8;
            --border: rgba(148, 163, 184, 0.14);
            --success: #34d399;
            --warning: #fbbf24;
            --shadow: 0 20px 48px rgba(15, 23, 42, 0.38);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(249, 115, 22, 0.18), transparent 24%),
                linear-gradient(135deg, #0b1120 0%, #111827 45%, #0f172a 100%);
            color: var(--text);
        }

        .dashboard-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: rgba(9, 15, 25, 0.94);
            border-right: 1px solid var(--border);
            padding: 28px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--primary), #ff9a55);
            color: white;
            font-size: 1rem;
            font-weight: bold;
            box-shadow: 0 12px 24px rgba(249, 115, 22, 0.35);
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 12px;
            background: rgba(255,255,255,0.02);
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-item:hover,
        .nav-item.active {
            background: linear-gradient(135deg, rgba(249,115,22,0.18), rgba(249,115,22,0.08));
            border-color: rgba(249,115,22,0.3);
        }

        .content {
            flex: 1;
            padding: 28px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
        }

        .user-box {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .user-box small {
            color: var(--muted);
        }

        .user-box strong {
            font-size: clamp(1.3rem, 2vw, 1.8rem);
            color: white;
        }

        .logout-btn {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            border-color: rgba(249,115,22,0.4);
            color: #fff;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: linear-gradient(180deg, rgba(17,24,39,0.96), rgba(15,23,42,0.92));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 22px 20px;
            box-shadow: var(--shadow);
        }

        .stat-card span {
            display: block;
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .stat-card strong {
            font-size: clamp(1.7rem, 2vw, 2.4rem);
            color: white;
            font-weight: 800;
        }

        .stat-card.orange strong { color: #ffb17a; }
        .stat-card.green strong { color: #7ee6a6; }
        .stat-card.yellow strong { color: #f9d976; }

        .grid {
            display: grid;
            grid-template-columns: 1.3fr 1fr;
            gap: 24px;
        }

        .panel {
            background: linear-gradient(180deg, rgba(17,24,39,0.96), rgba(15,23,42,0.9));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .panel h3 {
            margin: 0 0 18px;
            font-size: 1.2rem;
            color: white;
        }

        .contract-list,
        .pay-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .contract-item,
        .pay-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.04);
        }

        .contract-item strong,
        .pay-item strong {
            display: block;
            margin-bottom: 4px;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            background: rgba(52, 211, 153, 0.12);
            color: #7ee6a6;
        }

        .balance-box {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .amount {
            font-size: clamp(2rem, 2.6vw, 3rem);
            font-weight: 800;
            color: white;
        }

        .small-text {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .pay-item button {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .pay-item button:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 900px) {
            .dashboard-shell {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .nav {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }

            .stats,
            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .content {
                padding: 18px 14px 26px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .contract-item,
            .pay-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .pay-item button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">F</div>
                <span>FastNet</span>
            </div>

            <nav class="nav">
                <a href="#" class="nav-item active">🏠 Inicio</a>
                <a href="#" class="nav-item">📄 Contratos</a>
                <a href="#" class="nav-item">💰 Saldo</a>
                <a href="#" class="nav-item">💳 Pagos</a>
                <a href="#" class="nav-item">⚙️ Soporte</a>
            </nav>
        </aside>

        <main class="content">
            <header class="topbar">
                <div class="user-box">
                    <small>Bienvenido</small>
                    <strong><?= htmlspecialchars($nombreUsuario . ' ' . $apellidoUsuario) ?></strong>
                </div>
                <a href="logout.php" class="logout-btn">Cerrar sesión</a>
            </header>

            <section class="stats">
                <div class="stat-card orange">
                    <span>Contratos activos</span>
                    <strong>02</strong>
                </div>
                <div class="stat-card green">
                    <span>Saldo disponible</span>
                    <strong>$250.00</strong>
                </div>
                <div class="stat-card yellow">
                    <span>Pagos pendientes</span>
                    <strong>01</strong>
                </div>
            </section>

            <section class="grid">
                <div class="panel">
                    <h3>Mis contratos</h3>
                    <div class="contract-list">
                        <div class="contract-item">
                            <div>
                                <strong>Internet Hogar 300 Mbps</strong>
                                <span class="small-text">Contrato activo desde 02/05/2026</span>
                            </div>
                            <span class="status">Activo</span>
                        </div>

                        <div class="contract-item">
                            <div>
                                <strong>FastNet TV GO</strong>
                                <span class="small-text">Servicio adicional vigente</span>
                            </div>
                            <span class="status">Activo</span>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <h3>Saldo</h3>
                    <div class="balance-box">
                        <div class="amount">$250.00</div>
                        <span class="small-text">Último pago: 12/09/2026</span>
                        <span class="small-text">Próximo cobro: 15/10/2026</span>
                    </div>
                </div>
            </section>

            <section class="panel" style="margin-top: 24px;">
                <h3>Pagar cuentas</h3>
                <div class="pay-list">
                    <div class="pay-item">
                        <div>
                            <strong>Internet Hogar</strong>
                            <div class="small-text">Factura del mes</div>
                        </div>
                        <button>Pagar $120.00</button>
                    </div>

                    <div class="pay-item">
                        <div>
                            <strong>FastNet TV GO</strong>
                            <div class="small-text">Servicio adicional</div>
                        </div>
                        <button>Pagar $30.00</button>
                    </div>

                    <div class="pay-item">
                        <div>
                            <strong>Router Wi‑Fi 6</strong>
                            <div class="small-text">Cuota de equipo</div>
                        </div>
                        <button>Pagar $100.00</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
