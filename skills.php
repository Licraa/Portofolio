<?php
include 'admin/db.php';
$skills = [];
$skillResult = $conn->query("SELECT * FROM skills ORDER BY id DESC");
if ($skillResult && $skillResult->num_rows > 0) {
    while ($row = $skillResult->fetch_assoc()) {
        $skills[] = $row;
    }
}
?>
<!-- Skills Section -->
<section id="skills" class="py-20 px-4  relative overflow-hidden">
    <div class="max-w-7xl mx-auto ">
        <!-- Title -->
        <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text text-warm-wood">
            Technical Skills
        </h2>
        <!-- Skills Carousel Infinite Scroll -->
        <div class="relative overflow-hidden">
            <div class="flex skills-carousel">
                <?php for ($loop = 0; $loop < 2; $loop++): // duplikat untuk efek infinite 
                ?>
                    <?php foreach ($skills as $skill): ?>
                        <?php
                        // Pastikan path_icon selalu mengarah ke folder images/
                        $iconPath = $skill['path_icon'];
                        if (!empty($iconPath) && strpos($iconPath, 'images/') !== 0) {
                            $iconPath = 'images/' . ltrim($iconPath, '/');
                        }
                        ?>
                        <div class="glass rounded-2xl p-6 min-w-[200px] text-center group hover:scale-105 transition-transform duration-300 animate-scroll">
                            <?php if (!empty($iconPath)): ?>
                                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <img src="<?php echo htmlspecialchars($iconPath); ?>" alt="<?php echo htmlspecialchars($skill['name']); ?>" class="w-12 h-12 object-contain" />

                                </div>
                            <?php endif; ?>
                            <h3 class="text-xl font-bold mb-2 text-warm-wood"><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                                <div class="bg-warm-wood h-2 rounded-full" style="width: <?php echo intval($skill['level']); ?>%"></div>
                            </div>
                            <span class="text-sm text-gray-400"><?php echo intval($skill['level']); ?>% Proficiency</span>
                            <div class="text-xs text-gray-400 mt-2"><?php echo htmlspecialchars($skill['category']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
                <?php if (count($skills) === 0): ?>
                    <div class="text-gray-400 text-center w-full">Belum ada data keahlian.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- CSS for Infinite Scroll -->
<style>
    .skills-carousel {
        display: flex;
        gap: 2rem;
        animation: scroll 30s linear infinite;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-scroll {
        flex-shrink: 0;
    }


    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>