<?php
/**
 * ARC NEBU-PEN  |  Admin/Manager Dashboard
 * ─────────────────────────────────────────────────────────────
 * Accessible by Admin and Manager roles.
 * ─────────────────────────────────────────────────────────────
 */
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Internal Dashboard — ARC NEBU-PEN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .page-bg { background: linear-gradient(135deg, #040D0F 0%, #071419 30%, #0C2028 60%, #163644 100%); }
    </style>
</head>
<body class="page-bg min-h-screen text-white antialiased flex flex-col">

    <?php require __DIR__ . '/../../includes/navbar.php'; ?>

    <main class="flex-1 px-6 lg:px-16 py-24 max-w-7xl mx-auto w-full mt-10">
        <h1 class="text-3xl font-bold mb-8">Internal Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-2">Total Products</h3>
                <p class="text-4xl font-bold text-white"><?= htmlspecialchars((string)($total_products ?? 0)) ?></p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-2">System Status</h3>
                <p class="text-teal-400 font-bold text-xl mt-2">Operational</p>
            </div>
        </div>
    </main>

</body>
</html>
