<?php
/**
 * ARC NEBU-PEN  |  Admin: Subscription Management
 * ─────────────────────────────────────────────────────────────
 * Accessed via:  index.php?page=admin/subscriptions
 * FR-01: RBAC — admin only (enforced by controller)
 * ─────────────────────────────────────────────────────────────
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// $subscriptions is injected by SubscriptionController::adminIndex()
$subscriptions = $subscriptions ?? [];
$csrf_token    = $csrf_token    ?? ($_SESSION['csrf_token'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subscriptions — ARC Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        teal:  { 400: '#4DD9A8', 500: '#2ECB80', 600: '#25B870' },
                        ocean: { 950: '#040D0F', 900: '#071419', 800: '#0C2028', 700: '#112B35', 600: '#163644' },
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .page-bg { background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%); }
        .glass-panel { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(12px); }
        .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .sub-table th { padding: 0.75rem 1rem; color: #6b7280; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .sub-table td { padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
        .sub-table tr:last-child td { border-bottom: none; }
        .sub-table tr:hover td { background: rgba(255,255,255,0.02); }
    </style>
</head>
<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 pt-32 pb-16 max-w-7xl mx-auto w-full space-y-8">

        <!-- Header -->
        <div class="fade-in-up">
            <a href="index.php?page=admin/dashboard" class="text-xs text-gray-500 hover:text-teal-400 transition-colors flex items-center gap-1 mb-3 w-fit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Admin Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white">Manage Subscriptions</h1>
            <p class="text-gray-400 mt-1">
                <?= count($subscriptions) ?> Core Club member<?= count($subscriptions) !== 1 ? 's' : '' ?> total
            </p>
        </div>

        <!-- Flash messages -->
        <?php if ($flashSuccess): ?>
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-300 text-sm fade-in-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <?= htmlspecialchars($flashSuccess) ?>
        </div>
        <?php endif; ?>
        <?php if ($flashError): ?>
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm fade-in-up">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <?= htmlspecialchars($flashError) ?>
        </div>
        <?php endif; ?>

        <!-- Subscriptions Table -->
        <div class="glass-panel rounded-3xl overflow-hidden fade-in-up" style="animation-delay:0.1s;">
            <?php if (empty($subscriptions)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-16 h-16 rounded-2xl bg-indigo-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No subscriptions yet</h3>
                <p class="text-gray-400 text-sm">Customers who join Core Club will appear here.</p>
            </div>
            <?php else: ?>
            <table class="sub-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Member</th>
                        <th class="text-left">Plan</th>
                        <th class="text-left">Status</th>
                        <th class="text-left">Next Delivery</th>
                        <th class="text-left">Discount</th>
                        <th class="text-right pr-6">Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                    <?php
                        $statusMap = [
                            'active'    => ['bg-teal-500/20 text-teal-400',   'Active'],
                            'paused'    => ['bg-yellow-500/20 text-yellow-400','Paused'],
                            'cancelled' => ['bg-red-500/20 text-red-400',      'Cancelled'],
                        ];
                        [$statusClass, $statusLabel] = $statusMap[$sub['status']] ?? ['bg-gray-500/20 text-gray-400', ucfirst($sub['status'])];
                        $planLabel = $sub['plan'] === 'bimonthly' ? 'Every 2 Months' : 'Monthly';
                    ?>
                    <tr>
                        <td>
                            <p class="font-semibold text-white text-sm"><?= htmlspecialchars($sub['fullname']) ?></p>
                            <p class="text-gray-500 text-xs"><?= htmlspecialchars($sub['email']) ?></p>
                        </td>
                        <td class="text-gray-300 text-sm"><?= $planLabel ?></td>
                        <td>
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="text-gray-400 text-sm font-mono">
                            <?= htmlspecialchars($sub['next_delivery_date'] ?? '—') ?>
                        </td>
                        <td class="text-teal-400 text-sm font-bold"><?= (int)($sub['discount'] ?? 20) ?>%</td>
                        <td class="text-right pr-6">
                            <form action="index.php?page=admin/subscriptions/update" method="POST"
                                  class="inline-flex items-center gap-2 m-0">
                                <input type="hidden" name="csrf_token"       value="<?= htmlspecialchars($csrf_token) ?>">
                                <input type="hidden" name="subscription_id"  value="<?= (int)$sub['subscription_id'] ?>">
                                <select name="status"
                                        class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-gray-300 focus:outline-none focus:border-teal-500">
                                    <option value="active"    <?= $sub['status'] === 'active'    ? 'selected' : '' ?>>Active</option>
                                    <option value="paused"    <?= $sub['status'] === 'paused'    ? 'selected' : '' ?>>Paused</option>
                                    <option value="cancelled" <?= $sub['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 text-xs font-semibold text-gray-300 hover:text-white transition-all">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </main>
