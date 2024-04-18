<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w-1/2 mx-auto dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto my-5 w-full">
                    <livewire:weather-component />
                    <div class="mt-20 p-5">
                        <h2 class="text-xl font-semibold mb-2 dark:text-white">Set Email Notification</h2>
                        <form action="{{ route('set-email-notification') }}" method="POST">
                            @csrf
                            <div class="mb-4 ">
                                <p class="text-sm mb-4 dark:text-white text-gray-500">Set the time you would like to receive email notifications with the weather and clothing suggestions. The email will be sent to the email address associated with your account every day at the specified time.</p>
                                <label for="preferred_email_time" class="dark:text-white block text-sm font-medium text-gray-700">Preferred Email Time</label>
                                <input type="time" id="preferred_email_time" name="preferred_email_time" class="mt-1 p-2 block w-full border rounded-md" required>
                                <label for="city" class="block text-sm font-medium dark:text-white text-gray-700 mt-4">City</label>
                                <input type="text" id="city" name="city" class="mt-1 p-2 block w-full border rounded-md" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
</x-app-layout>