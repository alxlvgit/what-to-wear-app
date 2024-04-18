<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <div class="max-w-xl">
        <h2 class="text-xl font-semibold mb-2 dark:text-white">Email Notification Settings</h2>
        <p class="text-sm mb-4 dark:text-white text-gray-500">You will receive email notifications with the weather and clothing suggestions every day at {{ auth()->user()->preferred_email_time }} for city {{ auth()->user()->city }}.</p>
    </div>
</div>