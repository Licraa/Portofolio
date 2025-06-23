<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-bg': '#0F172A',
                        'dark-surface': '#1E293B',
                        'dark-border': '#334155',
                        'accent-blue': '#3B82F6',
                        'accent-purple': '#8B5CF6',
                        'accent-green': '#10B981',
                        'accent-orange': '#F59E0B'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        bounceGentle: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-5px)'
                            }
                        },
                        glow: {
                            '0%': {
                                boxShadow: '0 0 5px rgba(59, 130, 246, 0.3)'
                            },
                            '100%': {
                                boxShadow: '0 0 20px rgba(59, 130, 246, 0.6)'
                            }
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-dark-bg text-gray-100 font-['Inter'] min-h-screen">
    <!-- Main Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-dark-surface border-r border-dark-border flex flex-col shadow-2xl">
            <!-- Logo & Brand -->
            <div class="p-6 border-b border-dark-border">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-accent-blue to-accent-purple rounded-xl flex items-center justify-center shadow-lg animate-glow">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Portfolio Admin</h1>
                        <p class="text-gray-400 text-sm">Content Management</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2">
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Main Menu</h3>
                    <a href="#dashboard" class="nav-item flex items-center gap-3 p-3 rounded-lg bg-accent-blue/20 text-accent-blue border border-accent-blue/30 transition-all duration-200">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="font-medium">Dashboard</span>
                        <span class="ml-auto bg-accent-blue/30 text-xs px-2 py-1 rounded-full">Active</span>
                    </a>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Content Management</h3>

                    <a href="#profile" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-user w-5 text-gray-400 group-hover:text-accent-green"></i>
                        <span>Edit Profile</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-500 text-xs group-hover:text-accent-green"></i>
                    </a>

                    <a href="#skills" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-code w-5 text-gray-400 group-hover:text-accent-purple"></i>
                        <span>Manage Skills</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-500 text-xs group-hover:text-accent-purple"></i>
                    </a>

                    <a href="#projects" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-folder-open w-5 text-gray-400 group-hover:text-accent-orange"></i>
                        <span>Manage Projects</span>
                        <span class="ml-auto bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded-full">3</span>
                    </a>

                    <a href="#articles" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-newspaper w-5 text-gray-400 group-hover:text-accent-blue"></i>
                        <span>Manage Articles</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-500 text-xs group-hover:text-accent-blue"></i>
                    </a>

                    <a href="#media" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-images w-5 text-gray-400 group-hover:text-accent-green"></i>
                        <span>Media Library</span>
                        <i class="fas fa-chevron-right ml-auto text-gray-500 text-xs group-hover:text-accent-green"></i>
                    </a>
                </div>

                <div class="pt-6 border-t border-dark-border mt-6">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Settings</h3>
                    <a href="#settings" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700/50 transition-all duration-200 group">
                        <i class="fas fa-cog w-5 text-gray-400 group-hover:text-gray-300"></i>
                        <span>Settings</span>
                    </a>
                    <a href="#logout" class="nav-item flex items-center gap-3 p-3 rounded-lg hover:bg-red-900/30 hover:text-red-400 transition-all duration-200 group">
                        <i class="fas fa-sign-out-alt w-5 text-gray-400 group-hover:text-red-400"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile in Sidebar -->
            <div class="p-4 border-t border-dark-border">
                <div class="flex items-center gap-3 p-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-accent-green to-accent-blue rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-sm">AD</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-sm">Admin User</p>
                        <p class="text-gray-400 text-xs">admin@portfolio.com</p>
                    </div>
                    <i class="fas fa-ellipsis-v text-gray-500 text-sm cursor-pointer"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-auto">
            <!-- Top Header -->
            <header class="bg-dark-surface border-b border-dark-border p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-1">Dashboard Overview</h1>
                        <p class="text-gray-400">Manage your portfolio content and settings</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <input type="search" placeholder="Search..." class="bg-dark-bg border border-dark-border rounded-xl px-4 py-2 pl-10 text-sm focus:outline-none focus:border-accent-blue focus:ring-2 focus:ring-accent-blue/20 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm"></i>
                        </div>
                        <button class="relative p-2 bg-dark-bg rounded-xl border border-dark-border hover:border-accent-blue transition-all">
                            <i class="fas fa-bell text-gray-400"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-6 space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in">
                    <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-blue/50 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-accent-blue/20 rounded-xl flex items-center justify-center group-hover:bg-accent-blue/30 transition-all">
                                <i class="fas fa-folder text-accent-blue text-xl"></i>
                            </div>
                            <span class="text-sm text-green-400 font-medium">+12%</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">12</h3>
                        <p class="text-gray-400 text-sm">Active Projects</p>
                    </div>

                    <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-purple/50 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-accent-purple/20 rounded-xl flex items-center justify-center group-hover:bg-accent-purple/30 transition-all">
                                <i class="fas fa-code text-accent-purple text-xl"></i>
                            </div>
                            <span class="text-sm text-green-400 font-medium">+5</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">28</h3>
                        <p class="text-gray-400 text-sm">Skills Listed</p>
                    </div>

                    <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-green/50 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-accent-green/20 rounded-xl flex items-center justify-center group-hover:bg-accent-green/30 transition-all">
                                <i class="fas fa-newspaper text-accent-green text-xl"></i>
                            </div>
                            <span class="text-sm text-green-400 font-medium">+3</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">8</h3>
                        <p class="text-gray-400 text-sm">Published Articles</p>
                    </div>

                    <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-orange/50 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-accent-orange/20 rounded-xl flex items-center justify-center group-hover:bg-accent-orange/30 transition-all">
                                <i class="fas fa-eye text-accent-orange text-xl"></i>
                            </div>
                            <span class="text-sm text-green-400 font-medium">+24%</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">1.2K</h3>
                        <p class="text-gray-400 text-sm">Portfolio Views</p>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Quick Actions -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Homepage Settings -->
                        <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border animate-slide-up">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-xl font-semibold mb-2">Homepage Settings</h2>
                                    <p class="text-gray-400 text-sm">Update your homepage display name and basic info</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-accent-blue to-accent-purple rounded-xl flex items-center justify-center">
                                    <i class="fas fa-home text-white"></i>
                                </div>
                            </div>

                            <div id="success-message" class="hidden mb-4 p-4 bg-green-900/30 border border-green-700/50 rounded-xl text-green-400">
                                <i class="fas fa-check-circle mr-2"></i>
                                Homepage name updated successfully!
                            </div>

                            <form id="homepage-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Display Name</label>
                                    <input type="text" id="homepage_name" value="John Doe" class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:outline-none focus:border-accent-blue focus:ring-2 focus:ring-accent-blue/20 transition-all" placeholder="Enter your display name">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Tagline</label>
                                    <input type="text" placeholder="Full Stack Developer & UI/UX Designer" class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:outline-none focus:border-accent-blue focus:ring-2 focus:ring-accent-blue/20 transition-all">
                                </div>

                                <button type="submit" class="w-full py-3 bg-gradient-to-r from-accent-blue to-accent-purple rounded-xl font-semibold hover:shadow-lg hover:shadow-accent-blue/25 transition-all duration-300 transform hover:scale-[1.02]">
                                    <i class="fas fa-save mr-2"></i>
                                    Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Quick Actions Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-blue/50 transition-all cursor-pointer group">
                                <div class="w-12 h-12 bg-accent-blue/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent-blue/30 transition-all">
                                    <i class="fas fa-plus text-accent-blue text-xl"></i>
                                </div>
                                <h3 class="font-semibold mb-2">Add New Project</h3>
                                <p class="text-gray-400 text-sm">Create and showcase your latest work</p>
                            </div>

                            <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border hover:border-accent-green/50 transition-all cursor-pointer group">
                                <div class="w-12 h-12 bg-accent-green/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent-green/30 transition-all">
                                    <i class="fas fa-edit text-accent-green text-xl"></i>
                                </div>
                                <h3 class="font-semibold mb-2">Write Article</h3>
                                <p class="text-gray-400 text-sm">Share your knowledge and insights</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity & Quick Info -->
                    <div class="space-y-6">
                        <!-- Recent Activity -->
                        <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border">
                            <h3 class="font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-clock text-accent-blue"></i>
                                Recent Activity
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-accent-green rounded-full mt-2 animate-bounce-gentle"></div>
                                    <div>
                                        <p class="text-sm mb-1">Project "E-Commerce App" updated</p>
                                        <p class="text-xs text-gray-500">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-accent-blue rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm mb-1">New skill "React Native" added</p>
                                        <p class="text-xs text-gray-500">5 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-accent-purple rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-sm mb-1">Article "Modern CSS Tips" published</p>
                                        <p class="text-xs text-gray-500">1 day ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="bg-dark-surface rounded-2xl p-6 border border-dark-border">
                            <h3 class="font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-server text-accent-green"></i>
                                System Status
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Website Status</span>
                                    <span class="flex items-center gap-2 text-green-400 text-sm">
                                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                        Online
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Database</span>
                                    <span class="flex items-center gap-2 text-green-400 text-sm">
                                        <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                        Connected
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Storage</span>
                                    <span class="text-sm text-gray-400">2.1GB / 10GB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="bg-gradient-to-br from-accent-blue/10 to-accent-purple/10 rounded-2xl p-6 border border-accent-blue/20">
                            <h3 class="font-semibold mb-3 flex items-center gap-2">
                                <i class="fas fa-lightbulb text-yellow-400"></i>
                                Pro Tip
                            </h3>
                            <p class="text-sm text-gray-300 leading-relaxed">
                                Keep your portfolio updated regularly. Add new projects and skills to showcase your growth and attract more opportunities.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Form submission handling
        document.getElementById('homepage-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show success message
            const successMessage = document.getElementById('success-message');
            successMessage.classList.remove('hidden');

            // Hide success message after 3 seconds
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 3000);

            // Here you would normally send the data to your PHP backend
            console.log('Form submitted with name:', document.getElementById('homepage_name').value);
        });

        // Add smooth scrolling and interactive effects
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all items
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('bg-accent-blue/20', 'text-accent-blue', 'border', 'border-accent-blue/30');
                });

                // Add active class to clicked item
                this.classList.add('bg-accent-blue/20', 'text-accent-blue', 'border', 'border-accent-blue/30');
            });
        });

        // Add loading animation for cards
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('[class*="animate-"]');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>