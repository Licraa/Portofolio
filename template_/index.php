<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $homepage_name; ?> - Portfolio</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(90deg, #6366f1, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-300 min-h-screen scroll-smooth">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-900/90 backdrop-blur-lg border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <span class="font-bold text-white text-lg">R</span>
                    </div>
                    <span class="text-xl font-bold tracking-tight hidden sm:block gradient-text"><?php echo $homepage_name; ?></span>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-6">
                    <a href="#home" class="hover:text-indigo-400 transition-colors duration-200">Home</a>
                    <a href="#about" class="hover:text-indigo-400 transition-colors duration-200">About</a>
                    <a href="#skills" class="hover:text-indigo-400 transition-colors duration-200">Skills</a>
                    <a href="#projects" class="hover:text-indigo-400 transition-colors duration-200">Projects</a>
                    <a href="#articles" class="hover:text-indigo-400 transition-colors duration-200">Articles</a>
                    <a href="#contact" class="hover:text-indigo-400 transition-colors duration-200">Contact</a>
                </div>
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden fixed inset-0 bg-gray-900/95 backdrop-blur-md z-50 flex flex-col items-center justify-center space-y-6 text-lg font-semibold transition-all duration-300 opacity-0 pointer-events-none">
            <button id="mobile-menu-close" class="absolute top-6 right-6 p-2 rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <a href="#home" class="hover:text-indigo-400 transition-colors duration-200">Home</a>
            <a href="#about" class="hover:text-indigo-400 transition-colors duration-200">About</a>
            <a href="#skills" class="hover:text-indigo-400 transition-colors duration-200">Skills</a>
            <a href="#projects" class="hover:text-indigo-400 transition-colors duration-200">Projects</a>
            <a href="#articles" class="hover:text-indigo-400 transition-colors duration-200">Articles</a>
            <a href="#contact" class="hover:text-indigo-400 transition-colors duration-200">Contact</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen pt-16 flex items-center justify-center px-4 bg-gray-950 relative overflow-hidden">
        <div class="max-w-6xl mx-auto flex flex-col items-center text-center relative z-10 w-full">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-4 gradient-text animate-fadeInUp">
                Hi, I'm <?php echo $homepage_name; ?>
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl text-gray-400 mb-8 max-w-2xl animate-fadeInUp" style="animation-delay: 0.4s;">
                Backend Developer | Problem Solver | Fresh Graduate
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mb-24 animate-fadeInUp" style="animation-delay: 0.6s;">
                <a href="#contact" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    Get In Touch
                </a>
                <a href="#projects" class="px-8 py-4 border-2 border-indigo-400 text-indigo-400 hover:bg-indigo-400 hover:text-white rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    View My Work
                </a>
            </div>
        </div>
    </section>


    <!-- About Section -->
    <section id="about" class="py-20 px-4 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text">
                About Me
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Profile Image -->
                <div class="flex justify-center lg:justify-start">
                    <img src="Profile.jpg" alt="Profile photo of <?php echo $homepage_name; ?>" class="w-80 h-96 object-cover rounded-2xl shadow-lg">
                </div>
                <!-- About Content -->
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold mb-6 text-indigo-400">
                        Backend Developer & Problem Solver
                    </h3>
                    <p class="text-gray-400 mb-6 text-lg leading-relaxed">
                        I'm a passionate backend developer and recent graduate from Universitas Maritim Raja Ali Haji, eager to make my mark in the tech industry. I specialize in building robust server-side applications, designing efficient APIs, and working with various database systems.
                    </p>
                    <p class="text-gray-400 mb-8 text-lg leading-relaxed">
                        My expertise spans across PHP, Node.js, and Python, with a strong foundation in database management and API development.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#contact" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-center transition-all duration-300 transform hover:scale-105">
                            Contact Me
                        </a>
                        <a href="#" class="px-6 py-3 border-2 border-indigo-400 text-indigo-400 hover:bg-indigo-400 hover:text-white rounded-lg font-semibold text-center transition-all duration-300 transform hover:scale-105">
                            Download CV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <?php include 'skills.php' ?>

    <!-- Projects Section -->
    <section id="projects" class="py-20 px-4 bg-gray-950">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text">Projects</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Project Card Example -->
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg flex flex-col">
                    <img src="https://placehold.co/600x400/1e293b/93c5fd?text=E-commerce" alt="E-commerce" class="rounded-xl mb-4 object-cover h-48 w-full">
                    <h3 class="text-2xl font-bold mb-2 text-indigo-400">E-commerce Platform</h3>
                    <p class="text-gray-400 mb-4">A full-featured e-commerce platform built with React and Node.js.</p>
                    <a href="#" class="mt-auto px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-center transition-all duration-300">View Project</a>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg flex flex-col">
                    <img src="https://placehold.co/600x400/1e293b/93c5fd?text=Dashboard" alt="Dashboard" class="rounded-xl mb-4 object-cover h-48 w-full">
                    <h3 class="text-2xl font-bold mb-2 text-purple-400">Dashboard UI Kit</h3>
                    <p class="text-gray-400 mb-4">Modern dashboard UI kit built with Tailwind CSS and React components.</p>
                    <a href="#" class="mt-auto px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-center transition-all duration-300">View Project</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section id="articles" class="py-20 px-4 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text">Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Article Card Example -->
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg flex flex-col">
                    <h3 class="text-2xl font-bold mb-2 text-indigo-400">How to Build a REST API with PHP</h3>
                    <p class="text-gray-400 mb-4">Panduan lengkap membangun REST API menggunakan PHP dan best practice-nya.</p>
                    <a href="articles.php" class="mt-auto px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-center transition-all duration-300">Read More</a>
                </div>
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg flex flex-col">
                    <h3 class="text-2xl font-bold mb-2 text-purple-400">Tips Produktif untuk Fresh Graduate Developer</h3>
                    <p class="text-gray-400 mb-4">Tips dan trik agar tetap produktif dan berkembang di dunia kerja IT sebagai developer muda.</p>
                    <a href="articles.php" class="mt-auto px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-center transition-all duration-300">Read More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 px-4 bg-gray-950">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold mb-12 text-center gradient-text">Contact</h2>
            <form class="bg-gray-900 rounded-2xl p-8 shadow-xl space-y-6">
                <div>
                    <label for="name" class="block text-gray-400 font-semibold mb-2">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-gray-100 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-400 font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-gray-100 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="message" class="block text-gray-400 font-semibold mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 rounded-lg bg-gray-800 text-gray-100 border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-lg font-semibold text-white text-lg transition-all duration-300">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 bg-gray-900 text-gray-500 text-center text-sm">
        &copy; 2025 <?php echo $homepage_name; ?>. All rights reserved.
    </footer>

    <!-- Optional: Add JS for mobile menu toggle -->
    <script>
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('mobile-menu-close');
        menuBtn.addEventListener('click', () => {
            menu.classList.remove('opacity-0', 'pointer-events-none');
            menu.classList.add('opacity-100');
        });
        closeBtn.addEventListener('click', () => {
            menu.classList.add('opacity-0', 'pointer-events-none');
            menu.classList.remove('opacity-100');
        });
    </script>
</body>

</html>