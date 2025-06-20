<?php
include 'admin/db.php';
// Ambil artikel berdasarkan id dari query string
$article = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM articles WHERE id=$id LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $article = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Understanding React Hooks in Depth - JhonDoe</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#2D2D2D',
                        'light-surface': '#F5F5F5',
                        'accent-green': '#1D4ED8',
                        'warm-wood': '#D4A574'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        },
                        pulseGlow: {
                            '0%': {
                                boxShadow: '0 0 5px rgba(74, 93, 35, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 20px rgba(74, 93, 35, 0.6)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(245, 245, 245, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .prose-custom {
            max-width: none;
        }

        .prose-custom h2 {
            color: #D4A574;
            font-size: 1.875rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .prose-custom p {
            color: #D1D5DB;
            line-height: 1.75;
            margin-bottom: 1.5rem;
        }

        .prose-custom ul {
            color: #D1D5DB;
            margin: 1.5rem 0;
        }

        .prose-custom li {
            margin: 0.5rem 0;
        }

        .prose-custom code {
            background: #374151;
            color: #D4A574;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .prose-custom blockquote {
            border-left: 4px solid #1D4ED8;
            padding-left: 1rem;
            font-style: italic;
            color: #9CA3AF;
            margin: 1.5rem 0;
            background: rgba(29, 78, 216, 0.1);
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-dark-bg text-light-surface overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-warm-wood animate-pulse-glow">
                    Portfolio
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="index.php#home" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Beranda
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="index.php#about" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Tentang
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="index.php#projects" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Proyek
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="index.php#articles" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Artikel
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="index.php#contact" class="hover:text-warm-wood transition-colors duration-300 relative group">
                        Kontak
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-warm-wood transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                <button class="md:hidden text-light-surface focus:outline-none" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
                <a href="index.php#home" class="block py-2 hover:text-warm-wood transition-colors duration-300">Beranda</a>
                <a href="index.php#about" class="block py-2 hover:text-warm-wood transition-colors duration-300">Tentang</a>
                <a href="index.php#projects" class="block py-2 hover:text-warm-wood transition-colors duration-300">Proyek</a>
                <a href="index.php#articles" class="block py-2 hover:text-warm-wood transition-colors duration-300">Artikel</a>
                <a href="index.php#contact" class="block py-2 hover:text-warm-wood transition-colors duration-300">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="pt-20 min-h-screen">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-dark-bg via-accent-green/20 to-dark-bg -z-10"></div>

        <!-- Floating Elements -->
        <div class="absolute top-32 left-10 w-4 h-4 bg-warm-wood rounded-full animate-float opacity-60"></div>
        <div class="absolute bottom-32 right-16 w-6 h-6 bg-accent-green rounded-full animate-float opacity-40" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/3 right-8 w-3 h-3 bg-light-surface rounded-full animate-float opacity-30" style="animation-delay: 4s;"></div>

        <!-- Article Header -->
        <section class="py-16 relative">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto text-center animate-slide-up">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 text-shadow">
                        <span class="text-warm-wood"><?php echo $article ? htmlspecialchars($article['title']) : 'Artikel Tidak Ditemukan'; ?></span>
                    </h1>
                    <div class="flex items-center justify-center space-x-4 text-gray-400 mb-8">
                        <span><?php echo $article ? date('d M Y', strtotime($article['publish_date'])) : '-'; ?></span>
                        <span>•</span>
                        <span>By <?php echo $article ? htmlspecialchars($article['author'] ?? 'Admin') : '-'; ?></span>
                    </div>
                    <?php if ($article && !empty($article['image'])): ?>
                        <div class="glass-effect rounded-2xl p-2 max-w-3xl mx-auto">
                            <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="Article Banner" class="w-full rounded-lg shadow-lg" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Article Content -->
        <section class="py-12 relative">
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto">
                    <div class="glass-effect rounded-2xl p-8 md:p-12 animate-fade-in">
                        <div class="prose-custom">
                            <?php if ($article): ?>
                                <div class="text-lg leading-relaxed"><?php echo nl2br($article['content']); ?></div>
                            <?php else: ?>
                                <div class="text-gray-400 text-center">Artikel tidak ditemukan.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-between items-center animate-slide-up">
                        <a href="index.php#articles"
                            class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back to Articles
                        </a>
                    </div>
                </div>
            </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-dark-bg to-accent-green/20 py-8 border-t border-gray-700 mt-20">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-400 mb-4">© 2025 Portfolio. Raja aryansahPutra.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-warm-wood transition-colors duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-warm-wood transition-colors duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                    </svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-warm-wood transition-colors duration-300">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('backdrop-blur-lg');
            } else {
                nav.classList.remove('backdrop-blur-lg');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all elements with animation classes
        document.querySelectorAll('.animate-slide-up, .animate-fade-in').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
            observer.observe(el);
        });

        // Parallax effect for floating elements
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelectorAll('.animate-float');

            parallax.forEach(element => {
                const speed = 0.2;
                element.style.transform = `translateY(-${scrolled * speed}px)`;
            });
        });
    </script>
</body>

</html>