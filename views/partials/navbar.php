<nav class="bg-gray-900 text-white p-4">
    <div class="container mx-auto flex items-center">
        <a href="index.php" class="text-xl font-bold">Employees Management</a>
        <div class="flex items-center flex-grow justify-center space-x-4">
            <form action="search.php" method="GET" class="flex items-center space-x-2">
                <input type="search" name="query" placeholder="Search by email or ID" class="px-3 py-1 rounded border border-gray-600 bg-gray-900 text-gray-100">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">Search</button>
            </form>
        </div>
        <div class="ml-auto">
            <?php include __DIR__ . '/../../components/logout-btn.php'; ?>
        </div>
    </div>
</nav>
