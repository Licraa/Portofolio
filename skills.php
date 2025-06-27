<?php
include 'admin/db.php';
$skills = [];
$skillResult = $conn->query("SELECT * FROM skills ORDER BY id DESC");
if ($skillResult && $skillResult->num_rows > 0) {
    while ($row = $skillResult->fetch_assoc()) {
        // Gunakan kolom 'logo' jika ada, fallback ke 'path_icon' jika tidak ada
        $row['icon'] = '';
        if (!empty($row['logo'])) {
            $row['icon'] = 'images/' . ltrim($row['logo'], '/');
        } elseif (!empty($row['path_icon'])) {
            $iconPath = $row['path_icon'];
            if (strpos($iconPath, 'images/') !== 0) {
                $iconPath = 'images/' . ltrim($iconPath, '/');
            }
            $row['icon'] = $iconPath;
        }
        $skills[] = $row;
    }
}
?>

<!-- Skills Section -->
<section id="skills" class="py-20 bg-gradient-to-b from-[#232323] via-[#D4A574]/10 to-[#232323]">
    <div class="max-w-7xl mx-auto">
        <!-- Title -->
        <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text text-warm-wood">
            Technical Skills
        </h2>

        <!-- Skills Carousel Container -->
        <div class="relative overflow-hidden select-none" id="skillsContainer">
            <div class="flex skills-carousel" id="skillsCarousel">
                <?php for ($loop = 0; $loop < 4; $loop++): // 4 copy untuk seamless loop 
                ?>
                    <?php foreach ($skills as $skill): ?>
                        <div class="glass rounded-2xl p-6 min-w-[200px] text-center group transition-all duration-300 skill-card">
                            <?php if (!empty($skill['icon'])): ?>
                                <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <img src="<?php echo htmlspecialchars($skill['icon']); ?>"
                                        alt="<?php echo htmlspecialchars($skill['name']); ?>"
                                        class="w-12 h-12 object-contain pointer-events-none" />
                                </div>
                            <?php endif; ?>
                            <h3 class="text-xl font-bold mb-2 text-warm-wood"><?php echo htmlspecialchars($skill['name']); ?></h3>
                            <div class="flex justify-center mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-xl <?php echo $i <= intval($skill['level']) ? 'text-yellow-400' : 'text-gray-500'; ?>">★</span>
                                <?php endfor; ?>
                            </div>
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

<style>
    .skills-carousel {
        display: flex;
        gap: 2rem;
        will-change: transform;
    }

    .skill-card {
        flex-shrink: 0;
        user-select: none;
    }

    .glass {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    #skillsContainer {
        cursor: grab;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    #skillsContainer:active {
        cursor: grabbing;
    }

    #skillsContainer.dragging {
        cursor: grabbing;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('skillsContainer');
        const carousel = document.getElementById('skillsCarousel');

        if (!container || !carousel || <?php echo count($skills); ?> === 0) return;

        // Konfigurasi
        const skillsCount = <?php echo count($skills); ?>;
        const itemWidth = 232; // 200px + 32px gap
        const totalWidth = skillsCount * itemWidth;

        // State management
        let currentTranslate = 0;
        let animationSpeed = 1; // pixels per frame
        let isDragging = false;
        let dragStartX = 0;
        let dragStartTranslate = 0;
        let animationId = null;

        // Initialize position
        carousel.style.transform = `translateX(${currentTranslate}px)`;

        // Auto-scroll animation loop
        function animate() {
            if (!isDragging) {
                currentTranslate -= animationSpeed;

                // Reset saat mencapai 1/4 dari total width (karena kita punya 4 copy)
                if (Math.abs(currentTranslate) >= totalWidth) {
                    currentTranslate = 0;
                }

                carousel.style.transform = `translateX(${currentTranslate}px)`;
            }

            animationId = requestAnimationFrame(animate);
        }

        // Start animation
        animate();

        // Mouse Events
        container.addEventListener('mousedown', handleStart);
        container.addEventListener('mousemove', handleMove);
        container.addEventListener('mouseup', handleEnd);
        container.addEventListener('mouseleave', handleEnd);

        // Touch Events
        container.addEventListener('touchstart', handleStart, {
            passive: false
        });
        container.addEventListener('touchmove', handleMove, {
            passive: false
        });
        container.addEventListener('touchend', handleEnd);
        container.addEventListener('touchcancel', handleEnd);

        function handleStart(e) {
            isDragging = true;
            container.classList.add('dragging');

            const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            dragStartX = clientX;
            dragStartTranslate = currentTranslate;

            e.preventDefault();
        }

        function handleMove(e) {
            if (!isDragging) return;

            e.preventDefault();

            const clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
            const deltaX = clientX - dragStartX;

            // Update position dengan smooth dragging
            let newTranslate = dragStartTranslate + deltaX;

            // Infinite loop boundaries check
            if (newTranslate > 0) {
                newTranslate = -(totalWidth - Math.abs(newTranslate % totalWidth));
            } else if (Math.abs(newTranslate) > totalWidth * 3) {
                newTranslate = -(Math.abs(newTranslate) % totalWidth);
            }

            currentTranslate = newTranslate;
            carousel.style.transform = `translateX(${currentTranslate}px)`;
        }

        function handleEnd(e) {
            if (!isDragging) return;

            isDragging = false;
            container.classList.remove('dragging');

            // Smooth transition back to auto-scroll
            const inertia = e.type.includes('mouse') ? 0 : calculateInertia(e);
            if (Math.abs(inertia) > 5) {
                applyInertia(inertia);
            }
        }

        function calculateInertia(e) {
            // Simple inertia calculation for touch devices
            if (e.changedTouches && e.changedTouches[0]) {
                const touch = e.changedTouches[0];
                return (touch.clientX - dragStartX) * 0.1;
            }
            return 0;
        }

        function applyInertia(inertia) {
            let currentInertia = inertia;
            const friction = 0.95;

            function inertiaStep() {
                if (Math.abs(currentInertia) < 0.5 || isDragging) return;

                currentTranslate += currentInertia;
                currentInertia *= friction;

                // Boundary check
                if (currentTranslate > 0) {
                    currentTranslate = -(totalWidth - Math.abs(currentTranslate % totalWidth));
                } else if (Math.abs(currentTranslate) >= totalWidth * 3) {
                    currentTranslate = -(Math.abs(currentTranslate) % totalWidth);
                }

                carousel.style.transform = `translateX(${currentTranslate}px)`;
                requestAnimationFrame(inertiaStep);
            }

            inertiaStep();
        }

        // Pause on hover (optional)
        container.addEventListener('mouseenter', () => {
            animationSpeed = 0.3; // Slow down
        });

        container.addEventListener('mouseleave', () => {
            if (!isDragging) {
                animationSpeed = 1; // Resume normal speed
            }
        });

        // Prevent text selection and image dragging
        container.addEventListener('selectstart', e => e.preventDefault());
        container.addEventListener('dragstart', e => e.preventDefault());

        // Cleanup
        window.addEventListener('beforeunload', () => {
            if (animationId) {
                cancelAnimationFrame(animationId);
            }
        });
    });
</script>