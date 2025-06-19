<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Understanding React Hooks in Depth - JhonDoe</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen">

    <!-- Sidebar Navigation -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="ml-64 p-6 md:p-10 overflow-auto max-h-screen">

        <!-- Article Header -->
        <section class="mb-10">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl font-bold mb-4">Understanding React Hooks in Depth</h1>
                <p class="text-gray-400 mb-6">Published on April 5, 2025 • By JhonDoe</p>
                <img src="https://placehold.co/800x400/1e293b/93c5fd?text=React+Hooks" alt="Article Banner" class="w-full rounded-lg shadow-lg mb-8" />
            </div>
        </section>

        <!-- Article Content -->
        <section class="max-w-3xl mx-auto">
            <div class="prose prose-invert prose-blue max-w-none">
                <p class="text-lg leading-relaxed">
                    React Hooks are a game-changer in the world of React development. Introduced in React 16.8, they allow developers to use state and other React features without writing classes. This article dives deep into how hooks work under the hood, their benefits, and common use cases.
                </p>

                <h2 class="text-2xl font-semibold mt-8 mb-4">What Are React Hooks?</h2>
                <p>
                    Hooks are functions that let you "hook into" React state and lifecycle features from function components. They provide a more direct API to the React concepts you already know: props, state, context, refs, and lifecycle.
                </p>

                <h2 class="text-2xl font-semibold mt-8 mb-4">The Most Commonly Used Hooks</h2>
                <ul class="list-disc pl-6 space-y-2 mb-6">
                    <li><code class="bg-gray-800 px-1 py-0.5 rounded">useState</code>: For managing component state.</li>
                    <li><code class="bg-gray-800 px-1 py-0.5 rounded">useEffect</code>: For side effects like data fetching or subscriptions.</li>
                    <li><code class="bg-gray-800 px-1 py-0.5 rounded">useContext</code>: To consume React Context without wrapping your component in a Consumer.</li>
                </ul>

                <h2 class="text-2xl font-semibold mt-8 mb-4">Why Use Hooks?</h2>
                <p>
                    Hooks make it easier to reuse logic between components, reduce boilerplate code, and simplify complex patterns like higher-order components and render props. They also enable better composition of logic across components.
                </p>

                <blockquote class="border-l-4 border-blue-500 pl-4 italic text-gray-300 my-6">
                    “Hooks solve a wide variety of seemingly unrelated problems in React that we've encountered over five years of developing and maintaining thousands of components.”
                </blockquote>

                <h2 class="text-2xl font-semibold mt-8 mb-4">Best Practices</h2>
                <p>
                    Always call hooks at the top level of your React function. Don't call hooks inside loops, conditions, or nested functions. Also, only call hooks from React functions — not regular JavaScript functions.
                </p>

                <p class="mt-6">
                    Hooks are one of the most powerful tools in modern React development. If used correctly, they can significantly improve code readability, maintainability, and performance.
                </p>
            </div>

            <!-- Back to Articles Button -->
            <div class="mt-12">
                <a href="index.php#articles" class="inline-block px-5 py-2 bg-gray-800 hover:bg-gray-700 rounded-md transition-colors text-sm">
                    ← Back to Articles
                </a>
            </div>

            <!-- Comments Section (Optional) -->
            <div class="mt-16 pt-8 border-t border-gray-800">
                <h3 class="text-xl font-semibold mb-4">Leave a Comment</h3>
                <form method="POST" class="space-y-4 max-w-xl">
                    <input type="text" placeholder="Your Name" class="w-full bg-gray-800 border border-gray-700 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                    <input type="email" placeholder="Your Email" class="w-full bg-gray-800 border border-gray-700 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
                    <textarea rows="4" placeholder="Your Comment" class="w-full bg-gray-800 border border-gray-700 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-2 transition-colors">Post Comment</button>
                </form>
            </div>
        </section>

    </main>

</body>

</html>