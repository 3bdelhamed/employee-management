<?php
$messages = $_SESSION['done'] ?? [];
unset($_SESSION['done']);
?>

<?php if (!empty($messages)): ?>
    <div id="success-message" class="fixed top-4 left-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 w-96 shadow-lg opacity-0 transition-opacity duration-500 ease-in-out" role="alert">
        <strong class="font-bold">Success!</strong>
        <ul class="mt-2 list-disc list-inside">
            <?php foreach ($messages as $message): ?>
                <li><?= htmlspecialchars($message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script>
        window.onload = function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.classList.add('opacity-100');
            }
        };

        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.classList.remove('opacity-100');
                successMessage.classList.add('opacity-0');
            }
        }, 3000);
    </script>
<?php endif; ?>